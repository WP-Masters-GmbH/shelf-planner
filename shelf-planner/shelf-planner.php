<?php
/**
 * Shelf Planner
 *
 * Shelf Planner helps you reduce waste and minimize spillage, all whilst improving your business and bottom line.
 *
 * Plugin Name: Shelf Planner
 * Plugin URI: https://shelfplanner.com/
 * Version: 1.0.5
 * Author: Quick Assortments AB
 * Description: Shelf Planner helps you reduce waste and minimize spillage, all whilst improving your business and bottom line.
 * Text Domain: shelf-planner
 *
 * @author      Quick Assortments AB
 * @version     v.1.0.5 (18/07/22)
 * @copyright   Copyright (c) 2022
 */

const QA_MAIN_DOMAIN = 'shelf_planner';
const SP_TEXT_DOMAIN = 'shelf_planner';

// delete_option( 'sp.wizard_in_progress' );

/**
 * Industries list for mapping
 */
$categories_industry = array(
	1  => 'Fashion & Apparel',
	2  => 'Footwear',
	3  => 'Bags & Suitcases',
	4  => 'Jewellery & Watches',
	5  => 'Babywear',
	6  => 'Optical',
	7  => 'Sportswear & Sporting goods',
	8  => 'Outdoor Life',
	9  => 'Equestrian',
	10 => 'Drinks & Beverages',
	11 => 'Food',
	12 => 'Kitchen & Dining',
	13 => 'Beauty & Personal Care',
	14 => 'Home & Household',
	15 => 'Furniture & Decoration',
	16 => 'Consumer Electronics',
	17 => 'Health',
	18 => 'Toys & Games',
	19 => 'Bookshop',
	20 => 'Gardening',
	21 => 'DIY',
	22 => 'Pet Store',
	23 => 'Car Parts & Car Care',
	24 => 'Other',
);

$sp_countries_normilized = [
	'AF' => 'Afghanistan',
	'AX' => 'Åland Islands',
	'AL' => 'Albania',
	'DZ' => 'Algeria',
	'AS' => 'American Samoa',
	'AD' => 'Andorra',
	'AO' => 'Angola',
	'AI' => 'Anguilla',
	'AQ' => 'Antarctica',
	'AG' => 'Antigua and Barbuda',
	'AR' => 'Argentina',
	'AM' => 'Armenia',
	'AW' => 'Aruba',
	'AU' => 'Australia',
	'AT' => 'Austria',
	'AZ' => 'Azerbaijan',
	'BS' => 'Bahamas',
	'BH' => 'Bahrain',
	'BD' => 'Bangladesh',
	'BB' => 'Barbados',
	'BY' => 'Belarus',
	'BE' => 'Belgium',
	'BZ' => 'Belize',
	'BJ' => 'Benin',
	'BM' => 'Bermuda',
	'BT' => 'Bhutan',
	'BO' => 'Bolivia (Plurinational State of)',
	'BQ' => 'Bonaire, Sint Eustatius and Saba',
	'BA' => 'Bosnia and Herzegovina',
	'BW' => 'Botswana',
	'BV' => 'Bouvet Island',
	'BR' => 'Brazil',
	'IO' => 'British Indian Ocean Territory',
	'BN' => 'Brunei Darussalam',
	'BG' => 'Bulgaria',
	'BF' => 'Burkina Faso',
	'BI' => 'Burundi',
	'CV' => 'Cabo Verde',
	'KH' => 'Cambodia',
	'CM' => 'Cameroon',
	'CA' => 'Canada',
	'KY' => 'Cayman Islands',
	'CF' => 'Central African Republic',
	'TD' => 'Chad',
	'CL' => 'Chile',
	'CN' => 'China',
	'CX' => 'Christmas Island',
	'CC' => 'Cocos (Keeling) Islands',
	'CO' => 'Colombia',
	'KM' => 'Comoros',
	'CG' => 'Congo',
	'CD' => 'Congo (Democratic Republic of the)',
	'CK' => 'Cook Islands',
	'CR' => 'Costa Rica',
	'CI' => 'Côte d\'Ivoire',
	'HR' => 'Croatia',
	'CU' => 'Cuba',
	'CW' => 'Curaçao',
	'CY' => 'Cyprus',
	'CZ' => 'Czechia',
	'DK' => 'Denmark',
	'DJ' => 'Djibouti',
	'DM' => 'Dominica',
	'DO' => 'Dominican Republic',
	'EC' => 'Ecuador',
	'EG' => 'Egypt',
	'SV' => 'El Salvador',
	'GQ' => 'Equatorial Guinea',
	'ER' => 'Eritrea',
	'EE' => 'Estonia',
	'SZ' => 'Eswatini',
	'ET' => 'Ethiopia',
	'FK' => 'Falkland Islands (Malvinas)',
	'FO' => 'Faroe Islands',
	'FJ' => 'Fiji',
	'FI' => 'Finland',
	'FR' => 'France',
	'GF' => 'French Guiana',
	'PF' => 'French Polynesia',
	'TF' => 'French Southern Territories',
	'GA' => 'Gabon',
	'GM' => 'Gambia',
	'GE' => 'Georgia',
	'DE' => 'Germany',
	'GH' => 'Ghana',
	'GI' => 'Gibraltar',
	'GR' => 'Greece',
	'GL' => 'Greenland',
	'GD' => 'Grenada',
	'GP' => 'Guadeloupe',
	'GU' => 'Guam',
	'GT' => 'Guatemala',
	'GG' => 'Guernsey',
	'GN' => 'Guinea',
	'GW' => 'Guinea-Bissau',
	'GY' => 'Guyana',
	'HT' => 'Haiti',
	'HM' => 'Heard Island and McDonald Islands',
	'VA' => 'Holy See',
	'HN' => 'Honduras',
	'HK' => 'Hong Kong',
	'HU' => 'Hungary',
	'IS' => 'Iceland',
	'IN' => 'India',
	'ID' => 'Indonesia',
	'IR' => 'Iran (Islamic Republic of)',
	'IQ' => 'Iraq',
	'IE' => 'Ireland',
	'IM' => 'Isle of Man',
	'IL' => 'Israel',
	'IT' => 'Italy',
	'JM' => 'Jamaica',
	'JP' => 'Japan',
	'JE' => 'Jersey',
	'JO' => 'Jordan',
	'KZ' => 'Kazakhstan',
	'KE' => 'Kenya',
	'KI' => 'Kiribati',
	'KP' => 'Korea (Democratic People\'s Republic of)',
	'KR' => 'Korea (Republic of)',
	'KW' => 'Kuwait',
	'KG' => 'Kyrgyzstan',
	'LA' => 'Lao People\'s Democratic Republic',
	'LV' => 'Latvia',
	'LB' => 'Lebanon',
	'LS' => 'Lesotho',
	'LR' => 'Liberia',
	'LY' => 'Libya',
	'LI' => 'Liechtenstein',
	'LT' => 'Lithuania',
	'LU' => 'Luxembourg',
	'MO' => 'Macao',
	'MK' => 'Macedonia (the former Yugoslav Republic of)',
	'MG' => 'Madagascar',
	'MW' => 'Malawi',
	'MY' => 'Malaysia',
	'MV' => 'Maldives',
	'ML' => 'Mali',
	'MT' => 'Malta',
	'MH' => 'Marshall Islands',
	'MQ' => 'Martinique',
	'MR' => 'Mauritania',
	'MU' => 'Mauritius',
	'YT' => 'Mayotte',
	'MX' => 'Mexico',
	'FM' => 'Micronesia (Federated States of)',
	'MD' => 'Moldova (Republic of)',
	'MC' => 'Monaco',
	'MN' => 'Mongolia',
	'ME' => 'Montenegro',
	'MS' => 'Montserrat',
	'MA' => 'Morocco',
	'MZ' => 'Mozambique',
	'MM' => 'Myanmar',
	'NA' => 'Namibia',
	'NR' => 'Nauru',
	'NP' => 'Nepal',
	'NL' => 'Netherlands',
	'NC' => 'New Caledonia',
	'NZ' => 'New Zealand',
	'NI' => 'Nicaragua',
	'NE' => 'Niger',
	'NG' => 'Nigeria',
	'NU' => 'Niue',
	'NF' => 'Norfolk Island',
	'MP' => 'Northern Mariana Islands',
	'NO' => 'Norway',
	'OM' => 'Oman',
	'PK' => 'Pakistan',
	'PW' => 'Palau',
	'PS' => 'Palestine, State of',
	'PA' => 'Panama',
	'PG' => 'Papua New Guinea',
	'PY' => 'Paraguay',
	'PE' => 'Peru',
	'PH' => 'Philippines',
	'PN' => 'Pitcairn',
	'PL' => 'Poland',
	'PT' => 'Portugal',
	'PR' => 'Puerto Rico',
	'QA' => 'Qatar',
	'RE' => 'Réunion',
	'RO' => 'Romania',
	'RU' => 'Russian Federation',
	'RW' => 'Rwanda',
	'BL' => 'Saint Barthélemy',
	'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
	'KN' => 'Saint Kitts and Nevis',
	'LC' => 'Saint Lucia',
	'MF' => 'Saint Martin (French part)',
	'PM' => 'Saint Pierre and Miquelon',
	'VC' => 'Saint Vincent and the Grenadines',
	'WS' => 'Samoa',
	'SM' => 'San Marino',
	'ST' => 'Sao Tome and Principe',
	'SA' => 'Saudi Arabia',
	'SN' => 'Senegal',
	'RS' => 'Serbia',
	'SC' => 'Seychelles',
	'SL' => 'Sierra Leone',
	'SG' => 'Singapore',
	'SX' => 'Sint Maarten (Dutch part)',
	'SK' => 'Slovakia',
	'SI' => 'Slovenia',
	'SB' => 'Solomon Islands',
	'SO' => 'Somalia',
	'ZA' => 'South Africa',
	'GS' => 'South Georgia and the South Sandwich Islands',
	'SS' => 'South Sudan',
	'ES' => 'Spain',
	'LK' => 'Sri Lanka',
	'SD' => 'Sudan',
	'SR' => 'Suriname',
	'SJ' => 'Svalbard and Jan Mayen',
	'SE' => 'Sweden',
	'CH' => 'Switzerland',
	'SY' => 'Syrian Arab Republic',
	'TW' => 'Taiwan, Province of China',
	'TJ' => 'Tajikistan',
	'TZ' => 'Tanzania, United Republic of',
	'TH' => 'Thailand',
	'TL' => 'Timor-Leste',
	'TG' => 'Togo',
	'TK' => 'Tokelau',
	'TO' => 'Tonga',
	'TT' => 'Trinidad and Tobago',
	'TN' => 'Tunisia',
	'TR' => 'Turkey',
	'TM' => 'Turkmenistan',
	'TC' => 'Turks and Caicos Islands',
	'TV' => 'Tuvalu',
	'UG' => 'Uganda',
	'UA' => 'Ukraine',
	'AE' => 'United Arab Emirates',
	'GB' => 'United Kingdom of Great Britain and Northern Ireland',
	'US' => 'United States of America',
	'UM' => 'United States Minor Outlying Islands',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VU' => 'Vanuatu',
	'VE' => 'Venezuela (Bolivarian Republic of)',
	'VN' => 'Viet Nam',
	'VG' => 'Virgin Islands (British)',
	'VI' => 'Virgin Islands (U.S.)',
	'WF' => 'Wallis and Futuna',
	'EH' => 'Western Sahara',
	'YE' => 'Yemen',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe',
];

