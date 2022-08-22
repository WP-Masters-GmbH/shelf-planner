<?php // -*- coding: utf-8 -*-

/**
 * Report table sync related functions and actions.
 *
 *
 * @package QuickAssortmentsSP\COG\RISP
 */

namespace QuickAssortmentsSP\COG\RI;

defined('ABSPATH') || exit;

use QuickAssortmentsSP\COG\Helpers;
use QuickAssortmentsSP\COG\RI\API\Reports\CacheSP as ReportsCache;

/**
 * Class DataSyncSP.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortmentsSP\COG\RISP
 *
 * @since   2.0.0
 */
final class DataSyncSP
{
    /**
     * Action hook for reducing a range of batches down to single actions.
     */
    const QUEUE_BATCH_ACTION = 'qa-cog_queue_batches';

    /**
     * Action hook for importing a batch of orders.
     */
    const ORDERS_IMPORT_BATCH_ACTION = 'qa-cog_import_orders_batch';

    /**
     * Action hook for initializing the orders lookup batch creation.
     */
    const ORDERS_IMPORT_BATCH_INIT = 'qa-cog_orders_lookup_import_batch_init';

    /**
     * Action hook for deleting a batch of orders.
     */
    const ORDERS_DELETE_BATCH_ACTION = 'qa-cog_delete_order';

    /**
     * Action hook for importing a single order.
     */
    const SINGLE_ORDER_IMPORT_ACTION = 'qa-cog_import_order';

    /**
     * Action scheduler group.
     */
    const QUEUE_GROUP = 'qa-cog-data';

    /**
     * Queue instance.
     *
     * @var \WC_Queue_Interface
     */
    protected static $queue = null;

    /**
     * Set queue instance.
     *
     * @param \WC_Queue_Interface $queue Queue instance.
     *
     * @since 2.0.0
     */
    public function set_queue($queue)
    {
        self::$queue = $queue;
    }

    /**
     * Hook in sync methods.
     *
     * @return void
     *
     * @since 2.0.0
     */
    public function init()
    {
        // Initialize syncing hooks.
        add_action('wp_loaded', [$this, 'orders_lookup_update_init']);

        // Initialize scheduled action handlers.
        add_action(self::QUEUE_BATCH_ACTION, [$this, 'queue_batches'], 10, 4);
        add_action(self::ORDERS_IMPORT_BATCH_ACTION, [$this, 'orders_lookup_import_batch'], 10, 4);
        add_action(self::ORDERS_IMPORT_BATCH_INIT, [$this, 'orders_lookup_import_batch_init'], 10, 3);
        add_action(self::ORDERS_DELETE_BATCH_ACTION, [$this, 'delete_order'], 10);
        add_action(self::SINGLE_ORDER_IMPORT_ACTION, [$this, 'orders_lookup_import_order']);

        add_action('sp_cog_order_stats_table_delete_order_stats', [__CLASS__, 'sync_on_order_delete'], 10);
    }

    /**
     * Schedule an action to import a single OrderSP.
     *
     * @since 2.0.0
     *
     * @param int $order_id OrderSP ID.
     *
     * @throws \Exception
     *
     * @return void
     *
     */
    public function schedule_single_order_import($order_id)
    {
        if ('shop_order' !== get_post_type($order_id) && 'woocommerce_refund_created' !== current_filter()) {
            return;
        }

        if (apply_filters('woocommerce_disable_order_scheduling', false)) {
            $this->orders_lookup_import_order($order_id);

            return;
        }

        // This can get called multiple times for a single order, so we look
        // for existing pending jobs for the same order to avoid duplicating efforts.
        $existing_jobs = $this->queue()->search(
            [
                'status'   => 'pending',
                'per_page' => 1,
                'claimed'  => false,
                'search'   => "[{$order_id}]",
                'group'    => self::QUEUE_GROUP,
            ]
        );

        if ($existing_jobs) {
            $existing_job = current($existing_jobs);

            // Bail out if there's a pending single order action, or a pending dependent action.
            if (
                self::SINGLE_ORDER_IMPORT_ACTION === $existing_job->get_hook()
                || in_array(self::SINGLE_ORDER_IMPORT_ACTION, $existing_job->get_args(), true)
            ) {
                return;
            }
        }

        // We want to ensure that customer lookup updates are scheduled before order updates.
        $this->queue()->schedule_single(time() + 5, self::SINGLE_ORDER_IMPORT_ACTION, [$order_id], self::QUEUE_GROUP);
    }

