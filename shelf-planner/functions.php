<?php
/**
 * @param $option
 * @param $text
 *
 * @return string
 */
function sp_settings_get_radio_1( $option, $text ) {
	static $tmpl = '<input type="radio" name="%s" %s value="%s" /> <span class="sphd-p">Option %3$s: %s.</span>';
	static $name = 'business-model';
	static $option_business_model = null;

	if ( ! isset( $option_business_model ) ) {
		$option_business_model = get_option( 'sp.settings.business_model' );
	}
	$checked = $option_business_model == $option ? 'checked="checked"' : '';

	return sprintf( $tmpl, esc_attr( $name ), esc_attr( $checked ), esc_html( $option ), esc_html( __( $text, QA_MAIN_DOMAIN ) ) );
}

/**
 * @param $option
 * @param $text
 *
 * @return string
 */
function sp_settings_get_radio_3( $option, $text ) {
	static $tmpl = '<input type="radio" name="%s" %s value="%s" /> <span class="warehouses-radio"> %3$s %s</span>';
	static $name = 'business-model';
	static $option_business_model = null;

	if ( ! isset( $option_business_model ) ) {
		$option_business_model = get_option( 'sp.settings.business_model' );
	}
	$checked = $option_business_model == $option ? 'checked="checked"' : '';

	return sprintf( $tmpl, esc_attr( $name ), esc_attr( $checked ), esc_html( $option ), esc_html( __( $text, QA_MAIN_DOMAIN ) ) );
}

/**
 * @param $option
 * @param $text
 *
 * @return string
 */
function sp_settings_get_radio_2( $option, $text ) {
	static $tmpl = '<input type="radio" name="%s" %s value="%s" /> <span class="sphd-p">Option %3$s: %s.</span>';
	static $name = 'assortment-size';
	static $option_assortment_size = null;

	if ( ! isset( $option_assortment_size ) ) {
		$option_assortment_size = get_option( 'sp.settings.assortment_size' );
	}
	$checked = $option_assortment_size == $option ? 'checked="checked"' : '';

	return sprintf( $tmpl, esc_attr( $name ), esc_attr( $checked ), esc_html( $option ), esc_html( __( $text, QA_MAIN_DOMAIN ) ) );
}

/**
 * @param $text
 *
 * @return string
 */
function sp_settings_get_checkbox( $text ) {
	global $categories_industry;
	static $options = null;

	if ( ! isset( $options ) ) {
		$options = explode( ',', get_option( 'sp.settings.industry', array() ) );
	}

	$id      = array_search( $text, $categories_industry );
	$checked = is_int( array_search( $id, $options ) ) ? ' checked="checked" ' : '';
	$name    = "industry-{$id}";
	$id      = "id-{$name}";

	return '<input type="checkbox" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '"' . $checked . '>' . '<label class="settings-store-label" for="' . esc_attr($id) . '" style="font-weight: normal"> ' . esc_html( __( $text, QA_MAIN_DOMAIN ) ) . '</label>';
}

/**
 * @param $industry_id
 * @param int $industry_group
 *
 * @return string
 */
function sp_get_normalized_category_id( $industry_id, $industry_group = 10 ) {
	static $template = '{industry-group} {industry} 000 00000';
	$result = explode( ' ', $template );

	$result[0] = $industry_group;
	$result[1] = str_pad( (string) $industry_id, 3, '0', STR_PAD_LEFT );

	return implode( '', $result );
}

/**
 * @param $date_end
 *
 * @return float|int
 */
function sp_days_left( $date_end ) {
	$time_begin = strtotime( date( 'Y-m-d ', time() ) . ' 0:00:00' );
	$time_end   = strtotime( "{$date_end} 0:00:00" );
	$sid        = 24 * 60 * 60;

	$dx      = abs( $time_end - $time_begin );
	$is_late = $time_begin > $time_end;
	$days    = intval( $dx / $sid );

	return $is_late ? - $days : $days;
}

/**
 * @return array|object|null
 */
function sp_get_suppliers() {
	global $wpdb;

	$suppliers = $wpdb->get_results( "select a.*, 0 as orders, 0 as total_orders from {$wpdb->suppliers} a", ARRAY_A );

	return $suppliers;
}

/**
 * @param $value
 *
 * @return string
 */
function sp_get_price( $value ) {
	return number_format( round( $value, 2 ), 2 );
}

/**
 * @return string
 */