/**
 * Setup Wizard
 */
add_action('admin_head', 'shelf_planner_replace_admin_menu_icons_css', 99);
function shelf_planner_replace_admin_menu_icons_css() {
	?>
    <style>
        #toplevel_page_shelf_planner {
            display: none !important;
        }
    </style>
	<?php
}

/**
 * Setup Wizard
 */
register_activation_hook( __FILE__, function () {
	$was_installed = get_option( 'sp.wizard_in_progress', null );
	if ( ! isset( $was_installed ) ) {
		update_option( 'sp.wizard_in_progress', 1 );
	}
	update_option('sp.full_screen', true);
	update_option('sp.default_currency', get_woocommerce_currency());
} );

register_deactivation_hook( __FILE__, function () {
	global $wpdb;
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}api_log`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}purchase_orders`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}purchase_orders_bundle`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}suppliers`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}product_settings`");
	// $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}warehouses`");
	// delete_option( 'sp.wizard_in_progress' );
	// delete_option( 'sp.full_screen' );
} );

function sphd_start_wizard( $plugin ) {
	global $wpdb;

	if ( $plugin == plugin_basename( __FILE__ ) ) {
		update_option( 'sp.in_background', 'checked' );
		update_option( 'sp.log', 'checked' );

		update_option( 'sp.settings.db_version', 1 );

		exit( wp_redirect( admin_url( 'admin.php?page=shelf_planner&wizard_step=1' ) ) );
	}
}

add_action('wp_ajax_get_logs_table', 'get_logs_table');

/**
 * Template for Bulk Edit Products Page
 */
function get_logs_table()
{
    global $wpdb;

    // Settings for Get Products
    $page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : 1;
    $limit = 25;
    $offset = $limit * ($page - 1);

    // Get Logs
    $table = $wpdb->prefix.'sp_api_log';
    $table_data = $wpdb->get_results("SELECT * FROM {$table} ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}");

    // Send Response
    wp_send_json([
        'status' => 'true',
        'table' => $table_data
    ]);
}

add_action( 'activated_plugin', 'sphd_start_wizard' );

