<?php

/**
 * Class QAMain_Core
 */
class QAMain_Core {

	protected static $category_mapping;

	/**
	 * @param $product_id
	 *
	 * @return mixed
	 */
	public static function get_product_primary_category_id( $product_id ) {
		$tmp = get_post( $product_id );
		if( is_object($tmp) && $tmp->post_parent ){
			$product_id = $tmp->post_parent;
		}

		// Return saved category if exists
		$tmp = self::get_product_settings( $product_id );
		if ( $tmp['sp_primary_category'] ) {
			return $tmp['sp_primary_category'];
		}

		// Otherwise, get it from WooCommerce
		$term_list   = wc_get_product_cat_ids( $product_id );
		$term_list[] = 0;

		return array_shift( $term_list );
	}

	/**
	 * Get settings by product or variation.
	 * Clone settings from parent product to variation if needed.
	 *
	 * @param $product_id
	 * @param bool $add_settings_if_has_parent
	 *
	 * @return array
	 */
	public static function get_product_settings( $product_id, $add_settings_if_has_parent = true ) {
		global $wpdb;

		$product_id = (int) $product_id;
		$sql        = "SELECT * FROM {$wpdb->product_settings} WHERE `product_id` = {$product_id}";

		$result    = $wpdb->get_row( $sql, ARRAY_A );
		$parent_id = (int) wp_get_post_parent_id( $product_id );

		if ( ! $result ) {
			if ( $parent_id ) {
				$sql    = "SELECT * FROM {$wpdb->product_settings} WHERE `product_id` = {$parent_id}";
				$result = $wpdb->get_row( $sql, ARRAY_A );
				if ( $result && $add_settings_if_has_parent ) {
					$tmp_row               = $result;
					$tmp_row['id']         = null;
					$tmp_row['product_id'] = $product_id;
					$wpdb->insert( $wpdb->product_settings, $tmp_row );
				}
			}
		}

		if ( $parent_id ) {
			$result['parent_id'] = $parent_id;
		}

		return $result;
	}

	/**
	 * @param $category_id
	 *
	 * @return int|mixed|string|null
	 */
	public static function get_industry_by_category( $category_id ) {
		if ( ! isset( self::$category_mapping ) ) {
			self::$category_mapping = @json_decode( get_option( 'sp.category.mapping', '{}' ), true );
		}

		$industry_list = self::get_industry_categories();
		end( $industry_list );
		$result = key( $industry_list ); // last in array will be the default value

		if ( is_array( self::$category_mapping ) && array_key_exists( $category_id, self::$category_mapping ) ) {
			$result = self::$category_mapping[ $category_id ];
		}

		return $result;
	}

	/**
	 * @return string[]
	 */
	public static function get_industry_categories() {
		global $categories_industry;

		return $categories_industry;
	}

	/**
	 * Get all products settings in key-value pairs
	 *
	 * @return array
	 */
	public static function get_products_settings_list() {
		return [
			'sp_activate_replenishment'     => 'Activate Replenishment',
			'sp_supplier_id'                => 'Supplier',
			'sp_weeks_of_stock'             => 'Weeks Of Stock',
			'sp_lead_time'                  => 'Lead Time',
			'sp_product_launch_date'        => 'Product Launch Date',
			'sp_product_replenishment_date' => 'Replenishment Date',
			'sp_inbound_stock_limit'        => 'Inbound Stock Limit',
			'sp_on_hold'                    => 'On Hold',
			'sp_primary_category'           => 'Primary Category',
			'sp_size_packs'                 => 'Size Packs',
			'sp_size_pack_threshold'        => 'Size Pack Threshold',
			'sp_sku_pack_size'              => 'SKU Pack Size',
			'sp_supplier_product_id'        => 'Supplier Product ID',
			'sp_supplier_product_reference' => 'Supplier Product Reference',
			'sp_cost'                       => 'Unit Cost Price',
			'sp_stock_value'                => 'Stock Value',
			'sp_mark_up'                    => 'Markup',
			'sp_margin'                     => 'Net Margin (Incl VAT)',
			'sp_margin_tax'                 => 'Net Margin (excl VAT)',
		];
	}

