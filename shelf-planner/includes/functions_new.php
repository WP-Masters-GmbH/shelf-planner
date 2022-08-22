<?php

/**
 * @param false $as_stat
 *
 * @return int[]|string[]
 */
function sp_get_order_statuses( $as_stat = false ) {
	$order_statuses = array(
		'On Order'  => 0,
		'On Hold'   => 0,
		'Completed' => 0,
		'Cancelled' => 0,
		'Failed'    => 0,
	);

	if ( $as_stat ) {
		return $order_statuses;
	}

	return array_keys( $order_statuses );
}

/**
 * @param $tables_list
 * @param $uid
 */
function sp_map_tables( $tables_list, $uid ) {
	global $wpdb;

	foreach ( $tables_list as $table ) {
		$wpdb->$table = $wpdb->prefix . $uid . '_' . $table;
	}
}

/**
 * Get Woo categories list
 *
 * @return array
 */
function sp_get_categories() {
	$args           = array(
		'taxonomy'     => 'product_cat',
		'orderby'      => 'name',
		'show_count'   => 0,
		'pad_counts'   => 0,
		'hierarchical' => 1,
		'title_li'     => '',
		'hide_empty'   => 0,
	);
	$all_categories = get_categories( $args );

	$result = array();
	foreach ( $all_categories as $category ) {
		$result[ $category->cat_ID ] = $category->name;
	}

	return $result;
}

/**
 * Main function to get products stats
 *
 * @param $categories
 * @param array $where_extra
 *
 * @return array
 */