if ( get_option( 'sp.wizard_in_progress', 1 ) ) {
	define( 'SPDH_ROOT_DIR', __DIR__ );
	define( 'SPDH_ROOT', __FILE__ );

	add_filter( 'show_admin_bar', '__return_false' );

	require_once __DIR__ . '/sphd_wizard.class.php';

	return SPHD_Wizard::init();
}

// End of Wizard: plugin is ready for use
function ajax_sphd_purge_data() {
	global $wpdb;

	$options = array(
		'sp.in_background',
		'sp.log',
		'sp.last_forecast_success',
		'sp.wizard_in_progress',
		'sp.settings.db_version',
		'sp.settings.business_model',
		'sp.settings.assortment_size',
		'sp.settings.industry',
		'sp.settings.default_weeks_of_stock',
		'sp.settings.default_lead_time',
		'sp.settings.po_auto-generate_orders',
		'sp.settings.po_prefix',
		'sp.settings.po_next_number',
		'sp.settings.po_stock_type',
	);

	foreach ( $options as $each_option ) {
		delete_option( $each_option );
	}

	$db_tables = array(
		// 'purchase_orders',
		// 'suppliers',
		// 'qa_main_products_settings',
		// 'warehouses',
	);

	foreach ( $db_tables as $each_table ) {
		$wpdb->query( "DROP TABLE IF EXISTS `{$wpdb->prefix}{$each_table}`" );
	}

	wp_die( json_encode( array( 'message' => 'purged all data' ) ) );
}

add_action( 'wp_ajax_sphd_purge_data', 'ajax_sphd_purge_data' );

/**
 * SP API
 */
const SP_API_ENDPOINT = 'https://apifc.shelfplanner.com';

/**
 * Meta key for processing marks
 */
const SP_META_KEY_PROCESSED = 'imported_to_shelf_planner_2021_test14';

/**
 * How much orders to push per call (while import process)
 */
const SP_ORDERS_IMPORT_PER_CALL = 50;

/**
 * Shelf Planner - Historical Data
 * Admin Settings
 */
require_once __DIR__ . '/includes/core.php';
require_once __DIR__ . '/admin_init.php';

/**
 * Shelf Planner - All the data pages
 * Domain
 */
define( 'SP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'SP_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

const SP_FILE_INDEX    = __FILE__;
const SP_ROOT_DIR      = __DIR__;
const SP_FORECAST_FILE = __DIR__ . '/forecast.json';

require_once __DIR__ . '/includes/functions_new.php';
require_once __DIR__ . '/includes/database.php';

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/includes/xlsxwriter.class.php';
require_once __DIR__ . '/quick-assortments-main.core.class.php';

/**
 * Redirects user to plugin after activation
 *
 * @param $plugin
 */
if ( ! isset( $wpdb ) ) {
	return;
} elseif ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}

/**
 * Init Admin Page
 */
SPHD_Admin::init();

/**
 * BACKGROUND IMPORT
 */
$bg_import_status = get_option( 'sp.in_background', 'checked' );

/**
 * @param $affiliate_id
 *
 * @return string
 */
function sp_get_forecast_json_url( $affiliate_id = null ): string {
	if ( ! $affiliate_id ) {
		$affiliate_id = sp_get_affiliate_id();
	}

	return SP_API_ENDPOINT . '/FullForecast.aspx?affiliate_id=' . $affiliate_id;
}

/**
 * @return array|false|int|string|string[]|null
 */
function sp_get_affiliate_id() {
	return str_replace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
}

/**
 * Get Forecast
 */
if ( is_admin() || isset( $_GET['sp_forecast_push'] ) ) {
	if ( isset( $_GET['sp_purge_api_log'] ) ) {
		purgeApiLog();
	}

	$last_forecast = get_option( 'sp.last_forecast' );
	$last_forecast = (array) json_decode( $last_forecast, true );

	if ( ! $last_forecast || isset( $_GET['sp_forecast_push'] ) || ( get_option( 'sp.last_forecast_success' ) && ( time() - get_option( 'sp.last_forecast_success' ) > 1 * 60 * 60 ) ) ) {
		set_time_limit( 0 );
		$affiliate_id = sp_get_affiliate_id();
		$forecast_url = sp_get_forecast_json_url();

		try {
			spApiLog( 'Trying to download the JSON forecast: ' . $forecast_url );

			$sp_json_data = wp_remote_retrieve_body( wp_remote_get( $forecast_url, array( 'timeout' => 60 * 10 ) ) );
			if ( $sp_json_data !== false && mb_strlen( $sp_json_data ) > 0 ) {
				spApiLog( 'Download Success, JSON length is: ' . mb_strlen( $sp_json_data ), 'success' );
				update_option( 'sp.last_forecast', $sp_json_data );
				update_option( 'sp.last_forecast_success', time() );
			} elseif ( file_exists( SP_FORECAST_FILE ) ) {
				$sp_json_data = file_get_contents( SP_FORECAST_FILE );
				spApiLog( 'Download From Local File Success, JSON length is: ' . mb_strlen( $sp_json_data ), 'success' );
				update_option( 'sp.last_forecast', $sp_json_data );
				update_option( 'sp.last_forecast_success', time() );
			} else {
				spApiLog( 'Download Failed', 'error' );
			}
		} catch ( Exception $e ) {
			spApiLog( 'Failed to download the JSON forecast: ' . $e->getMessage(), 'error' );
		}

		if ( isset( $_GET['sp_forecast_push'] ) ) {
			die( "Download Success, JSON: " . esc_textarea( $sp_json_data ) );
		}
	}
}

/**
 * @param $order_ids
 *
 * @internal param $order_id
 */
