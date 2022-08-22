<?php

global $wpdb;

/**
 * Plugin tables
 */
$sp_db_tables = array(
	'api_log',
	'purchase_orders',
	'purchase_orders_products',
	'suppliers',
	'product_settings',
	'warehouses'
);

/**
 * Prefix
 */
$db_prefix = 'sp';

/**
 * Make mapping
 */
sp_map_tables( $sp_db_tables, $db_prefix );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->api_log}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `message` text NOT NULL,
    `type` varchar(20) NOT NULL,
    `date_added` datetime NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
" );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->purchase_orders}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `supplier_id` int(11) NOT NULL,
    `warehouse_id` int(11) NOT NULL,
    `deliver_to` varchar(255) NOT NULL,
    `order_prefix` varchar(255) NOT NULL,
    `order_number` varchar(32) UNIQUE NOT NULL,
    `reference_number` varchar(255) NOT NULL,
    `order_date` date NOT NULL,
    `expected_delivery_date` date DEFAULT NULL,
    `shipping_address` varchar(255) DEFAULT NULL,
    `status` varchar(32) NOT NULL DEFAULT 'On Order',
    `description` text,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
" );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->purchase_orders_products}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `order_id` int(11) NOT NULL,
    `product_id` int(11) NOT NULL,
    `qty` int(11) NOT NULL DEFAULT '1',
    `price` decimal(10,2) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
" );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->suppliers}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `supplier_name` varchar(255) DEFAULT NULL,
    `supplier_code` varchar(45) DEFAULT NULL,
    `tax_vat_number` varchar(45) DEFAULT NULL,
    `phone_number` varchar(45) DEFAULT NULL,
    `website` varchar(255) DEFAULT NULL,
    `email_for_ordering` varchar(255) DEFAULT NULL,
    `general_email_address` varchar(255) DEFAULT NULL,
    `description` text,
    `currency` char(3) DEFAULT NULL,
    `address` varchar(255) DEFAULT NULL,
    `city` varchar(45) DEFAULT NULL,
    `country` varchar(45) DEFAULT NULL,
    `state` varchar(45) DEFAULT NULL,
    `zip_code` varchar(45) DEFAULT NULL,
    `assigned_to` varchar(45) DEFAULT NULL,
    `ship_to_location` varchar(45) DEFAULT NULL,
    `discount` float DEFAULT NULL,
    `tax_rate` float DEFAULT NULL,
    `lead_times` int(11) DEFAULT NULL,
    `dt_added` timestamp NULL DEFAULT NULL,
    `weeks_of_stock` int(11) DEFAULT NULL,
    `payment_terms` varchar(255) DEFAULT NULL,
    `delivery_terms` varchar(255) DEFAULT NULL,
    `account_no` varchar(255) DEFAULT NULL,
    `account_id` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uid_supplier_code` (`supplier_code`),
    UNIQUE KEY `uid_supplier_name` (`supplier_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
" );

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->product_settings}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `product_id` int(11) NOT NULL,
    `sp_supplier_id` int(11) DEFAULT NULL,
    `sp_activate_replenishment` tinyint(1) unsigned DEFAULT NULL,
    `sp_weeks_of_stock` tinyint(3) unsigned DEFAULT NULL,
    `sp_lead_time` mediumint(5) unsigned DEFAULT NULL,
    `sp_product_launch_date` date DEFAULT NULL,
    `sp_product_replenishment_date` date DEFAULT NULL,
    `sp_inbound_stock_limit` mediumint(5) unsigned DEFAULT NULL,
    `sp_on_hold` tinyint(1) unsigned DEFAULT NULL,
    `sp_primary_category` bigint(20) unsigned NOT NULL,
    `sp_size_packs` tinyint(1) unsigned DEFAULT NULL,
    `sp_size_pack_threshold` mediumint(5) unsigned DEFAULT NULL,
    `sp_sku_pack_size` mediumint(5) unsigned DEFAULT NULL,
    `sp_supplier_product_id` int(11) DEFAULT NULL,
    `sp_supplier_product_reference` int(11) DEFAULT NULL,
    `sp_cost` decimal(10,2) unsigned DEFAULT NULL,
    `sp_stock_value` mediumint(5) unsigned DEFAULT NULL,
    `sp_mark_up` mediumint(5) unsigned DEFAULT NULL,
    `sp_margin` mediumint(5) unsigned DEFAULT NULL,
    `sp_margin_tax` mediumint(5) unsigned DEFAULT NULL,
    `dt_updated` timestamp ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uid_product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