function sp_get_products_data( $categories, $where_extra = array() ) {
	global $wpdb;

	// TODO: remove $categories

	if ( ! empty( $where_extra ) ) {
		foreach ( $where_extra as $each_field => &$each_data ) {
			if ( is_array( $each_data ) ) {
				foreach ( $each_data as &$each_value ) {
					if ( ! is_numeric( $each_value ) ) {
						$each_value = "'" . esc_sql( $each_value ) . "'";
					}
				}
				$each_data = ' IN (' . implode( ',', $each_data ) . ')';
			} else if ( ! is_numeric( $each_value ) ) {
				$each_data = "'" . esc_sql( $each_data ) . "'";
				$each_data = '=' . $each_data;
			}
			$each_data = $each_field . $each_data;
		}
		$where_extra = ' AND ' . implode( ' AND ', $where_extra );
	} else {
		$where_extra = '';
	}

	$is_min_stock_based = ( 'min_stock' == get_option( 'sp.settings.po_stock_type', 'ideal_stock' ) );
	$stock_sub_query    = $is_min_stock_based ? 'ROUND(IFNULL(pma.`meta_value`, 2), 0) AS ideal_stock_weeks' : '(ps.`sp_weeks_of_stock` + ps.`sp_lead_time`) AS ideal_stock_weeks';

	$sp_cost = get_option( 'sp.settings.force_zero_price_products', true ) ? '' : 'AND ps.`sp_cost` > 0';

	$sql = "
    SELECT
		p.`ID` AS term_id,
		p.`post_title` AS name,

		IFNULL( FLOOR(pms.`meta_value` / ROUND(0.01, 2) / 7), 0 ) AS weeks_to_stock_out,
		IFNULL( ROUND(pms.`meta_value`, 0), 0 ) AS current_stock,
		IFNULL( SUM(pop.`qty`), 0 ) AS inbound_stock,
        {$stock_sub_query},
		
        IFNULL(
			IF(0 - pms.`meta_value` - SUM(pop.`qty`) < 0, 0, 0 - pms.`meta_value` - SUM(pop.`qty`)),
			0
		) AS order_proposal_units,

		ps.`sp_cost` AS cost_price,
		ps.`sp_cost` AS order_value_cost,     
		pmp.`meta_value` AS order_value_retail,

		ROUND( pmp.`meta_value`, 2 ) AS order_value_price,
		ROUND( AVG(pmp.`meta_value`), 2 ) AS avg_price,
		
        ps.`sp_primary_category`,
           
        ps.`sp_lead_time` AS product_lead_time,
		sup.`lead_times` AS supplier_lead_time,           
		
        sup.`supplier_name`,
        sup.`address` AS supplier_address,
        sup.`id` AS supplier_id,
        sup.`payment_terms`,
        sup.`delivery_terms`,
        sup.`supplier_code` AS vendor_no,
        sup.`tax_vat_number` AS vendor_vat,
        sup.`account_no`,
        sup.`account_id`,
        sup.`assigned_to`,
        count(p_child.ID) as child_count

	FROM `{$wpdb->prefix}posts` p
	
	LEFT JOIN `{$wpdb->prefix}posts` p_child on p_child.post_parent = p.ID  and p_child.`post_type` IN ('product', 'product_variation')

	#LEFT JOIN `{$wpdb->prefix}terms` c ON tr.`object_id` = p.`ID`
	#LEFT JOIN `{$wpdb->prefix}term_relationships` tr ON c.`term_id` = tr.`term_taxonomy_id`

	LEFT JOIN `{$wpdb->prefix}postmeta` pms ON pms.`post_id` = p.`ID` 
		AND pms.`meta_key` = '_stock'
	LEFT JOIN `{$wpdb->prefix}postmeta` pmp ON pmp.`post_id` = p.`ID`
		AND pmp.`meta_key` = '_price'
	LEFT JOIN `{$wpdb->prefix}postmeta` pma ON pma.`post_id` = p.`ID`
		AND pma.`meta_key` = '_low_stock_amount' AND pms.`meta_value` != ''

	LEFT JOIN `{$wpdb->product_settings}` ps ON ps.`product_id` = p.`ID`
	LEFT JOIN `{$wpdb->suppliers}` sup ON sup.`id` = ps.`sp_supplier_id`

	LEFT JOIN (select product_id, qty from `{$wpdb->purchase_orders_products}` pop
	    JOIN `{$wpdb->purchase_orders}` po ON po.`id` = pop.`order_id`
		AND po.`status` NOT IN ('Completed', 'Cancelled', 'Failed', 'On Hold')) pop ON pop.`product_id` = p.`ID`

	WHERE p.`post_type` IN ('product', 'product_variation')
		{$sp_cost}
			
	{$where_extra}
	
    GROUP BY 1,2
	HAVING child_count = 0
	ORDER BY 2
";

	$products_data   = $wpdb->get_results( $sql, ARRAY_A );

	/*echo '<pre>';
	print_r($products_data);
	echo '</pre>';
	die;*/

	$products_result = array();

	$last_forecast = get_option( 'sp.last_forecast' );
	$forecast_data   = QAMain_Core::parse_forecast_json( wp_unslash($last_forecast) );

	foreach ( $products_data as &$products_item ) {

		if ( $is_min_stock_based ) {
			$products_item['ideal_stock'] = $products_item['ideal_stock_weeks'];
		} else {

			$weeks_forecast = array_fill( 0, 24, 0 );
			if ( isset( $forecast_data[ $products_item['term_id'] ] ) ) {
				$weeks_forecast = $forecast_data[ $products_item['term_id'] ]['WeeklySlesArray'];
			}

			$weeks_of_stock = get_option( 'sp.settings.default_weeks_of_stock', 6 );
			$lead_time      = get_option( 'sp.settings.default_lead_time', 1 );

			if ( isset( $products_item['weeks_of_stock_supplier'] ) && is_numeric( $products_item['weeks_of_stock_supplier'] ) && $products_item['weeks_of_stock_supplier'] > 0 ) {
				$weeks_of_stock = $products_item['weeks_of_stock_supplier'];
			}
			if ( isset( $products_item['lead_time_supplier'] ) && is_numeric( $products_item['lead_time_supplier'] ) && $products_item['lead_time_supplier'] > 0 ) {
				$lead_time = $products_item['lead_time_supplier'];
			}

			if ( isset( $products_item['ideal_stock_weeks'] ) && is_numeric( $products_item['ideal_stock_weeks'] ) && $products_item['ideal_stock_weeks'] > 0 ) {
				$weeks_of_stock = $products_item['ideal_stock_weeks'];
			}
			if ( isset( $products_item['lead_time_product'] ) && is_numeric( $products_item['lead_time_product'] ) && $products_item['lead_time_product'] > 0 ) {
				$lead_time = $products_item['lead_time_product'];
			}

			$weeks_count                  = $weeks_of_stock + $lead_time;
			$weeks_forecast_sum           = array_sum( array_slice( $weeks_forecast, 0, (int) $weeks_count ) );
			$products_item['ideal_stock'] = ceil( $weeks_forecast_sum );
		}

		$products_item['order_proposal_units'] = max( 0, $products_item['ideal_stock'] - $products_item['current_stock'] - $products_item['inbound_stock'] );

		$weeks_to_stock_out = '-';
		$tmp_stock          = $products_item['current_stock'];
		foreach ( $weeks_forecast as $k => $v ) {
			$tmp_stock -= $v;
			if ( $tmp_stock <= 0 ) {
				$weeks_to_stock_out = $k;
				break;
			}
		}
		$products_item['weeks_to_stock_out'] = $weeks_to_stock_out;

		$products_item['name']                   = htmlspecialchars_decode( $products_item['name'] );
		$products_item['cat_url']                = get_permalink( (int) $products_item['term_id'] );
		$products_item['cat_url_purchase_order'] = 'admin.php?page=shelf_planner_purchase_orders' . '&product_id=' . $products_item['term_id'] . '&proposal_units=' . $products_item['order_proposal_units'];

		$products_item['sales_l4w'] = 0;
		$products_item['sales_n4w'] = 0.0;

		$products_result[ $products_item['term_id'] ] = $products_item;
	}

//	$sql = "
//	SELECT
//		if(im.meta_key = '_product_id', im.`meta_value`, null) AS product_id,
//		if(im.meta_key = '_variation_id', im.`meta_value`, null) AS variation_id,
//		SUM(im2.meta_value) AS sales_l4w
//
//	FROM `{$wpdb->prefix}woocommerce_order_items` items
//
//	LEFT JOIN `{$wpdb->prefix}woocommerce_order_itemmeta` im ON items.`order_item_id` = im.`order_item_id`
//	LEFT JOIN `{$wpdb->prefix}woocommerce_order_itemmeta` im2 ON im2.`order_item_id` = im.`order_item_id` and im2.meta_key = '_qty'
//	LEFT JOIN `{$wpdb->prefix}wc_order_stats` os ON items.`order_id` = os.`order_id`
//
//	WHERE items.`order_item_type` = 'line_item'
//		AND im.`meta_key` IN ( '_product_id', '_variation_id' )
//		AND os.`status` = 'wc-completed'
//	    AND os.`date_created` >= DATE_SUB( NOW(), INTERVAL 4 WEEK )
//	    group by 1,2";

	$sql = "
		select 	if(post_type != 'product_variation', p.ID, null) as product_id, 
		if(post_type = 'product_variation', p.ID, null) as variation_id, 
        sk.meta_value as product_sku,
        p.post_title as product_title,
        round(ifnull(s.meta_value, 0), 0) as product_stock_amount,
        st.meta_value as product_stock_status,
        ifnull(period_sales_report.total_qty_sold, 0) as sales_l4w
from {$wpdb->prefix}posts p 
left join {$wpdb->prefix}postmeta s on s.post_id = p.ID and s.meta_key = '_stock'
left join {$wpdb->prefix}postmeta st on st.post_id = p.ID and st.meta_key = '_stock_status'
left join {$wpdb->prefix}postmeta sk on sk.post_id = p.ID and sk.meta_key = '_sku'
left join (select distinct post_parent as id from {$wpdb->prefix}posts where post_type like 'product%') pt on pt.id = p.ID
# Get Sales
left join (select omp.meta_value as product_id, omv.meta_value as variation_id, sum(omq.meta_value) as total_qty_sold from {$wpdb->prefix}posts o 
	join {$wpdb->prefix}woocommerce_order_items oi on oi.order_item_type = 'line_item' and oi.order_id = o.ID
	join {$wpdb->prefix}woocommerce_order_itemmeta omv on omv.order_item_id = oi.order_item_id and omv.meta_key = '_variation_id'
	join {$wpdb->prefix}woocommerce_order_itemmeta omp on omp.order_item_id = oi.order_item_id and omp.meta_key = '_product_id'
	join {$wpdb->prefix}woocommerce_order_itemmeta omq on omq.order_item_id = oi.order_item_id and omq.meta_key = '_qty'
		where 
			o.post_type = 'shop_order'
            # Interval is managed here
			and o.post_date >= DATE(NOW()) - INTERVAL 4 WEEK
			and o.post_status in (
				# You can decide which order types to include to sold quantity amount
				#'wc-pending', 
                'wc-processing', 
                #'wc-on-hold', 
                'wc-completed', 
                #'wc-cancelled',
                #'wc-refunded',
                #'wc-failed', 
                '-'
            )
	group by 1,2) period_sales_report on (period_sales_report.product_id = p.ID or period_sales_report.variation_id = p.ID)
where post_type like 'product%' and pt.id is null
order by total_qty_sold desc;
	";

	$orders_data = $wpdb->get_results( $sql, ARRAY_A );

	foreach ( $orders_data as $tmp_oi_key => $order_record ) {
		if ( $order_record['variation_id'] ) {
			$orders_data[$tmp_oi_key]['product_id'] = $order_record['variation_id'];
		}
	}

	foreach ( $orders_data as $order_record ) {

		if ( ! isset( $products_result[ $order_record['product_id'] ] ) ) {
			continue;
		}
		$weeks_forecast = ! isset( $forecast_data[ $order_record['product_id'] ] ) ? array( 0 ) : array_values( $forecast_data[ $order_record['product_id'] ]['WeeklySlesArray'] );
		if ( count( $weeks_forecast ) == 0 ) {
			$weeks_forecast[] = 0;
		}

		$products_result[ $order_record['product_id'] ]['sales_l4w'] = $order_record['sales_l4w'];
	}

	foreach ( $forecast_data as $p_id => $val ) {
		if ( ! isset( $products_result[ $p_id ] ) ) {
			continue;
		}
		$products_result[ $p_id ]['sales_n4w'] = ceil( array_sum( array_slice( $val['WeeklySlesArray'], 0, 4 ) ) );
	}

	if ( isset( $_GET['debug'] ) ) {
		echo esc_html( json_encode([$products_data, $forecast_data,$orders_data,$products_result]) );
		die;
	}

	return $products_result;
}

