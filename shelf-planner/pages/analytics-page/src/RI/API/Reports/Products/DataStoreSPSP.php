<?php // -*- coding: utf-8 -*-
/**
 * API\Reports\Products\DataStoreSPSP class file.
 *
 *
 */

namespace QuickAssortmentsSP\COG\RI\API\Reports\Products;

defined('ABSPATH') || exit;

use QuickAssortmentsSP\COG\RI\API\Reports\DataStoreSP as ReportsDataStore;
use QuickAssortmentsSP\COG\RI\API\Reports\DataStoreInterfaceSP;
use QuickAssortmentsSP\COG\RI\TimeIntervalSP;

/**
 * API\Reports\Products\DataStoreSPSP.
 */
class DataStoreSPSP extends ReportsDataStore implements DataStoreInterfaceSP
{
    /**
     * Table used to get the data.
     *
     * @var string
     */
    const TABLE_NAME = 'sp_cog_order_product_lookup';

    /**
     * CacheSP identifier.
     *
     * @var string
     */
    protected $cache_key = 'products';

    /**
     * Mapping columns to data type to return correct response types.
     *
     * @var array
     */
    protected $column_types = [
        'date_start'       => 'strval',
        'date_end'         => 'strval',
        'product_id'       => 'intval',
        'gross_revenue'    => 'floatval',
        'net_revenue'      => 'floatval',
        'net_profit'       => 'floatval',
        'cost_of_goods_sold'       => 'floatval',
        // Extended attributes.
        'name'             => 'strval',
        'price'            => 'floatval',
        'image'            => 'strval',
        'permalink'        => 'strval',
        'stock_status'     => 'strval',
        'stock_quantity'   => 'intval',
        'low_stock_amount' => 'intval',
        'category_ids'     => 'array_values',
        'variations'       => 'array_values',
        'sku'              => 'strval',
    ];

    /**
     * SQL columns to select in the db query and their mapping to SQL code.
     *
     * @var array
     */
    protected $report_columns = [
        'product_id'    => 'product_id',
        'gross_revenue' => 'SUM(product_gross_revenue) as gross_revenue',
        'net_revenue'   => 'SUM(product_net_revenue) AS net_revenue',
        'net_profit'    => 'SUM(product_net_profit) AS net_profit',
        'cost_of_goods_sold' => 'SUM(product_net_profit) AS cost_of_goods_sold',
        'net_margin'    => 'SUM(product_margin_ex_tax)/SUM(product_qty) AS net_margin',
        'gross_margin'  => 'SUM(product_margin_in_tax)/SUM(product_qty) AS gross_margin',
        'items_sold'    => 'SUM(product_qty) as items_sold',
    ];

    /**
     * Extended product attributes to include in the data.
     *
     * @var array
     */
    protected $extended_attributes = [
        'name',
        'price',
        'image',
        'permalink',
        'stock_status',
        'stock_quantity',
        'manage_stock',
        'low_stock_amount',
        'category_ids',
        'variations',
        'sku',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::TABLE_NAME;
        // Avoid ambigious column order_id in SQL query.
        $this->report_columns['net_profit'] = str_replace('product_net_profit', $table_name . '.product_net_profit', $this->report_columns['net_profit']);
    }

    /**
     * Returns the report data based on parameters supplied by the user.
     *
     * @param array $query_args QuerySP parameters.
     *
     * @return stdClass|WP_Error Data.
     */
    public function get_data($query_args)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . self::TABLE_NAME;

        // These defaults are only partially applied when used via REST API, as that has its own defaults.
        $defaults   = [
            'per_page'         => get_option('posts_per_page'),
            'page'             => 1,
            'order'            => 'DESC',
            'orderby'          => 'date',
            'before'           => TimeIntervalSP::default_before(),
            'after'            => TimeIntervalSP::default_after(),
            'fields'           => '*',
            'categories'       => [],
            'product_includes' => [],
            'extended_info'    => false,
        ];
        $query_args = wp_parse_args($query_args, $defaults);
        $this->normalize_timezones($query_args, $defaults);