function pushOrder( $order_ids ) {
	if ( ! $order_ids ) {
		return;
	}

	$sales_row = [];

	foreach ( $order_ids as $order_id ) {
		// Allow code execution only once
		if ( ! get_post_meta( $order_id, SP_META_KEY_PROCESSED, true ) ) {
			// Get an instance of the WC_Order object
			$order = wc_get_order( $order_id );

			$order->update_meta_data( SP_META_KEY_PROCESSED, date( 'd.m.Y H:i:s' ) );
			$order->save();

			if ( ! $order ) {
				spApiLog( "Request denied: wrong order_id {$order_id}, not found", 'error' );
				header( 'HTTP/1.0 404 Not Found' );
				exit;
			}

			spApiLog( "Start Processing: order_id {$order_id} with " . count( $order->get_items() ) . ' item(s)' );

			// Loop through order items
			foreach ( $order->get_items() as $item_id => $item ) {
				if ( ! method_exists( $order, 'get_date_paid' ) ) {
					spApiLog( "Skip Non Order Item: {$order_id}" );
					exit;
				}

				spApiLog( "Start Processing Order Item: order_id {$order_id}, product_id " . $item->get_product_id() );

				$variation_id = $item->get_variation_id();
				if ( ! empty( $variation_id ) ) {
					spApiLog( "VARIATION FOUND: {$variation_id}, PARENT PRODUCT: " . $item->get_product_id() );
				}

				$product = new WC_Product( $item->get_product_id() );

				if ( $product->get_status() != 'publish' ) {
					continue;
				}

				/**
				 * Fill all the data in
				 */
				$tmp                    = [];
				$tmp['creation_date']   = date( "Y-m-d", strtotime( $order->get_date_created() ) );
				$tmp['affiliate_id']    = str_replace( 'www.', '', ( $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : AFFILIATE_ID ) );
				$tmp['order_id']        = $order_id;
				$tmp['segment_id']      = 3;
				$primary_category_id    = QAMain_Core::get_product_primary_category_id( $item->get_product_id() );
				$tmp['raw_category_id'] = empty( $primary_category_id ) ? 0 : $primary_category_id;

				$date_payed = $order->get_date_paid();
				if ( $date_payed ) {
					$date_payed = $date_payed->getTimestamp();
				}
				if ( ! $date_payed ) {
					$date_payed = $order->get_date_created()->getTimestamp();
				}
				$tmp['order_date'] = $tmp['shipping_date'] = date( "Y-m-d", $date_payed );

				$tmp['product_stock'] = $product->get_stock_quantity();
				if ( ! empty( $variation_id ) ) {
					$tmp['product_id'] = $variation_id;
				} else {
					$tmp['product_id'] = $item->get_product_id();
				}
				$tmp['product_creation_date'] = date( "Y-m-d", strtotime( $product->get_date_created() ) );
				$tmp['product_sku']           = $product->get_sku();

				if ( $item->get_variation_id() ) {
					$tmp['product_options'] = [ $item->get_variation_id() ];
				} else {
					$tmp['product_options'] = [];
				}
				$tmp['product_options']          = "";
				$tmp['product_strong_option1']   = 0;
				$tmp['product_strong_option2']   = 0;
				$tmp['product_strong_option3']   = 0;
				$tmp['product_strong_option4']   = 0;
				$tmp['product_quantity_ordered'] = $item->get_quantity();

				$tmp['product_cost_price']     = sp_get_cost_price( $tmp['product_id'] );
				$tmp['product_original_price'] = (string) floatval( $product->get_regular_price() );
				$tmp['product_final_price']    = (string) floatval( $product->get_price() );

				$with_tax    = wc_get_price_including_tax( $product );
				$without_tax = wc_get_price_excluding_tax( $product );

				if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
					$with_tax    = $product->get_price_including_tax();
					$without_tax = $product->get_price_excluding_tax();
				}

				if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
					$with_tax    = 0;
					$without_tax = 0;
				}

				$tax_amount         = $with_tax - $without_tax;
				$tmp['product_vat'] = round( ( $tax_amount / max( $without_tax, 0.01 ) ) * 100, 1 );

				$shipping_class_id = $product->get_shipping_class_id();
				$shipping_class    = $product->get_shipping_class();
				$fee               = 0;
				if ( $shipping_class_id ) {
					$flat_rates = get_option( "woocommerce_flat_rates" );
					$fee        = ( is_array( $flat_rates ) ) ? $flat_rates[ $shipping_class ]['cost'] : 0;
				}
				$flat_rate_settings = get_option( "woocommerce_flat_rate_settings" );

				$flat_rate_cost = ( is_array( $flat_rate_settings ) ) ? $flat_rate_settings['cost_per_order'] : 0;
				
				$tmp['product_shipping_price'] = $flat_rate_cost + $fee;
				$tmp['order_grandtotal']       = $order->get_total();
				$tmp['order_discount']         = $order->get_total_discount();

				$tmp['shipping_country'] = addslashes( $order->get_shipping_country() );
				$tmp['shipping_town']    = addslashes( $order->get_shipping_city() );
				$tmp['billing_country']  = addslashes( $order->get_billing_country() );
				$tmp['billing_town']     = addslashes( $order->get_billing_city() );

				// We need string here, not array! API expects string with commas inside!
				$tmp['industry_id']            = sp_get_industry_id();
				$tmp['normalized_category_id'] = sp_get_normalized_category_id( $tmp['industry_id'] );

				$product_id             = $item->get_product_id();
				$primary_category_id    = \QAMain_Core::get_product_primary_category_id( $product_id );
				$tmp['raw_category_id'] = empty( $primary_category_id ) ? 0 : $primary_category_id;

				$tmp['industry_id'] = \QAMain_Core::get_industry_by_category( $tmp['raw_category_id'] );

				$tmp['normalized_category_id'] = sp_get_normalized_category_id( $tmp['industry_id'] );
				$tmp['affiliate_country'] = strtoupper( get_option( 'sp.settings.country' ) );

				spApiLog( "[IMPORTANT] Product #{$product_id} - normalized category ID is {$tmp['normalized_category_id']}" );

				if ( $order->get_total() <= 0 || $order->get_item_count() <= 0 ) {
					continue;
				}

				$sales_row[] = $tmp;
			}
		} else {
			spApiLog( "Order Ignored: order_id {$order_id}, it already has " . SP_META_KEY_PROCESSED . " meta key", 'notice' );
			header( "HTTP/1.0 208 Already Reported" );
			exit;
		}

		spApiLog( "API Data Prepared: order_id {$order_id}, data: " . json_encode( $sales_row ) );
	}

	$sp_json_data = json_encode( [ 'SalesRow' => $sales_row ] );

	$url      = SP_API_ENDPOINT . '/Api/Sales';
	$args     = array(
		'method'  => 'POST',
		'headers' => array(
			'content-type' => 'application/json', // Set content type to multipart/form-data
		),
		'body'    => "'" . $sp_json_data . "'",
		'timeout' => 60 * 60 * 10,
	);
	$response = wp_remote_request( $url, $args );

	spApiLog( PHP_EOL . PHP_EOL . "NEW API Call at [" . SP_API_ENDPOINT . '/Api/Sales' . "]" . PHP_EOL . PHP_EOL . $sp_json_data . PHP_EOL . PHP_EOL, 'notice' );

	if ( $sales_row ) {
		if ( $response['body'] != 'result= ok' ) {
			spApiLog( "API Call: order_ids " . implode( ', ', $order_ids ) . ", error " . $response['body'], 'notice' );

			spApiLog( "Response: {$response['body']}" );
		} else {
			spApiLog( "API Call: order_ids " . implode( ', ', $order_ids ) . ", success " . $response['body'], 'success' );

			spApiLog( "Orders Imported: order_ids " . implode( ', ', $order_ids ) . ", added " . SP_META_KEY_PROCESSED . " status to meta data" );

			spApiLog( "Response: {$response['body']}" );
		}
	} else {
		spApiLog( PHP_EOL . PHP_EOL . "Skipped - no products in order", 'notice' );
	}
}