/**
 * Starts session
 */
function sp_session_start() {
	$is_session_started = false;
	if ( php_sapi_name() !== 'cli' ) {
		$is_session_started = version_compare( phpversion(), '5.4.0', '>=' ) ? session_status() === PHP_SESSION_ACTIVE : session_id() !== '';
	}
	if ( ! $is_session_started ) {
		session_start();
	}
}

/**
 * @return string
 */
function sp_get_next_rn() {
	return str_pad( strval( intval( get_option( 'sp.last_reference_number', 0 ) ) + 1 ), 8, '0', STR_PAD_LEFT );
}

/**
 * @param int $new_number
 *
 * @return string
 */
function sp_get_next_po( $new_number = 0 ) {
	global $wpdb;

	$sql         = "SELECT IFNULL(MAX(`order_number`), 0) FROM {$wpdb->purchase_orders}";
	$next_number = max( $new_number, intval( get_option( 'sp.settings.po_next_number', 0 ) ) + 1, intval( $wpdb->get_var( $sql ) ) + 1 );

	return str_pad( strval( $next_number ), 8, '0', STR_PAD_LEFT );
}

/**
 * DELETE NEEDED OPTIONS FOR DEVELOPER'S PURPOSE
 */
$options_to_delete = array(
	'sp.wizard_in_progress' => 0,

	'sp.settings.business_model'  => 0,
	'sp.settings.assortment_size' => 0,
	'sp.settings.industry'        => 0,

	'sp.settings.default_weeks_of_stock' => 0,
	'sp.settings.default_lead_time'      => 0,

	'sp.settings.po_stock_type'      => 0,
	'sp.settings.po_company_name'    => 0,
	'sp.settings.po_company_logo'    => 0,
	'sp.settings.po_company_address' => 0,

	'sp.settings.po_postal_code' => 0,
	'sp.settings.po_city'        => 0,
	'sp.settings.po_country'     => 0,

	'sp.settings.po_email'       => 0,
	'sp.settings.po_website'     => 0,
	'sp.settings.po_phone'       => 0,
	'sp.settings.po_description' => 0,

	'sp.settings.po_bank'           => 0,
	'sp.settings.po_iban'           => 0,
	'sp.settings.po_swift_code'     => 0,
	'sp.settings.po_vat_number'     => 0,
	'sp.settings.po_account_number' => 0,
	'sp.settings.po_branch'         => 0,

	'sp.settings.po_auto-generate_orders' => 0,
	'sp.settings.po_prefix'               => 0,

	'sp.settings.db_version' => 0,

	'sp.last_reference_number' => 0,
	'sp.last_po_number'        => 0,

	'sp.in_background'         => 0,
	'sp.last_forecast_success' => 0,
	'sp.log'                   => 0,
);

foreach ( $options_to_delete as $each_option => $is_delete ) {
	if ( $is_delete ) {
		delete_option( $each_option );
	}
}

/**
 * @param $product_id
 *
 * @return string
 */
function sp_get_cost_price( $product_id ) {
	global $wpdb;
	$sql = "SELECT `sp_cost` FROM {$wpdb->product_settings} WHERE `product_id` = " . intval( $product_id );

	return (string) floatval( $wpdb->get_var( $sql ) );
}

/**
 * @return string
 */
function sp_get_industry_id() {
	$industry = get_option( 'sp.settings.industry' );
	$industry = explode( ',', $industry );
	$industry = empty( $industry ) ? 0 : reset( $industry );

	return (string) $industry;
}