    /**
     * Imports a single order or refund to update lookup tables for.
     * If an error is encountered in one of the updates, a retry action is scheduled.
     *
     * @param int $order_id OrderSP or refund ID.
     *
     * @throws \Exception
     *
     * @return void
     *
     * @since 2.0.0
     *
     */
    public function orders_lookup_import_order($order_id)
    {
        $order = wc_get_order($order_id);

        // If the order isn't found for some reason, skip the sync.
        if (! $order) {
            return;
        }

        $type = $order->get_type();

        // If the order isn't the right type, skip sync.
        if ('shop_order' !== $type && 'shop_order_refund' !== $type) {
            return;
        }

        // If the order has no id or date created, skip sync.
        if (! $order->get_id() || ! $order->get_date_created()) {
            return;
        }

        $result = array_sum(
            [
                $this->sync_order($order_id),
                $this->sync_order_products($order_id),
            ]
        );

        ReportsCache::invalidate();

        // If all updates were either skipped or successful, we're done.
        // The update methods return -1 for skip, or a boolean success indicator.
        if (2 === absint($result)) {
            return;
        }

        // Otherwise assume an error occurred and reschedule.
        $this->schedule_single_order_import($order_id);
    }

    /**
     * Add order information to the lookup table when orders are created or modified.
     *
     * @param int $post_id Post ID.
     *
     * @return int|bool Returns -1 if order won't be processed, or a boolean indicating processing success.
     *
     * @since 2.0.0
     *
     */
    public function sync_order($post_id)
    {
        if ('shop_order' !== get_post_type($post_id) && 'shop_order_refund' !== get_post_type($post_id)) {
            return -1;
        }

        $order = wc_get_order($post_id);
        if (! $order) {
            return -1;
        }

        return $this->update($order);
    }

    /**
     * Update the database with stats data.
     *
     * @param \WC_Order | \WC_Order_Refund $order OrderSP or refund to update row for.
     *
     * @return int|bool Returns -1 if order won't be processed, or a boolean indicating processing success.
     *
     * @since 2.0.0
     *
     */
    public function update($order)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'sp_cog_order_stats';

        if (! $order->get_id() || ! $order->get_date_created()) {
            return -1;
        }

        $data   = [
            'order_id'         => $order->get_id(),
            'parent_id'        => $order->get_parent_id(),
            'date_created'     => $order->get_date_created()->date('Y-m-d H:i:s'),
            'date_created_gmt' => gmdate('Y-m-d H:i:s', $order->get_date_created()->getTimestamp()),
            'num_items_sold'   => $this->get_num_items_sold($order),
            'gross_total'      => $order->get_total(),
            'tax_total'        => $order->get_total_tax(),
            'shipping_total'   => $order->get_shipping_total(),
            'net_total'        => $this->get_net_total($order),
            'status'           => $this->normalize_order_status($order->get_status()),
        ];
        $format = [
            '%d',
            '%d',
            '%s',
            '%s',
            '%d',
            '%f',
            '%f',
            '%f',
            '%f',
            '%s',
        ];

        if ('shop_order_refund' === $order->get_type()) {
            $parent_order = wc_get_order($order->get_parent_id());
            if ($parent_order) {
                $data['parent_id'] = $parent_order->get_id();
                $format[]          = '%d';
            }
        }

        // Update or add the information to the DBSP.
        $result = $wpdb->replace($table_name, $data, $format);

        /**
         * Fires when order's stats reports are updated.
         *
         * @param int $order_id OrderSP ID.
         */
        do_action('sp_cog_order_stats_table_update', $order->get_id());