if ( $bg_import_status == 'checked' && $_SERVER['REQUEST_URI'] == '/' ) {
	/**
	 * Add action for page load
	 */
	function sp_action_wp_woocommerce_loaded() {
		$orders = wc_get_orders( array(
			'orderby'    => 'date',
			'order'      => 'DESC',
			'post_type'  => 'shop_order',
			'limit'      => SP_ORDERS_IMPORT_PER_CALL,
			'meta_query' => [
				[
					'key'     => SP_META_KEY_PROCESSED,
					'value'   => 0,
					'compare' => 'NOT EXISTS',
				],
			],
		) );

		if ( $orders ) {
			$order_ids = [];

			foreach ( $orders as $order ) {
				$order_ids[] = $order->get_id();
			}

			sp_payment_complete( $order_ids );
		}
	}

	add_action( 'wp_loaded', 'sp_action_wp_woocommerce_loaded', 10, 1 );

	/**
	 * @param $order_ids
	 *
	 * @internal param $order_id
	 */
	function sp_payment_complete( $order_ids ) {
		spApiLog( "New Orders Processing: " . implode( ', ', $order_ids ) );
		try {
			pushOrder( $order_ids );
		} catch ( Exception $e ) {
			spApiLog( "Error while pushing orders: " . implode( ', ', $order_ids ) );
		}
	}
}

if ( isset( $_GET['sp_clear_api_sent_entries'] ) ) {
	add_action( 'wp_loaded', 'sp_unpush_orders', 9, 1 );
}

/**
 * Make meta queries work
 */
add_filter( 'woocommerce_get_wp_query_args', function ( $wp_query_args, $query_vars ) {
	if ( isset( $query_vars['meta_query'] ) ) {
		$meta_query                  = isset( $wp_query_args['meta_query'] ) ? $wp_query_args['meta_query'] : [];
		$wp_query_args['meta_query'] = array_merge( $meta_query, $query_vars['meta_query'] );
	}

	return $wp_query_args;
}, 10, 2 );

/**
 * WooCommerce meta. Displaying product edit form - additional fields
 */
add_action( 'woocommerce_product_options_general_product_data', 'qa_main_adv_product_options' );
function qa_main_adv_product_options() {
	global $wpdb;

	$product = new WC_Product( get_the_ID() );

	$settings = \QAMain_Core::get_products_settings_list();

	echo '<header><h4 style="padding-bottom: 0px !important; color:#000; margin-bottom: 0px; padding-left: 10px;">Shelf Planner Product Settings</h4></header>';
	echo '<div class="options_group">';
	?>
    <input type="hidden" name="tmp_qa_stock" value="<?php echo esc_attr($product->get_stock_quantity()); ?>"/>
	<?php

	$data = $wpdb->get_results( "SELECT * FROM {$wpdb->product_settings} WHERE product_id = " . get_the_ID(), ARRAY_A );
	if ( isset( $data[0] ) ) {
		$data = $data[0];
	} else {
		$data = array(
			'product_id'                    => get_the_ID(),
			'sp_supplier_id'                => 0,
			'sp_activate_replenishment'     => 0,
			'sp_weeks_of_stock'             => 0,
			'sp_lead_time'                  => 0,
			'sp_product_launch_date'        => 0,
			'sp_product_replenishment_date' => 0,
			'sp_inbound_stock_limit'        => 0,
			'sp_on_hold'                    => 0,
			'sp_primary_category'           => 0,
			'sp_size_packs'                 => 0,
			'sp_size_pack_threshold'        => 0,
			'sp_sku_pack_size'              => 0,
			'sp_supplier_product_id'        => 0,
			'sp_supplier_product_reference' => 0,
			'sp_cost'                       => 0,
			'sp_stock_value'                => 0,
			'sp_mark_up'                    => 0,
			'sp_margin'                     => 0,
			'sp_margin_tax'                 => 0
		);
		$wpdb->insert( $wpdb->product_settings, $data );
	}

	/**
	 * Set product creation date by default, if it was not set
	 */
	if ( ! $data['sp_product_launch_date'] || $data['sp_product_launch_date'] == '0000-00-00' ) {
		if ( method_exists( $product, 'get_date_created' ) && ! is_null( $product->get_date_created() ) ) {
			$data['sp_product_launch_date'] = $product->get_date_created()->format( 'Y-m-d' );
		}
	}

	/**
	 * Set product primary category by default, if it was not set
	 */
	if ( ! $data['sp_primary_category'] ) {
		$data['sp_primary_category'] = QAMain_Core::get_product_primary_category_id( get_the_ID() );
	}

	$data['sp_stock_value'] = $product->get_stock_quantity() * $data['sp_cost'];

	$profit = (float) $product->get_price() - (float) $data['sp_cost'];

	$data['sp_mark_up'] = round( $profit / max( $data['sp_cost'], 0.01 ), 2 );

	$with_tax    = wc_get_price_including_tax( $product );
	$without_tax = wc_get_price_excluding_tax( $product );

	if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
		$with_tax    = $product->get_price_including_tax();
		$without_tax = $product->get_price_excluding_tax();
	}

	if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
		$with_tax    = 0;
		$without_tax = 0;
	}

	$tax_amount = $with_tax - $without_tax;
	$percent    = ( $tax_amount / max( $without_tax, 0.01 ) ) * 100;

	$data['sp_margin']     = round( $profit / max( $with_tax, 0.01 ) * 100, 2 ) . '%';
	$data['sp_margin_tax'] = round( ( (float) $product->get_price() - (float) $tax_amount - (float) $data['sp_cost'] ) / max( (float) $without_tax, 0.01 ) * 100, 2 ) . '%';

	woocommerce_wp_checkbox( array(
		'id'    => 'sp_activate_replenishment',
		'value' => ( $data['sp_activate_replenishment'] == '1' ) ? 'yes' : 'no',
		'label' => $settings['sp_activate_replenishment'],
	) );
	array_shift( $settings );

	$suppliers = [];
	foreach ( QAMain_Core::get_suppliers() as $row ) {
		$suppliers[ $row['id'] ] = $row['supplier_name'];
	}

	woocommerce_wp_select( array(
		'id'      => 'sp_supplier_id',
		'value'   => $data['sp_supplier_id'],
		'label'   => $settings['sp_supplier_id'],
		'options' => $suppliers,
	) );
	array_shift( $settings );

	foreach ( $settings as $key => $setting ) {
		if ( $key == 'sp_on_hold' ) {
			woocommerce_wp_select( array(
				'id'      => $key,
				'value'   => $data[ $key ],
				'label'   => $settings[ $key ],
				'options' => [ 'No', 'Yes' ],
			) );
			continue;
		}

		if ( $key == 'sp_primary_category' ) {
			woocommerce_wp_select( array(
				'id'      => $key,
				'value'   => $data[ $key ],
				'label'   => $settings[ $key ],
				'options' => QAMain_Core::get_all_categories(),
			) );
			continue;
		}

		woocommerce_wp_text_input( array(
			'id'    => $key,
			'value' => $data[ $key ],
			'label' => $setting,
			'type'  => ( strpos( $key, '_date' ) !== false ) ? 'date' : 'text',
		) );
	}

	echo '</div>';
}