	/**
	 * @return array
	 */
	public static function get_all_categories( $hierarchical = 1 ) {
		$taxonomy   = 'product_cat';
		$orderby    = 'name';
		$show_count = 0;
		$pad_counts = 0;
		$title      = '';
		$empty      = 0;

		$args           = array(
			'taxonomy'     => $taxonomy,
			'orderby'      => $orderby,
			'show_count'   => $show_count,
			'pad_counts'   => $pad_counts,
			'hierarchical' => $hierarchical,
			'title_li'     => $title,
			'hide_empty'   => $empty,
		);
		$all_categories = get_categories( $args );

		$result = [];
		foreach ( $all_categories as $category ) {
			$result[ $category->cat_ID ] = $category->name;
		}

		return $result;
	}

	/**
	 * @param $product_id
	 *
	 * @return array
	 */
	public static function get_sales_forecast_by_product_id( $product_id ) {
		$_pf      = new \WC_Product_Factory();
		$_product = $_pf->get_product( $product_id );

		// Prevent non-product posts being interpreted as products, to avoid errors
		if ( ! is_object( $_product ) ) {
			return [];
		}

		$last_forecast = get_option( 'sp.last_forecast' );
		$data          = self::parse_forecast_json( wp_unslash( $last_forecast ) );

		if ( ! isset( $data[ $product_id ] ) ) {
			$result = [
				'product_id'             => $product_id,
				'SKU'                    => $_product->get_sku(),
				'normalized_category_id' => sp_get_normalized_category_id( sp_get_industry_id() ),
				'WeeklySlesArray'        => array_fill( 0, 24, 0.00 ),
			];

			/**
			 * Get normalized_category_id from parent product
			 */
			$parent_id = wp_get_post_parent_id( $product_id );
			if ( $parent_id ) {
				$tmp = self::get_sales_forecast_by_product_id( $parent_id );
				if ( $tmp ) {
					$result['normalized_category_id'] = $tmp['normalized_category_id'];
				}
			}
		} else {
			$result = $data[ $product_id ];
		}

		return $result;
	}

	/**
	 * @param $filename
	 *
	 * @return array
	 */
	public static function parse_forecast_file( $filename ) {
		$data   = json_decode( file_get_contents( $filename ), true );
		$data   = $data['ForecastItem'];
		$result = [];
		if ( $data ) {
			foreach ( $data as $row ) {
				$result[ $row['product_id'] ] = $row;
			}
		}

		return $result;
	}

	/**
	 * @param $json
	 *
	 * @return array
	 */
	public static function parse_forecast_json( $json ) {
		$data   = json_decode( $json, true );
		$data   = $data['ForecastItem'];
		$result = [];
		if ( $data ) {
			foreach ( $data as $row ) {
				$result[ $row['product_id'] ] = $row;
			}
		}

		return $result;
	}

	/**
	 * @return array|null|object
	 */
	public static function get_all_product_ids() {
		global $wpdb;

		return $wpdb->get_results( "
			SELECT p.ID AS product_id
			FROM {$wpdb->prefix}posts p
			LEFT JOIN {$wpdb->prefix}posts p_child on p_child.post_parent = p.ID
			WHERE p.post_type IN ('product', 'product_variation')
			  AND p.post_status IN ('publish')
			GROUP BY 1
			HAVING count(p_child.ID) = 0", ARRAY_A );
	}

	/**
	 * @return array|null|object
	 */
	public static function get_all_product_settings() {
		global $wpdb;

		return $wpdb->get_results( "SELECT * FROM {$wpdb->product_settings}", ARRAY_A );
	}

	/**
	 * Get suppliers list from DB
	 *
	 * @return mixed
	 */
	public static function get_suppliers() {
		global $wpdb;

		$suppliers = $wpdb->get_results( "select a.*, 0 as orders, 0 as total_orders from {$wpdb->suppliers} a", ARRAY_A );

		return $suppliers;
	}

	/**
	 * Get warehouses list or one item from DB
	 *
	 * @param $id int
	 *
	 * @return mixed
	 */
	public static function get_warehouses( $id = null ) {
		global $wpdb;

		$where = '';
		if ( $id ) {
			$id    = (int) $id;
			$where = "and id = {$id}";
		}

		$warehouses = $wpdb->get_results( "select * from {$wpdb->warehouses} where 1 {$where}", ARRAY_A );

		return $warehouses;
	}

}