        // Check the rows affected for success. Using REPLACE can affect 2 rows if the row already exists.
        return (1 === $result || 2 === $result);
    }

    /**
     * Get number of items sold among all orders.
     *
     * @param \WC_Order $order WC_Order object.
     *
     * @return int
     *
     * @since 2.0.0
     *
     */
    public function get_num_items_sold($order)
    {
        $num_items = 0;

        $line_items = $order->get_items('line_item');
        foreach ($line_items as $line_item) {
            $num_items += $line_item->get_quantity();
        }

        return $num_items;
    }

    /**
     * Get the net amount from an order without shipping, tax, or refunds.
     *
     * @param \WC_Order $order WC_Order object.
     *
     * @return float
     *
     * @since 2.0.0
     *
     */
    public function get_net_total($order)
    {
        $net_total = floatval($order->get_total()) - floatval($order->get_total_tax()) - floatval($order->get_shipping_total());

        return (float) $net_total;
    }

    /**
     * Maps order status provided by the user to the one used in the database.
     *
     * @param string $status OrderSP status.
     *
     * @return string
     *
     * @since 2.0.0
     *
     */
    public function normalize_order_status($status)
    {
        $status = trim($status);

        return 'wc-' . $status;
    }

    /**
     * Create or update an entry in the wc_admin_order_product_lookup table for an order.
     *
     * @param int $order_id OrderSP ID.
     *
     * @throws \Exception
     *
     * @return int|bool Returns -1 if order won't be processed, or a boolean indicating processing success.
     *
     * @since 2.0.0
     */
    public function sync_order_products($order_id)
    {
        global $wpdb;

        $order = wc_get_order($order_id);
        if (! $order) {
            return -1;
        }

        $order_items = $order->get_items();
        $num_updated = 0;
	    $table = $wpdb->prefix.'sp_product_settings';

        foreach ($order_items as $order_item) {
            $order_item_id       = $order_item->get_id();
            $product_qty         = $order_item->get_quantity('edit');
            $shipping_amount     = Helpers\OrderSP::get_item_shipping_amount($order_item, $order);
            $shipping_tax_amount = Helpers\OrderSP::get_item_shipping_tax_amount($order_item, $order);
            $coupon_amount       = Helpers\OrderSP::get_item_coupon_amount($order_item);

            // Tax amount.
            $tax_amount  = 0;
            $order_taxes = $order->get_taxes();
            $tax_data    = $order_item->get_taxes();
            foreach ($order_taxes as $tax_item) {
                $tax_item_id = $tax_item->get_rate_id();
                $tax_amount += isset($tax_data['total'][$tax_item_id]) ? $tax_data['total'][$tax_item_id] : 0;
            }

            $net_revenue  = $order_item->get_subtotal('edit');
            $product_id   = wc_get_order_item_meta($order_item_id, '_product_id');
            $variation_id = wc_get_order_item_meta($order_item_id, '_variation_id');

            if ($variation_id && $variation_id > 0) {
                $product_id = $variation_id;
            }

            $data = $wpdb->get_row("SELECT * FROM {$table} WHERE product_id='{$product_id}'");
            $product_cost = $data->sp_cost;

            $data   = [
                'order_item_id'         => $order_item_id,
                'order_id'              => $order->get_id(),
                'product_id'            => $product_id,
                'variation_id'          => $variation_id,
                'product_qty'           => $product_qty,
                'product_net_revenue'   => $net_revenue = $net_revenue - $coupon_amount,
                'date_created'          => $order->get_date_created('edit')->date(TimeIntervalSP::$sql_datetime_format),
                'coupon_amount'         => $coupon_amount,
                'tax_amount'            => $tax_amount,
                'shipping_amount'       => $shipping_amount,
                'shipping_tax_amount'   => $shipping_tax_amount,
                // @todo Can this be incorrect if modified by filters?
                'product_gross_revenue' => $net_revenue + $tax_amount + $shipping_amount + $shipping_tax_amount,
                'product_cost'          => $product_cost = ($product_cost * $product_qty) +20,
                'product_net_profit'    => $net_profit = ($net_revenue - $product_cost),
                'product_margin_ex_tax' => Helpers\FormulaeSP::margin($product_cost, $net_revenue) * $product_qty,
                'product_margin_in_tax' => Helpers\FormulaeSP::margin($product_cost, ($net_revenue + $tax_amount)) * $product_qty,
            ];
            $format = [
                '%d', // order_item_id.
                '%d', // order_id.
                '%d', // product_id.
                '%d', // variation_id.
                '%d', // product_qty.
                '%f', // product_net_revenue.
                '%s', // date_created.
                '%f', // coupon_amount.
                '%f', // tax_amount.
                '%f', // shipping_amount.
                '%f', // shipping_tax_amount.
                '%f', // product_gross_revenue.
                '%f', // product_cost.
                '%f', // product_net_profit.
                '%f', // product_margin_ex_vat.
                '%f', // product_margin_in_vat.
            ];

            if ('shop_order_refund' === $order->get_type() && $order->get_parent_id() > 0) {
                if ($product_qty > 0) {
                    $data['product_cost'] = $this->get_parent_order_product_cost(
                        $order->get_parent_id(),
                        $data['product_id'],
                        $data['variation_id']
                    );
                } else {
                    $data['product_cost'] = 0;
                }
                $data['product_cost']       = -1 * abs($data['product_cost'] * $product_qty);
                $data['product_net_profit'] = -1 * abs($net_revenue - $data['product_cost']);
                $data['product_margin']     = -1 * abs(Helpers\FormulaeSP::margin($data['product_cost'], $net_revenue));
            }

            $result = $wpdb->replace($wpdb->prefix . 'sp_cog_order_product_lookup', $data, $format); // WPCS: cache ok, DBSP call ok, unprepared SQL ok.

            /**
             * Fires when product's reports are updated.
             *
             * @param int $order_item_id OrderSP Item ID.
             * @param int $order_id      OrderSP ID.
             *
             * @since 2.0.0
             *
             */
            do_action('qa_cog_lookup_table_update_product', $order_item_id, $order->get_id());

            // Sum the rows affected. Using REPLACE can affect 2 rows if the row already exists.
            $num_updated += 2 === intval($result) ? 1 : intval($result);
        }

        return (count($order_items) === $num_updated);
    }

    /**
     * Will return the parent order product cost.
     *
     * @author Khan Mohammad R. <khan@quickassortments.com>
     *
     * @param int $order_id
     * @param int $product_id
     * @param int $variation_id
     *
     * @return float
     *
     * @since  2.0.0
     *
     */
    public function get_parent_order_product_cost($order_id, $product_id, $variation_id = 0)
    {
        global $wpdb;
        $result = $wpdb->get_results("SELECT product_cost, product_qty from {$wpdb->prefix}sp_cog_order_product_lookup WHERE product_id LIKE {$product_id} AND variation_id LIKE {$variation_id} AND order_id LIKE {$order_id} LIMIT 1", ARRAY_A);

        return ! empty($result[0]) ? $result[0]['product_cost'] / $result[0]['product_qty'] : 0;
    }

    /**
     * Get queue instance.
     *
     * @return \WC_Queue_Interface
     *
     * @since 2.0.0
     */
    public function queue()
    {
        if (is_null(self::$queue)) {
            self::$queue = WC()->queue();
        }

        return self::$queue;
    }

    /**
     * Schedule an action to import a single OrderSP.
     *
     * @param int $order_id OrderSP ID.
     *
     * @return void
     *
     * @since 2.0.0
     */
    public function schedule_single_order_delete($order_id)
    {
        // This can get called multiple times for a single order, so we look
        // for existing pending jobs for the same order to avoid duplicating efforts.
        $existing_jobs = $this->queue()->search(
            [
                'status'   => 'pending',
                'per_page' => 1,
                'claimed'  => false,
                'search'   => "[{$order_id}]",
                'group'    => self::QUEUE_GROUP,
            ]
        );

        if ($existing_jobs) {
            $existing_job = current($existing_jobs);

            // Bail out if there's a pending single order action, or a pending dependent action.
            if (
                self::ORDERS_DELETE_BATCH_ACTION === $existing_job->get_hook()
                || in_array(self::ORDERS_DELETE_BATCH_ACTION, $existing_job->get_args(), true)
            ) {
                return;
            }
        }

        // We want to ensure that customer lookup updates are scheduled before order updates.
        $this->queue()->schedule_single(time() + 5, self::ORDERS_DELETE_BATCH_ACTION, [$order_id], self::QUEUE_GROUP);
    }

    /**
     * Attach order lookup update hooks.
     *
     * @return void
     *
     * @since 2.0.0
     *
     */
    public function orders_lookup_update_init()
    {
        // OrderSP and refund data must be run on these hooks to ensure meta data is set.
        add_action('save_post', [$this, 'schedule_single_order_import']);
        add_action('woocommerce_refund_created', [$this, 'schedule_single_order_import']);

        add_action('after_delete_post', [$this, 'schedule_single_order_delete']);
    }

    /**
     * Init order/product lookup tables update (in batches).
     *
     * @param int|bool $days          Number of days to import.
     * @param bool     $skip_existing Skip existing records.
     *
     * @return void
     *
     * @since 2.0.0
     *
     */
    public function orders_lookup_import_batch_init($days, $skip_existing)
    {
        $batch_size = $this->get_batch_size(self::ORDERS_IMPORT_BATCH_ACTION);
        $orders     = $this->get_orders(1, 1, $days, $skip_existing);

        if (0 === $orders->total) {
            return;
        }

        $num_batches = ceil($orders->total / $batch_size);

        $this->queue_batches(1, $num_batches, self::ORDERS_IMPORT_BATCH_ACTION, [$days, $skip_existing]);
    }

    /**
     * Returns the batch size for regenerating reports.
     * Note: can differ per batch action.
     *
     * @param string $action Single batch action name.
     *
     * @return int Batch size.
     *
     * @since 2.0.0
     *
     */
    public function get_batch_size($action)
    {
        $batch_sizes = [
            self::QUEUE_BATCH_ACTION         => 100,
            self::ORDERS_IMPORT_BATCH_ACTION => 10,
            self::ORDERS_DELETE_BATCH_ACTION => 10,
        ];
        $batch_size  = isset($batch_sizes[$action]) ? $batch_sizes[$action] : 25;

        /**
         * Filter the batch size for regenerating a report table.
         *
         * @param int    $batch_size Batch size.
         * @param string $action     Batch action name.
         *
         * @since 2.0.0
         *
         */
        return apply_filters('qa_cog_report_regenerate_batch_size', $batch_size, $action);
    }

    /**
     * Get the order/refund IDs and total count that need to be synced.
     *
     * @param int      $limit         Number of records to retrieve.
     * @param int      $page          PageSP number.
     * @param int|bool $days          Number of days prior to current date to limit search results.
     * @param bool     $skip_existing Skip already imported orders.
     *
     * @return object
     *
     * @since 2.0.0
     *
     */
    public function get_orders($limit = 10, $page = 1, $days = false, $skip_existing = false)
    {
        global $wpdb;
        $where_clause = '';
        $offset       = $page > 1 ? ($page - 1) * $limit : 0;

        if (is_int($days)) {
            $days_ago     = date('Y-m-d 00:00:00', time() - (DAY_IN_SECONDS * $days));
            $where_clause .= " AND post_date >= '{$days_ago}'";
        }

        if ($skip_existing) {
            $where_clause .= " AND NOT EXISTS (
				SELECT 1 FROM {$wpdb->prefix}sp_cog_order_stats
				WHERE {$wpdb->prefix}sp_cog_order_stats.order_id = {$wpdb->posts}.ID
			)";
        }

        $count = $wpdb->get_var(
            "SELECT COUNT(*) FROM {$wpdb->posts}
			WHERE post_type IN ( 'shop_order', 'shop_order_refund' )
			AND post_status NOT IN ( 'wc-auto-draft', 'auto-draft', 'trash' )
			{$where_clause}"
        ); // WPCS: unprepared SQL ok.

        $order_ids = absint($count) > 0 ? $wpdb->get_col(
            $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts}
				WHERE post_type IN ( 'shop_order', 'shop_order_refund' )
				AND post_status NOT IN ( 'auto-draft', 'trash' )
				{$where_clause}
				ORDER BY post_date ASC
				LIMIT %d
				OFFSET %d",
                $limit,
                $offset
            )
        ) : []; // WPCS: unprepared SQL ok.

        return (object) [
            'total'     => absint($count),
            'order_ids' => $order_ids,
        ];
    }

    /**
     * Queue a large number of batch jobs, respecting the batch size limit.
     * Reduces a range of batches down to "single batch" jobs.
     *
     * @param int    $range_start         Starting batch number.
     * @param int    $range_end           Ending batch number.
     * @param string $single_batch_action Action to schedule for a single batch.
     * @param array  $action_args         Action arguments.
     *
     * @return void
     *
     * @since 2.0.0
     *
     */
    public function queue_batches($range_start, $range_end, $single_batch_action, $action_args = [])
    {
        $batch_size       = $this->get_batch_size(self::QUEUE_BATCH_ACTION);
        $range_size       = 1 + ($range_end - $range_start);
        $action_timestamp = time() + 5;

        if ($range_size > $batch_size) {
            // If the current batch range is larger than a single batch,
            // split the range into $queue_batch_size chunks.
            $chunk_size = (int) ceil($range_size / $batch_size);

            for ($i = 0; $i < $batch_size; $i++) {
                $batch_start = (int) ($range_start + ($i * $chunk_size));
                $batch_end   = (int) min($range_end, $range_start + ($chunk_size * ($i + 1)) - 1);

                if ($batch_start > $range_end) {
                    return;
                }

                $this->queue()->schedule_single(
                    $action_timestamp,
                    self::QUEUE_BATCH_ACTION,
                    [$batch_start, $batch_end, $single_batch_action, $action_args],
                    self::QUEUE_GROUP
                );
            }
        } else {
            // Otherwise, queue the single batches.
            for ($i = $range_start; $i <= $range_end; $i++) {
                $batch_action_args = array_merge([$i], $action_args);
                $this->queue()->schedule_single($action_timestamp, $single_batch_action, $batch_action_args, self::QUEUE_GROUP);
            }
        }
    }

    /**
     * Imports a batch of orders to update (stats and products).
     *
     * @param int      $batch_number  Batch number to import (essentially a query page number).
     * @param int|bool $days          Number of days to import.
     * @param bool     $skip_existing Skip exisiting records.
     *
     * @return void
     *
     * @since 2.0.0
     *
     */
    public function orders_lookup_import_batch($batch_number, $days, $skip_existing)
    {
        $batch_size = $this->get_batch_size(self::ORDERS_IMPORT_BATCH_ACTION);

        $properties = [
            'batch_number' => $batch_number,
            'batch_size'   => $batch_size,
            'type'         => 'order',
        ];
        $this->qa_cog_record_tracks_event('import_job_start', $properties);

        // When we are skipping already imported orders, the table of orders to import gets smaller in
        // every batch, so we want to always import the first page.
        $page   = $skip_existing ? 1 : $batch_number;
        $orders = $this->get_orders($batch_size, $page, $days, $skip_existing);

        foreach ($orders->order_ids as $order_id) {
            $this->orders_lookup_import_order($order_id);
        }

        $imported_count = get_option('qa_cog_import_orders_count', 0);
        update_option('qa_cog_import_orders_count', $imported_count + count($orders->order_ids));

        $properties['imported_count'] = $imported_count;

        $this->qa_cog_record_tracks_event('import_job_complete', $properties);
    }

    /**
     * Record an event using Tracks.
     *
     * @param string $event_name Event name for tracks.
     * @param array  $properties Properties to pass along with event.
     *
     * @return void
     *
     * @internal WooCommerce core only includes Tracks in admin, not the REST API, so we need to include it.
     *
     * @since    2.0.0
     *
     */
    public function qa_cog_record_tracks_event($event_name, $properties = [])
    {
        if (! class_exists('WC_Tracks')) {
            if (! defined('WC_ABSPATH') || ! file_exists(WC_ABSPATH . 'includes/tracks/class-wc-tracks.php')) {
                return;
            }
            include_once WC_ABSPATH . 'includes/tracks/class-wc-tracks.php';
            include_once WC_ABSPATH . 'includes/tracks/class-wc-tracks-event.php';
            include_once WC_ABSPATH . 'includes/tracks/class-wc-tracks-client.php';
            include_once WC_ABSPATH . 'includes/tracks/class-wc-tracks-footer-pixel.php';
            include_once WC_ABSPATH . 'includes/tracks/class-wc-site-tracking.php';
        }

        \WC_Tracks::record_event($event_name, $properties);
    }

    /**
     * Deletes the order stats when an order is deleted.
     *
     * @param int $post_id Post ID.
     *
     * @return void
     *
     * @since 2.0.0
     *
     */
    public function delete_order($post_id)
    {
        global $wpdb;
        $order_id   = (int) $post_id;
        $table_name = $wpdb->prefix . 'sp_cog_order_stats';

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM ${table_name} WHERE order_id = %d",
                $order_id
            )
        );

        /**
         * Fires when orders stats are deleted.
         *
         * @param int $order_id OrderSP ID.
         */
        do_action('sp_cog_order_stats_table_delete_order_stats', $order_id);

        ReportsCache::invalidate();
    }

    /**
     * Clean products data when an order is deleted.
     *
     * @param int $order_id OrderSP ID.
     *
     * @return void
     *
     * @since 2.0.0
     *
     */
    public function sync_on_order_delete($order_id)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'sp_cog_order_product_lookup';

        $wpdb->query($wpdb->prepare("DELETE FROM ${table_name} WHERE order_id = %d", $order_id));

        /**
         * Fires when product's reports are removed from database.
         *
         * @param int $product_id Product ID.
         * @param int $order_id   OrderSP ID.
         */
        do_action('qa_cog_lookup_table_delete_product', 0, $order_id);

        ReportsCache::invalidate();
    }
}