        /*
         * We need to get the cache key here because
         * parent::update_intervals_sql_params() modifies $query_args.
         */
        $cache_key = $this->get_cache_key($query_args);
        $data      = $this->get_cached_data($cache_key);
        $data      = false;
        if (false === $data) {
            $data = (object) [
                'data'    => [],
                'total'   => 0,
                'pages'   => 0,
                'page_no' => 0,
            ];

            $selections        = $this->selected_columns($query_args);
            $sql_query_params  = $this->get_sql_query_params($query_args);
            $included_products = $this->get_included_products_array($query_args);

            if (count($included_products) > 0) {
                $total_results = count($included_products);
                $total_pages   = (int) ceil($total_results / $sql_query_params['per_page']);

                if ('date' === $query_args['orderby']) {
                    $selections .= ", {$table_name}.date_created";
                }

                $fields          = $this->get_fields($query_args);
                $join_selections = $this->format_join_selections($fields, ['product_id']);
                $ids_table       = $this->get_ids_table($included_products, 'product_id');
                $prefix          = "SELECT {$join_selections} FROM (";
                $suffix          = ") AS {$table_name}";
                $right_join      = "RIGHT JOIN ( {$ids_table} ) AS default_results
					ON default_results.product_id = {$table_name}.product_id";
            } else {
                $db_records_count = (int) $wpdb->get_var(
                    "SELECT COUNT(*) FROM (
								SELECT
									product_id
								FROM
									{$table_name}
									{$sql_query_params['from_clause']}
								WHERE
									1=1
									{$sql_query_params['where_time_clause']}
									{$sql_query_params['where_clause']}
								GROUP BY
									product_id
									) AS tt"
                ); // WPCS: cache ok, DBSP call ok, unprepared SQL ok.

                $total_results = $db_records_count;
                $total_pages   = (int) ceil($db_records_count / $sql_query_params['per_page']);

                if (($query_args['page'] < 1 || $query_args['page'] > $total_pages)) {
                    return $data;
                }

                $prefix     = '';
                $suffix     = '';
                $right_join = '';
            }

            $product_data = $wpdb->get_results(
                "${prefix}
					SELECT
						{$selections}
					FROM
						{$table_name}
						{$sql_query_params['from_clause']}
					WHERE
						1=1
						{$sql_query_params['where_time_clause']}
						{$sql_query_params['where_clause']}
					GROUP BY
						product_id
				{$suffix}
					{$right_join}
					{$sql_query_params['outer_from_clause']}
					ORDER BY
						{$sql_query_params['order_by_clause']}
					{$sql_query_params['limit']}
					",
                ARRAY_A
            ); // WPCS: cache ok, DBSP call ok, unprepared SQL ok.

            if (null === $product_data) {
                return $data;
            }

            $this->include_extended_info($product_data, $query_args);

            $product_data = array_map([$this, 'cast_numbers'], $product_data);
            $data         = (object) [
                'data'    => $product_data,
                'total'   => $total_results,
                'pages'   => $total_pages,
                'page_no' => (int) $query_args['page'],
            ];