/**
 * Save fields from product edit form
 */
add_action( 'woocommerce_process_product_meta', 'sp_main_save_product_settings', 10, 2 );
function sp_main_save_product_settings( $id, $post ) {
	global $wpdb;

	$_POST['sp_margin']                 = (float) $_POST['sp_margin'];
	$_POST['sp_margin_tax']             = (float) $_POST['sp_margin_tax'];
	$_POST['sp_cost']                   = (float) $_POST['sp_cost'];
	$_POST['sp_activate_replenishment'] = isset( $_POST['sp_activate_replenishment'] ) ? '1' : '0';

	$clean = [];
	foreach ( $_POST as $key => $value ) {
		if ( strpos( $key, 'sp_' ) !== false ) {
			$clean[ $key ] = esc_sql( $value );
		}
	}
	$clean['product_id'] = $id;

	$wpdb->replace( $wpdb->product_settings, $clean );
}

/**
 * Custom Cost price for variations - display field
 */
add_action( 'woocommerce_variation_options_pricing', 'sp_add_custom_field_to_variations', 10, 3 );
function sp_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
	woocommerce_wp_text_input( array(
		'id'    => 'variation_cost_price[' . $loop . ']',
		'class' => 'short wc_input_price',
		'style' => 'width: 100% !important',
		'label' => __( 'Variation Cost Price', SP_TEXT_DOMAIN ) . ' (' . get_woocommerce_currency_symbol() . ')',
		'value' => get_post_meta( $variation->ID, 'variation_cost_price', true )
	) );
}

/**
 * Custom Cost price for variations - save it
 */
add_action( 'woocommerce_save_product_variation', 'sp_save_custom_field_variations', 10, 2 );
function sp_save_custom_field_variations( $variation_id, $i ) {
	global $wpdb;

	if ( isset( $_POST['variation_cost_price'][ $i ] ) ) {
		$variation_id         = (int) $variation_id;
		$variation_cost_price = (float) $_POST['variation_cost_price'][ $i ];
		update_post_meta( $variation_id, 'variation_cost_price', $variation_cost_price );
		\QAMain_Core::get_product_settings( $variation_id );

		$wpdb->query( "UPDATE `{$wpdb->product_settings}` SET `sp_cost` = {$variation_cost_price} WHERE `product_id` = {$variation_id} limit 1" );
	}
}

/**
 * Custom Routing for Logs
 */
function sp_rewrites_init() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

//add_action( 'init', 'sp_rewrites_init' );

add_action( 'wp_ajax_sp-ajax', 'find_sp_ajax__ajax_callback' );
function find_sp_ajax__ajax_callback() {
	if ( isset( $_GET['bg'] ) ) {
		if ( sanitize_text_field( $_GET['bg'] ) == 'true' ) {
			update_option( 'sp.in_background', 'checked' );
		} else {
			update_option( 'sp.in_background', 'false' );
		}
	} elseif ( isset( $_GET['log'] ) ) {
		if ( sanitize_text_field( $_GET['log'] ) == 'true' ) {
			update_option( 'sp.log', 'checked' );
		} else {
			update_option( 'sp.log', 'false' );
		}
	} elseif ( isset( $_GET['sp-analyzed-orders-count'] ) ) {
		echo esc_html(ShelfPlannerCore::getAnalyzedOrdersCount());
	} elseif ( isset( $_GET['sp-total-orders-count'] ) ) {
		echo esc_html(ShelfPlannerCore::getOrdersCount());
	} elseif ( isset( $_GET['sp-chart'] ) ) {
		echo esc_html( (int) ( min( 100, ( ShelfPlannerCore::getAnalyzedOrdersCount() / max( 1, ShelfPlannerCore::getOrdersCount() ) * 100 ) ) ) );
	} else {
		wp_send_json( [
			'total'    => ShelfPlannerCore::getOrdersCount(),
			'analyzed' => ShelfPlannerCore::getAnalyzedOrdersCount(),
			'progress' => ShelfPlannerCore::getAnalyzedProgress(),
		] );
	}

	exit;
}

/**
 * Parse XLSX
 */