" );

/**
 * Migrations
 */
$sp_db_version        = get_option( 'sp.settings.db_version', 0 );
$sp_migration_applied = false;
if ( $sp_db_version < 10 ) {
	$wpdb->query( "
ALTER TABLE `{$wpdb->product_settings}` ADD `sp_supplier_product_id` INT(11) NULL
    AFTER `sp_sku_pack_size`
" );
	$wpdb->query( "
ALTER TABLE `{$wpdb->product_settings}` ADD `sp_supplier_product_reference` VARCHAR(255) NULL
    AFTER `sp_supplier_product_id`
" );

	$sp_db_version        = 10;
	$sp_migration_applied = true;
}

if ( $sp_migration_applied ) {
	update_option( 'sp.settings.db_version', $sp_db_version );
}

$wpdb->query( "
CREATE TABLE IF NOT EXISTS `{$wpdb->warehouses}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `warehouse_name` varchar(255) NOT NULL,
    `warehouse_address` varchar(255) DEFAULT NULL,
    `warehouse_postal_code` varchar(255) DEFAULT NULL,
    `warehouse_city` varchar(255) DEFAULT NULL,
    `warehouse_country` varchar(255) DEFAULT NULL,
    `warehouse_phone` varchar(255) DEFAULT NULL,
    `warehouse_website` varchar(255) DEFAULT NULL,
    `warehouse_email` varchar(255) DEFAULT NULL,
    `warehouse_use_same` int(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
" );

if (
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

$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_cog_order_stats (
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
		) $collate;");

$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_cog_order_product_lookup (
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
		) $collate;");

$wpdb->query( "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sp_cog_category_lookup (
			category_tree_id BIGINT UNSIGNED NOT NULL,
			category_id BIGINT UNSIGNED NOT NULL,
			PRIMARY KEY (category_tree_id,category_id)
		) $collate;");
		
		
//require_once ABSPATH . 'wp-admin/includes/upgrade.php';

//dbDelta($tables);

/*
// SP Tables
$sp_cog_order_stats = $wpdb->prefix.'sp_cog_order_stats';
$sp_cog_order_product_lookup = $wpdb->prefix.'sp_cog_order_product_lookup';
$sp_cog_category_lookup = $wpdb->prefix.'sp_cog_category_lookup';

// QA Tables
$qa_cog_order_stats = $wpdb->prefix.'qa_cog_order_stats';
$qa_cog_order_product_lookup = $wpdb->prefix.'qa_cog_order_product_lookup';
$qa_cog_category_lookup = $wpdb->prefix.'qa_cog_category_lookup';

// Count Rows
$count_sp_cog_order_stats = $wpdb->get_var("SELECT COUNT(*) FROM {$sp_cog_order_stats}");
$count_sp_cog_order_product_lookup = $wpdb->get_var("SELECT COUNT(*) FROM {$sp_cog_order_product_lookup}");
$count_sp_cog_category_lookup = $wpdb->get_var("SELECT COUNT(*) FROM {$sp_cog_category_lookup}");

$count_qa_cog_order_stats = $wpdb->get_var("SELECT COUNT(*) FROM {$qa_cog_order_stats}");
$count_qa_cog_order_product_lookup = $wpdb->get_var("SELECT COUNT(*) FROM {$qa_cog_order_product_lookup}");
$count_qa_cog_category_lookup = $wpdb->get_var("SELECT COUNT(*) FROM {$qa_cog_category_lookup}");
*/

// Copy From QA to SP tables
/*if($count_sp_cog_order_stats + 15 < $count_qa_cog_order_stats) {
    $wpdb->query("TRUNCATE TABLE {$sp_cog_order_stats}");
    $wpdb->query("INSERT INTO {$sp_cog_order_stats} SELECT * FROM {$qa_cog_order_stats}");
}
if($count_sp_cog_order_product_lookup + 15 < $count_qa_cog_order_product_lookup) {
    $wpdb->query("TRUNCATE TABLE {$sp_cog_order_product_lookup}");
    $wpdb->query("INSERT INTO {$sp_cog_order_product_lookup} SELECT * FROM {$qa_cog_order_product_lookup}");
}
if($count_sp_cog_category_lookup + 15 < $count_qa_cog_category_lookup) {
    $wpdb->query("TRUNCATE TABLE {$sp_cog_category_lookup}");
    $wpdb->query("INSERT INTO {$sp_cog_category_lookup} SELECT * FROM {$qa_cog_category_lookup}");
}*/