            $this->set_cached_data($cache_key, $data);
        }

        return $data;
    }

    /**
     * Updates the database query with parameters used for Products report: categories and order status.
     *
     * @param array $query_args QuerySP arguments supplied by the user.
     *
     * @return array Array of parameters used for SQL query.
     */
    protected function get_sql_query_params($query_args)
    {
        global $wpdb;
        $order_product_lookup_table = $wpdb->prefix . self::TABLE_NAME;

        $sql_query_params = $this->get_time_period_sql_params($query_args, $order_product_lookup_table);
        $sql_query_params = array_merge($sql_query_params, $this->get_limit_sql_params($query_args));
        $sql_query_params = array_merge($sql_query_params, $this->get_order_by_sql_params($query_args));

        $included_products = $this->get_included_products($query_args);
        if ($included_products) {
            $sql_query_params                 = array_merge($sql_query_params, $this->get_from_sql_params($query_args, 'outer_from_clause', 'default_results.product_id'));
            $sql_query_params['where_clause'] .= " AND {$order_product_lookup_table}.product_id IN ({$included_products})";
        } else {
            $sql_query_params = array_merge($sql_query_params, $this->get_from_sql_params($query_args, 'from_clause', "{$order_product_lookup_table}.product_id"));
        }

        $included_variations = $this->get_included_variations($query_args);
        if ($included_variations) {
            $sql_query_params['where_clause'] .= " AND {$order_product_lookup_table}.variation_id IN ({$included_variations})";
        }

        $order_status_filter = $this->get_status_subquery($query_args);
        if ($order_status_filter) {
            $sql_query_params['from_clause'] .= " JOIN {$wpdb->prefix}sp_cog_order_stats ON {$order_product_lookup_table}.order_id = {$wpdb->prefix}sp_cog_order_stats.order_id";
            $sql_query_params['where_clause'] .= " AND ( {$order_status_filter} )";
        }

        return $sql_query_params;
    }

    /**
     * Fills FROM clause of SQL request based on user supplied parameters.
     *
     * @param array  $query_args Parameters supplied by the user.
     * @param string $arg_name   Name of the FROM sql param.
     * @param string $id_cell    ID cell identifier, like `table_name.id_column_name`.
     *
     * @return array
     */
    protected function get_from_sql_params($query_args, $arg_name, $id_cell)
    {
        global $wpdb;
        $sql_query['outer_from_clause'] = '';

        // OrderSP by product name requires extra JOIN.
        if ('product_name' === $query_args['orderby']) {
            $sql_query[$arg_name] .= " JOIN {$wpdb->prefix}posts AS _products ON {$id_cell} = _products.ID";
        }
        if ('sku' === $query_args['orderby']) {
            $sql_query[$arg_name] .= " JOIN {$wpdb->prefix}postmeta AS postmeta ON {$id_cell} = postmeta.post_id AND postmeta.meta_key = '_sku'";
        }
        if ('variations' === $query_args['orderby']) {
            $sql_query[$arg_name] .= " LEFT JOIN ( SELECT post_parent, COUNT(*) AS variations FROM {$wpdb->prefix}posts WHERE post_type = 'product_variation' GROUP BY post_parent ) AS _variations ON {$id_cell} = _variations.post_parent";
        }

        return $sql_query;
    }

    /**
     * Enriches the product data with attributes specified by the extended_attributes.
     *
     * @param array $products_data Product data.
     * @param array $query_args    QuerySP parameters.
     */
    protected function include_extended_info(&$products_data, $query_args)
    {
        global $wpdb;
        $product_names = [];

        foreach ($products_data as $key => $product_data) {
            $extended_info = new \ArrayObject();
            if ($query_args['extended_info']) {
                $product_id = $product_data['product_id'];
                $product    = wc_get_product($product_id);
                // Product was deleted.
                if (! $product) {
                    if (! isset($product_names[$product_id])) {
                        $product_names[$product_id] = $wpdb->get_var(
                            $wpdb->prepare(
                                "SELECT i.order_item_name
								FROM {$wpdb->prefix}woocommerce_order_items i, {$wpdb->prefix}woocommerce_order_itemmeta m
								WHERE i.order_item_id = m.order_item_id
								AND m.meta_key = '_product_id'
								AND m.meta_value = %s
								ORDER BY i.order_item_id DESC
								LIMIT 1",
                                $product_id
                            )
                        );
                    }

                    /* translators: %s is product name */
                    $products_data[$key]['extended_info']['name'] = $product_names[$product_id] ? sprintf(__('%s (Deleted)', 'qa-cost-of-goods-margins'), $product_names[$product_id]) : __('(Deleted)', 'qa-cost-of-goods-margins');
                    continue;
                }

                $extended_attributes = apply_filters('woocommerce_rest_reports_products_extended_attributes', $this->extended_attributes, $product_data);
                foreach ($extended_attributes as $extended_attribute) {
                    if ('variations' === $extended_attribute) {
                        if (! $product->is_type('variable')) {
                            continue;
                        }
                        $function = 'get_children';
                    } else {
                        $function = 'get_' . $extended_attribute;
                    }
                    if (is_callable([$product, $function])) {
                        $value                                = $product->{$function}();
                        $extended_info[$extended_attribute]   = $value;
                    }
                }
                // If there is no set low_stock_amount, use the one in user settings.
                if ('' === $extended_info['low_stock_amount']) {
                    $extended_info['low_stock_amount'] = absint(max(get_option('woocommerce_notify_low_stock_amount'), 1));
                }
                $extended_info = $this->cast_numbers($extended_info);
            }
            $products_data[$key]['extended_info'] = $extended_info;
        }
    }

    /**
     * Maps ordering specified by the user to columns in the database/fields in the data.
     *
     * @param string $order_by Sorting criterion.
     *
     * @return string
     */
    protected function normalize_order_by($order_by)
    {
        global $wpdb;
        $order_product_lookup_table = $wpdb->prefix . self::TABLE_NAME;

        if ('date' === $order_by) {
            return $order_product_lookup_table . '.date_created';
        }
        if ('product_name' === $order_by) {
            return 'post_title';
        }
        if ('sku' === $order_by) {
            return 'meta_value';
        }

        return $order_by;
    }
}
