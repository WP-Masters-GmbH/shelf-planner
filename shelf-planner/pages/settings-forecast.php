<?php

global $wpdb;

if ( ! empty( $_POST ) ) {
	if ( isset( $_POST['save-forecast-settings'] ) ) {
		update_option( 'sp.settings.force_zero_price_products', intval( isset( $_POST['force_zero_price_products'] ) && strtolower( $_POST['force_zero_price_products'] ) === 'on' ) );

		$woocommerce_currency = get_woocommerce_currency();
		$default = sanitize_text_field($_POST['default_currency']);

		if($woocommerce_currency != $default) {
			$response = wp_remote_post("https://api.exchangerate.host/convert?from={$woocommerce_currency}&to={$default}", [
				'method'  => 'GET',
				"timeout" => 100,
				'headers' => [
					"Accept" => "application/json"
				]
			]);
			$currencies = json_decode($response['body'], true);

			update_option( 'sp.currency_rate', isset($currencies['result']) ? $currencies['result'] : 1);
        }

		update_option( 'sp.rate_add', ( isset( $_POST['rate_add'] ) ) ? sanitize_text_field(str_replace(' ', '', $_POST['rate_add'])) : 0 );
		update_option( 'sp.default_currency', ( isset( $_POST['default_currency'] ) ) ? sanitize_text_field(str_replace(' ', '', $_POST['default_currency'])) : false );
        update_option( 'sp.full_screen', ( isset( $_POST['full_screen'] ) ) ? true : false );

		$default_weeks_of_stock = sanitize_text_field( $_POST['default-weeks-of-stock'] );

		if ( is_numeric( $default_weeks_of_stock ) && $default_weeks_of_stock > 0 ) {
			update_option( 'sp.settings.default_weeks_of_stock', $default_weeks_of_stock = (int) $default_weeks_of_stock );
			$wpdb->query( "UPDATE `{$wpdb->product_settings}`
                SET `sp_weeks_of_stock` = {$default_weeks_of_stock}
                WHERE `sp_weeks_of_stock` = 0" );
		}

		$default_lead_time = sanitize_text_field( $_POST['default-lead-time'] );
		if ( is_numeric( $default_lead_time ) && $default_lead_time > 0 ) {
			update_option( 'sp.settings.default_lead_time', $default_lead_time = (int) $default_lead_time );
			$wpdb->query( "UPDATE `{$wpdb->product_settings}`
                SET `sp_lead_time` = {$default_lead_time}
                WHERE `sp_lead_time` = 0" );
		}
	}
}

$currencies = get_woocommerce_currencies();
$default_currency = get_option( 'sp.default_currency', true);
$rate = get_option( 'sp.currency_rate', true);
$rate_add = get_option( 'sp.rate_add') ? get_option( 'sp.rate_add', true) : 0;

require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';

?>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
		<?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">
                <!-- container opened -->
                <div class="container">
	                <?php include SP_PLUGIN_DIR_PATH ."pages/header_js.php"; ?>
                    <style>
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }

                        .sp-settings-forecast-table {
                            width: 60%;
                        }

                        .sp-settings-forecast-table td {
                            padding-bottom: 2em;
                        }
                    </style>
                    <h2><?php echo esc_html( __( 'Settings', QA_MAIN_DOMAIN ) ); ?></h2>
                    <?php do_action( 'after_page_header' ); ?>
                    <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div class="card">
                        <div class="card-body">
                            <h4><?php echo esc_html( __( 'Forecast Settings', QA_MAIN_DOMAIN ) ); ?></h4>
                            <p class="mg-b-20"></p>
                            <form method="post">
                                <table class="sp-settings-forecast-table">
                                    <tr>
                                        <td style="width: 60%;"><?php echo esc_html( __( 'Default Weeks of Stock', QA_MAIN_DOMAIN ) ); ?>
                                        </td>
                                        <td>
                                            <input type="number" name="default-weeks-of-stock" value="<?php echo  esc_attr( get_option( 'sp.settings.default_weeks_of_stock', 6 ) ) ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html( __( 'Default Lead Time', QA_MAIN_DOMAIN ) ); ?></td>
                                        <td>
                                            <input type="number" name="default-lead-time" value="<?php echo  esc_attr( get_option( 'sp.settings.default_lead_time', 1 ) ) ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html( __( 'Convert stats from '.get_woocommerce_currency().' to', QA_MAIN_DOMAIN ) ); ?></td>
                                        <td>
                                            <select name="default_currency" id="default_currency">
                                                <?php foreach($currencies as $code => $currency) { ?>
                                                    <option value="<?php echo esc_attr($code); ?>" <?php if($code == $default_currency) { echo esc_attr('selected'); } ?>><?php echo esc_attr($currency); ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html( __( 'Exchange rates (auto-refresh after save)', QA_MAIN_DOMAIN ) ); ?></td>
                                        <td>
                                            <input type="text" value="<?php echo esc_attr($rate); ?>" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html( __( 'Add rate multiplier (positive or negative value)', QA_MAIN_DOMAIN ) ); ?></td>
                                        <td>
                                            <input type="number" name="rate_add" value="<?php echo esc_attr($rate_add); ?>" step="0.01">
                                        </td>
                                    </tr>
                                </table>
                                <p class="mg-b-20"></p>
                                <p style="font-size: inherit"><input type="checkbox" id="id-force-zero-price-products" name="force_zero_price_products"
										<?php echo esc_attr( ( get_option( 'sp.settings.force_zero_price_products', true ) ? ' checked="checked"' : '' ) ); ?>> <label for="id-force-zero-price-products" style="font-weight: normal"> <?php echo esc_html( __( 'Add Force include products with zero cost price?', QA_MAIN_DOMAIN ) ); ?></label></p>
                                <p class="mg-b-20"></p>
                                <p style="font-size: inherit"><input type="checkbox" id="id-full-screen" name="full_screen"
										<?php echo esc_attr( ( get_option( 'sp.full_screen', false ) ? ' checked="checked"' : '' ) ); ?>> <label for="id-full-screen" style="font-weight: normal"> <?php echo esc_html( __( 'Enable full screen mode', QA_MAIN_DOMAIN ) ); ?></label></p>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="btn btn-sm btn-success" value="<?php echo esc_html( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-forecast-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>