add_action( 'wp_ajax_sp-ajax-xlsx', 'find_sp_ajax_xlsx__ajax_callback' );
function find_sp_ajax_xlsx__ajax_callback() {
	global $wpdb;

	//require_once __DIR__ . '/includes/simple_xlsx.class.php';
	require_once __DIR__ . '/includes/core.php';

	if ( $_FILES && isset( $_FILES['excel'] ) ) {
		if ( $_FILES['excel']['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ) {
			wp_die( 'Error: unsupported file format. Try again please' );
		}

		$new_dataset_data = [];
		$xlsx_file        = $_FILES['excel']['tmp_name'];

		if ( $xlsx = SimpleXLSX::parse( $xlsx_file ) ) {
			foreach ( $xlsx->rows() as $line => $each_row ) {
				if ( 0 == $line ) {
					foreach ( $each_row as $k => $cell ) {
						if ( ! trim( $cell ) ) {
							continue;
						}
						$new_dataset_data['title'][ $k ] = esc_sql( $cell );
					}
				} else {
					foreach ( $each_row as $k => $cell ) {
						if ( $new_dataset_data['title'][ $k ] ) {
							$new_dataset_data['items'][ $line ][ $new_dataset_data['title'][ $k ] ] = esc_sql( $cell );
						}
					}
				}
			}
		} else {
			$error_str = SimpleXLSX::parseError();
		}

		if ( $new_dataset_data['items'] ) {
			foreach ( $new_dataset_data['items'] as $item ) {
				if ( ! $item['product_id'] ) {
					continue;
				}
				$wpdb->replace( $wpdb->product_settings, $item );
			}
		}

		echo "<center>Succesfully imported " . count( $new_dataset_data['items'] ) . " items. You can close this window.</center>";

		?>
        <script>
            // Reload the parent page to see the changes in dataset
            setTimeout(function () {
                parent.location.reload();
            }, 2000);
        </script>
		<?php
	}

	?>
    <div style="width:600px; height: 4em; padding-top: 5px; margin: auto; text-align: center;">
        <form action="<?php echo esc_url( admin_url( 'admin-ajax.php?action=sp-ajax-xlsx' ) ); ?>" method="POST" enctype="multipart/form-data">
            <p>
                <strong>Upload .XLSX file</strong> <select name="import_mode" style="display: none">
                    <option value="append" style="color: darkgreen">Append</option>
                    <option value="overwrite" style="color: darkred">Overwrite</option>
                </select>
            </p>
            <input type="file" name="excel" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/> <input type="submit" onclick="this.innerHTML = 'Loading...';">
        </form>
    </div>
	<?php
}

//Register post status for Backordered
function register_backordered_order_status() {
    register_post_status( 'wc-backordered', array(
        'label'                     => 'Backordered',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Backordered <span class="count">(%s)</span>', 'Backordered <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_backordered_order_status' );
// Add to list of WC Order statuses
function add_backordered_to_order_statuses( $order_statuses ) {
 
    $new_order_statuses = array();
 
    // add new order status after processing
    foreach ( $order_statuses as $key => $status ) {
 
        $new_order_statuses[ $key ] = $status;
 
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-backordered'] = 'Backordered';
        }
    }
 
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_backordered_to_order_statuses' );

/**
 * Create additional order for out of stock products
 */
$enable_backorder = get_option( 'sp.backorder', false );

if ( $enable_backorder ) {
	add_action( 'woocommerce_thankyou', 'create_outofstock_order' );
	add_action( 'add_meta_boxes', 'link_to_parent_or_child_order' );	
}

function create_outofstock_order( $order_id ) {

	//Order need to be updated
	$need_update = false;

	$order = new WC_Order( $order_id );

	$items = $order->get_items();

	$all_items_backordered = true;

	foreach ( $items as $id => $item ) {
		$product_id = $item->get_product_id();
		$product = wc_get_product($product_id);

		$order_qty = $item->get_quantity();
		$product_qty = $product->get_stock_quantity();

		if ( $product_qty < 0 ) {
			$need_update = true;
			$new_qty = $order_qty + $product_qty;
			if ( $new_qty >=0 ) {
				$all_items_backordered = false;
				$item->set_quantity( $new_qty );
				$out_items[] = [
					'product_id' => $id,
					'product' => $product,
					'qty' => abs( $product_qty ),
					'delete' => false
				];
			} else {
				$out_items[] = [
					'product_id' => $id,
					'product' => $product,
					'qty' => abs( $order_qty ),
					'delete' => true
				];				
			}
		} else {
			$all_items_backordered = false;
		}
	}

	if ( $need_update ) {
		//All items backordered
		if ( $all_items_backordered ) {
			$order->update_status( 'wc-backordered' );
			$order->save();
			return false;
		} else {
			foreach( $out_items as $item ) {
				if( $item['delete'] ) {
					wc_delete_order_item( $item['product_id'] );
				}
			}
		}
		
		$order->save();

		if ( empty( $out_items ) ) return false;

		$new_order = wc_create_order();

		foreach ( $out_items as $item ) {
			$new_order->add_product( $item['product'], $item['qty'] );
		}
		$new_order->set_customer_id( $order->get_customer_id() );
		$new_order->set_address( $order->get_address() );
		$new_order->calculate_totals();
		$new_order->update_status( 'wc-backordered' );
        $new_order->add_order_note("Parent Order ID #{$order_id}");
		$new_order_id = $new_order->save();

		// Add Note to Main Order
        $order->add_order_note("Child Order ID #{$new_order_id}");
        $order->save();

        // Add Meta Data
        update_post_meta($new_order_id, 'parent_order_id', $order_id);
        update_post_meta($order_id, 'child_order_id', $new_order_id);
	}
	
}

/**
 * Add Button to Parent or Child Backorder
 */
function link_to_parent_or_child_order()
{
    $post_id = isset($_GET['post']) ? $_GET['post'] : false;

    if(! $post_id ) return; // Exit

    $order = wc_get_order($post_id);

    if($order) {
        if(get_post_meta($post_id, 'child_order_id')) {
            add_meta_box( 'custom_order_meta_box', __( 'Child Backorder' ), 'content_child_link', 'shop_order', 'normal', 'default');
        } elseif(get_post_meta($post_id, 'parent_order_id')) {
            add_meta_box( 'custom_order_meta_box', __( 'Parent Order' ), 'content_parent_link', 'shop_order', 'normal', 'default');
        }
    }
}

/**
 * Link to child order
 *
 * @return void
 */
function content_child_link() {
    $post_id = isset($_GET['post']) ? sanitize_text_field( $_GET['post'] ) : false;

    if(! $post_id ) return; // Exit

    $child_id = get_post_meta($post_id, 'child_order_id', true);

    ?>
    <p><a target="_blank" class="button save_order button-primary" href="<?php echo esc_url(get_edit_post_link($child_id)) ?>" class="button"><?php _e('Go to Child Order'); ?></a></p>
    <?php
}

/**
 * Link to parent order
 *
 * @return void
 */
function content_parent_link() {
    $post_id = isset($_GET['post']) ? sanitize_text_field( $_GET['post'] ) : false;

    if(! $post_id ) return; // Exit

    $parent_id = get_post_meta($post_id, 'parent_order_id', true);

    ?>
    <p><a target="_blank" class="button save_order button-primary" href="<?php echo esc_url(get_edit_post_link($parent_id)) ?>" class="button"><?php _e('Go to Parent Order'); ?></a></p>
    <?php
}

function display_admin_part(){
	return ( get_option('sp.full_screen') ) ? false : true ;
}

// Initialize RestAPI
add_action('rest_api_init', 'initialize_rest_api_init', 99);

/**
 * Add Rest Routes for API
 */
function initialize_rest_api_init()
{
	register_rest_route('sp/v1', '/orders_sp', array(
		'methods' => 'POST',
		'callback' => 'retrieve_sp_orders'
	));
}

/**
 * Get Orders Details
 */
function retrieve_sp_orders(WP_REST_Request $request)
{
	global $wpdb;

	// Get Data from Request
	$data = $request->get_params();

	if(isset($data['orderLimit']) && isset($data['orderOffset'])) {

        // Get Orders by API request
        $args = [
            'post_status' => 'any',
            'post_type' => 'shop_order',
            'posts_per_page' => sanitize_text_field($data['orderLimit']),
            'offset' => sanitize_text_field($data['orderOffset']),
            'fields' => 'ids'
        ];
        $order_ids = get_posts($args);

        $sales_row = [];

        foreach ( $order_ids as $order_id ) {
            // Allow code execution only once
            if ( ! get_post_meta( $order_id, SP_META_KEY_PROCESSED, true ) || 1) {
                // Get an instance of the WC_Order object
                $order = wc_get_order( $order_id );

                $order->update_meta_data( SP_META_KEY_PROCESSED, date( 'd.m.Y H:i:s' ) );
                $order->save();

                if ( ! $order ) {
                    spApiLog( "Request denied: wrong order_id {$order_id}, not found", 'error' );
                    continue;
                }

                spApiLog( "Start Processing: order_id {$order_id} with " . count( $order->get_items() ) . ' item(s)' );

                // Loop through order items
                foreach ( $order->get_items() as $item_id => $item ) {
                    if ( ! method_exists( $order, 'get_date_paid' ) ) {
                        spApiLog( "Skip Non Order Item: {$order_id}" );
                        continue;
                    }

                    spApiLog( "Start Processing Order Item: order_id {$order_id}, product_id " . $item->get_product_id() );

                    $variation_id = $item->get_variation_id();
                    if ( ! empty( $variation_id ) ) {
                        spApiLog( "VARIATION FOUND: {$variation_id}, PARENT PRODUCT: " . $item->get_product_id() );
                    }

                    $product = new WC_Product( $item->get_product_id() );

                    if ( $product->get_status() != 'publish' ) {
                        continue;
                    }

                    /**
                     * Fill all the data in
                     */
                    $tmp                    = [];
                    $tmp['creation_date']   = date( "Y-m-d", strtotime( $order->get_date_created() ) );
                    $tmp['affiliate_id']    = str_replace( 'www.', '', ( $_SERVER['HTTP_HOST'] ? $_SERVER['HTTP_HOST'] : AFFILIATE_ID ) );
                    $tmp['order_id']        = $order_id;
                    $tmp['segment_id']      = 3;
                    $primary_category_id    = QAMain_Core::get_product_primary_category_id( $item->get_product_id() );
                    $tmp['raw_category_id'] = empty( $primary_category_id ) ? 0 : $primary_category_id;

                    $date_payed = $order->get_date_paid();
                    if ( $date_payed ) {
                        $date_payed = $date_payed->getTimestamp();
                    }
                    if ( ! $date_payed ) {
                        $date_payed = $order->get_date_created()->getTimestamp();
                    }
                    $tmp['order_date'] = $tmp['shipping_date'] = date( "Y-m-d", $date_payed );

                    $tmp['product_stock'] = $product->get_stock_quantity();
                    if ( ! empty( $variation_id ) ) {
                        $tmp['product_id'] = $variation_id;
                    } else {
                        $tmp['product_id'] = $item->get_product_id();
                    }
                    $tmp['product_creation_date'] = date( "Y-m-d", strtotime( $product->get_date_created() ) );
                    $tmp['product_sku']           = $product->get_sku();

                    if ( $item->get_variation_id() ) {
                        $tmp['product_options'] = [ $item->get_variation_id() ];
                    } else {
                        $tmp['product_options'] = [];
                    }
                    $tmp['product_options']          = "";
                    $tmp['product_strong_option1']   = 0;
                    $tmp['product_strong_option2']   = 0;
                    $tmp['product_strong_option3']   = 0;
                    $tmp['product_strong_option4']   = 0;
                    $tmp['product_quantity_ordered'] = $item->get_quantity();

                    $tmp['product_cost_price']     = sp_get_cost_price( $tmp['product_id'] );
                    $tmp['product_original_price'] = (string) floatval( $product->get_regular_price() );
                    $tmp['product_final_price']    = (string) floatval( $product->get_price() );

                    $with_tax    = wc_get_price_including_tax( $product );
                    $without_tax = wc_get_price_excluding_tax( $product );

                    if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
                        $with_tax    = $product->get_price_including_tax();
                        $without_tax = $product->get_price_excluding_tax();
                    }

                    if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
                        $with_tax    = 0;
                        $without_tax = 0;
                    }

                    $tax_amount         = $with_tax - $without_tax;
                    $tmp['product_vat'] = round( ( $tax_amount / max( $without_tax, 0.01 ) ) * 100, 1 );

                    $shipping_class_id = $product->get_shipping_class_id();
                    $shipping_class    = $product->get_shipping_class();
                    $fee               = 0;
                    if ( $shipping_class_id ) {
                        $flat_rates = get_option( "woocommerce_flat_rates" );
                        $fee        = ( is_array( $flat_rates ) ) ? $flat_rates[ $shipping_class ]['cost'] : 0;
                    }
                    $flat_rate_settings = get_option( "woocommerce_flat_rate_settings" );

                    $flat_rate_cost = ( is_array( $flat_rate_settings ) ) ? $flat_rate_settings['cost_per_order'] : 0;

                    $tmp['product_shipping_price'] = $flat_rate_cost + $fee;
                    $tmp['order_grandtotal']       = $order->get_total();
                    $tmp['order_discount']         = $order->get_total_discount();

                    $tmp['shipping_country'] = addslashes( $order->get_shipping_country() );
                    $tmp['shipping_town']    = addslashes( $order->get_shipping_city() );
                    $tmp['billing_country']  = addslashes( $order->get_billing_country() );
                    $tmp['billing_town']     = addslashes( $order->get_billing_city() );

                    // We need string here, not array! API expects string with commas inside!
                    $tmp['industry_id']            = sp_get_industry_id();
                    $tmp['normalized_category_id'] = sp_get_normalized_category_id( $tmp['industry_id'] );

                    $product_id             = $item->get_product_id();
                    $primary_category_id    = \QAMain_Core::get_product_primary_category_id( $product_id );
                    $tmp['raw_category_id'] = empty( $primary_category_id ) ? 0 : $primary_category_id;

                    $tmp['industry_id'] = \QAMain_Core::get_industry_by_category( $tmp['raw_category_id'] );

                    $tmp['normalized_category_id'] = sp_get_normalized_category_id( $tmp['industry_id'] );
                    $tmp['affiliate_country'] = strtoupper( get_option( 'sp.settings.country' ) );

                    spApiLog( "[IMPORTANT] Product #{$product_id} - normalized category ID is {$tmp['normalized_category_id']}" );

                    if ( $order->get_total() <= 0 || $order->get_item_count() <= 0 ) {
                        continue;
                    }

                    $sales_row[] = $tmp;
                }
            } else {
                spApiLog( "Order Ignored: order_id {$order_id}, it already has " . SP_META_KEY_PROCESSED . " meta key", 'notice' );
            }
        }

        http_response_code(200);
        $response = [
            'SalesRow' => $sales_row
        ];
	} else {
		http_response_code(403);
		$response = [
			'success' => 'false',
			'message' => 'Missing required field(s)'
		];
	}

	return $response;
}

add_shortcode( 'sp_stock' , 'sp_get_less_stock' );

if ( !function_exists( 'sp_get_less_stock' ) ) {
	/**
	 * Get product with less stoks
	 */
	function sp_get_less_stock( $atts ) {
		$count = $atts['count'] ?? 5;
		$title = $atts['title'] ?? 'Last stocks';
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $count,
			'meta_key' => '_stock', 
			'orderby' => 'meta_value_num'
		);
		
		$products = new WP_Query( $args );
		$result = '<h2>'.$title.'</h2><ul style="width:100%;margin:0 auto;display:flex;flex-wrap:wrap;gap:1rem;list-style:none;" class="less-stock">';
		
		if ( $products->posts ) {
			foreach ( $products->posts as $item ) {
				$value = wc_get_product( $item->ID );
				$name  = $value->get_name();
				$price = $value->get_price_html();
				$link =  get_permalink( $value->get_id() );
				$image = get_the_post_thumbnail_url( $value->get_id(), 'thumbnail' );
				$result .= "<li><a style='display:flex;flex-direction:column;justify-content:space-between;max-height:400px;' href='".$link."' style='display:block;'><img src=".$image." alt=".$name."><p>".$name."</p>".$price."</a></li>";
			}
		}
		$result .= '</ul>';
		return $result;
	}
}
