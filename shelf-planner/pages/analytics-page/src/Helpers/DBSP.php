<?php // -*- coding: utf-8 -*-

/**
 * DBSP tables related functions and actions.
 *
 * @package QuickAssortmentsSP\COG\Helpers
 */

namespace QuickAssortmentsSP\COG\Helpers;

defined('ABSPATH') || exit;

/**
 * Class DBSP.
 *
 * @package QuickAssortmentsSP\COG\Helpers
 */
final class DBSP
{
    /**
     * Create tables in DBSP.
     *
     * @return void
     *
     * @since 2.0.0
     */
    public static function create_db_tables()
    {
        global $wpdb;

        /*if (
            in_array($wpdb->prefix . 'sp_cog_order_stats', $wpdb->tables, true)
            && in_array($wpdb->prefix . 'sp_cog_order_product_lookup', $wpdb->tables, true)
        ) {
            return;
        }

        if ($wpdb->has_cap('collation')) {
            $collate = $wpdb->get_charset_collate();
        }

        // Max DBSP index length. See wp_get_db_schema().
        $max_index_length = 191;

        $tables = "
		CREATE TABLE {$wpdb->prefix}sp_cog_order_stats (
			order_id bigint(20) unsigned NOT NULL,
			parent_id bigint(20) unsigned DEFAULT 0 NOT NULL,
			date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			date_created_gmt datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			num_items_sold int(11) DEFAULT 0 NOT NULL,
			gross_total double DEFAULT 0 NOT NULL,
			tax_total double DEFAULT 0 NOT NULL,
			shipping_total double DEFAULT 0 NOT NULL,
			net_total double DEFAULT 0 NOT NULL,
			status varchar(200) NOT NULL,
			PRIMARY KEY (order_id),
			KEY date_created (date_created),
			KEY status (status({$max_index_length}))
		) $collate;
		CREATE TABLE {$wpdb->prefix}sp_cog_order_product_lookup (
			order_item_id BIGINT UNSIGNED NOT NULL,
			order_id BIGINT UNSIGNED NOT NULL,
			product_id BIGINT UNSIGNED NOT NULL,
			variation_id BIGINT UNSIGNED NOT NULL,
			date_created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			product_qty INT NOT NULL,
			product_net_revenue double DEFAULT 0 NOT NULL,
			product_gross_revenue double DEFAULT 0 NOT NULL,
			coupon_amount double DEFAULT 0 NOT NULL,
			tax_amount double DEFAULT 0 NOT NULL,
			shipping_amount double DEFAULT 0 NOT NULL,
			shipping_tax_amount double DEFAULT 0 NOT NULL,
			product_cost double DEFAULT 0 NOT NULL,
			product_net_profit double DEFAULT 0 NOT NULL,
			product_margin_ex_tax double DEFAULT 0 NOT NULL,
			product_margin_in_tax double DEFAULT 0 NOT NULL,
			PRIMARY KEY  (order_item_id),
			KEY order_id (order_id),
			KEY product_id (product_id),
			KEY date_created (date_created)
		) $collate;
		CREATE TABLE {$wpdb->prefix}sp_cog_category_lookup (
			category_tree_id BIGINT UNSIGNED NOT NULL,
			category_id BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY (category_tree_id,category_id)
		) $collate;
		";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        dbDelta($tables);*/
    }
}