function sp_make_order_pdf() {
	$currency_code = get_woocommerce_currency();
	$contents      = file_get_contents( SP_ROOT_DIR . '/pages/order.html' );

	$order_info = (array) $_SESSION['order_info'];

	$html = '<h4>' . __( 'Purchase Orders', QA_MAIN_DOMAIN ) . '</h4>';
	$html .= '<div class="card"><div class="card-body">';
	$html .= '<div class="print-hide" style="background-color: green; color: white; margin: auto; font-size: 14px; padding: 10px; width: 90%; position: relative; border-radius: 3px; text-align: center">&nbsp;' . __( 'Purchase Order successfully created!', QA_MAIN_DOMAIN ) . '&nbsp;</div>';

	$html .= $contents;

	$html_vars = array();

	$html_vars['currency_code'] = esc_html( $currency_code );

	$html_vars['LOGO'] = '<img id="sp-po-logo" width="250" src="data:' . esc_attr( get_option( 'sp.settings.po_company_logo', '' ) ) . '">';

	$html_vars['company_name']    = esc_html( get_option( 'sp.settings.po_company_name', '' ) );
	$html_vars['company_address'] = esc_html( $order_info['supplier_address'] );

	$html_vars['order_no']       = esc_html( $order_info['purchase_order_num'] );
	$html_vars['ref_no']         = esc_html( $order_info['reference_number'] );
	$html_vars['delivery_terms'] = esc_html( $order_info['delivery_terms'] );
	$html_vars['payment_terms']  = esc_html( $order_info['payment_terms'] );

	$html_vars['vendor_no']   = esc_html( $order_info['vendor_no'] );
	$html_vars['vendor_vat']  = esc_html( $order_info['vendor_vat'] );
	$html_vars['account_no']  = esc_html( $order_info['account_no'] );
	$html_vars['account_id']  = esc_html( $order_info['account_id'] );
	$html_vars['assigned_to'] = esc_html( $order_info['assigned_to'] );

	$html_vars['free_text'] = esc_html( $order_info['description'] );

	$html_vars['order_date'] = esc_html( date( 'd/m/Y', strtotime( $order_info['order_date'] ) ) );
	$html_vars['ship_date']  = esc_html( date( 'd/m/Y', strtotime( $order_info['expected_delivery_date'] ) ) );

	$html_vars['postal_code']  = esc_html( get_option( 'sp.settings.po_postal_code', '' ) );
	$html_vars['city']         = esc_html( get_option( 'sp.settings.po_city', '' ) );
	$html_vars['iban']         = esc_html( get_option( 'sp.settings.po_iban', '' ) );
	$html_vars['swift_code']   = esc_html( get_option( 'sp.settings.po_swift_code', '' ) );
	$html_vars['vat_reg']      = esc_html( get_option( 'sp.settings.po_vat_number', '' ) );
	$html_vars['country']      = esc_html( get_option( 'sp.settings.po_country', '' ) );
	$html_vars['primary_mail'] = esc_html( get_option( 'sp.settings.po_email', '' ) );
	$html_vars['account_nr']   = esc_html( get_option( 'sp.settings.po_account_number', '' ) );
	$html_vars['website']      = esc_html( get_option( 'sp.settings.po_website', '' ) );
	$html_vars['bank']         = esc_html( get_option( 'sp.settings.po_bank', '' ) );
	$html_vars['branch']       = esc_html( get_option( 'sp.settings.po_branch', '' ) );
	$html_vars['phone']        = esc_html( get_option( 'sp.settings.po_phone', '' ) );
	$html_vars['street']       = esc_html( get_option( 'sp.settings.po_company_address', '' ) );

	/**
	 * Free text - TODO: make comment field for order
	 */

	/**
	 * Delivery address
	 */
	$html_vars['delivery_address'] = '-';
	// TODO: make constant
	if ( $order_info['deliver_to'] == 'warehouse' ) {
		$tmp_warehouse_info            = QAMain_Core::get_warehouses( $order_info['warehouse_id'] );
		$html_vars['delivery_address'] = $tmp_warehouse_info[0]['warehouse_address'];
	}

	$product_rows = array();
	$totals       = array( 'qty' => 0, 'amount' => 0 );
	foreach ( json_decode( $order_info['product_data'], true ) as $product_id => $each_table_row ) {
		$amount           = $each_table_row['qty'] * $each_table_row['price'];
		$product_row      = '<tr>
            <td width="20%">' . esc_html( $product_id ) . '</td>
            <td width="45%">' . esc_html( $each_table_row['name'] ) . '</td>
            <td width="15%" style=" text-align: center;">' . esc_html( $each_table_row['qty'] ) . '</td>
            <td width="15%" style=" text-align: center;">' . esc_html( sp_get_price( $each_table_row['price'] ) ) . '</td>
            <td width="15%" style=" text-align: center;">' . esc_html( $amount ) . '</td>
        </tr>
		';
		$totals['qty']    += $each_table_row['qty'];
		$totals['amount'] += $amount;
		$product_rows[]   = $product_row;
	}

	// Already escaped
	$html_vars['product_data']  = implode( "\n", $product_rows );
	$html_vars['product_total'] = '<tr>
            <td width="20%" style=""></td>
            <td width="45%" style="text-align: center; text-align:right; background-color: #d7d7d7; font-weight: bold">Total ' . esc_html( $currency_code ) . '</td>
            <td width="15%" style="background-color: #d7d7d7; text-align: center; font-weight: bold">' . esc_html( $totals['qty'] ) . '</td>
            <td width="15%" style="background-color: #d7d7d7; text-align: center; font-weight: bold">-</td>
            <td width="15%" style="background-color: #d7d7d7; text-align: center; font-weight: bold">' . esc_html( sp_get_price( $totals['amount'] ) ) . '</td>
        </tr>
	';

	foreach ( $html_vars as $html_var => $html_val ) {
		$html     = str_replace( "[{$html_var}]", $html_val, $html );
		$contents = str_replace( "[{$html_var}]", $html_val, $contents );
	}

	return [$html, $order_info];
}

/**
 * Deletes meta_key named 'SP_META_KEY_PROCESSED' from each order
 */
function sp_unpush_orders() {
	$orders = wc_get_orders( array(
		'orderby'   => 'date',
		'order'     => 'DESC',
		'post_type' => 'shop_order',
		'limit'     => - 1,
	) );

	if ( $orders ) {
		foreach ( $orders as $order ) {
			delete_post_meta( $order->get_id(), SP_META_KEY_PROCESSED );
		}
	}
}