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
        //update_option( 'sp.full_screen', ( isset( $_POST['full_screen'] ) ) ? true : false );

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

if ( ! empty( $_POST ) && isset( $_POST['save-store-settings'] ) ) {
	// Sanitized with json_decode
	$js_category_mapping = stripslashes( $_POST['category_mapping'] );
	$category_mapping    = @json_decode( $js_category_mapping, true );
	if ( is_array( $category_mapping ) && ! empty( $category_mapping ) ) {
		update_option( 'sp.category.mapping', $js_category_mapping );
	}
}

// Sanitized with json_decode
$js_category_mapping = get_option( 'sp.category.mapping', '{}' );
$category_mapping    = json_decode( $js_category_mapping, true );

$tmpl_industry_column = '<div class="column column-done" style="" ondrop="drop(event)" ondragover="allowDrop(event)" data-id="%s"><h5>%s</h5>%s</div>';
$tmpl_industry_card   = '<article class="js-card" draggable="true" style="text-align: center; border-radius: 5px;" ondragstart="drag(event)" data-id="%s">%s</article>';
$industry_columns     = $industry_cards = array();

$industry_list = \QAMain_Core::get_industry_categories();
array_pop( $industry_list );
foreach ( $industry_list as $each_id => $each_industry ) {
	$industry_columns[ $each_id ] = sprintf( $tmpl_industry_column, esc_attr( $each_id ), esc_html( $each_industry ), '%s' );
	$industry_cards[ $each_id ]   = array();
}

$tmp_cats = \QAMain_Core::get_all_categories();
foreach ( $tmp_cats as $each_id => $each_category ) {
	if ( array_key_exists( $each_id, $category_mapping ) ) {
		$industry_cards[ $category_mapping[ $each_id ] ][] = sprintf( $tmpl_industry_card, esc_attr( $each_id ), esc_html( $each_category ) );
		unset( $tmp_cats[ $each_id ] );
	}
}

foreach ( $industry_columns as $each_id => &$each_column ) {
	$each_industry = empty( $industry_cards[ $each_id ] ) ? '' : implode( "\n", $industry_cards[ $each_id ] ); // Escaped in previous foreach
	$each_column   = sprintf( $each_column, $each_industry );
}

require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';

?>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
		<?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">
            <div class="plugin-header">
              <svg xmlns="http://www.w3.org/2000/svg" width="21.015" height="24.017" viewBox="0 0 21.015 24.017"><path d="M20.61,16.994c-.906-.974-2.6-2.439-2.6-7.237a7.407,7.407,0,0,0-6-7.278V1.5a1.5,1.5,0,1,0-3,0v.978a7.407,7.407,0,0,0-6,7.278c0,4.8-1.7,6.264-2.6,7.237A1.466,1.466,0,0,0,0,18.013a1.5,1.5,0,0,0,1.506,1.5h18a1.5,1.5,0,0,0,1.506-1.5,1.465,1.465,0,0,0-.4-1.018Zm-17.443.268c1-1.312,2.084-3.487,2.089-7.478,0-.009,0-.018,0-.027a5.254,5.254,0,1,1,10.507,0c0,.009,0,.018,0,.027.005,3.992,1.093,6.166,2.089,7.478Zm7.34,6.755a3,3,0,0,0,3-3h-6A3,3,0,0,0,10.507,24.017Z" transform="translate(0.001)" fill="rgba(0,0,0,0.6)"/></svg>
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="27" height="27" viewBox="0 0 27 27"><defs><pattern id="a" preserveAspectRatio="xMidYMid slice" width="100%" height="100%" viewBox="0 0 50 50"><image width="50" height="50" xlink:href="data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABLAAD/4QRbaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAxNCA3OS4xNTE0ODEsIDIwMTMvMDMvMTMtMTI6MDk6MTUgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpGOTdGMTE3NDA3MjA2ODExODhDNkEyQTU3QTlFMEVBRSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo3QTlGNTIzMzc5OUYxMUU0QkYxOTk4OTM2ODczRDIxNCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo3QTlGNTIzMjc5OUYxMUU0QkYxOTk4OTM2ODczRDIxNCIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OTVkNGUzYmItMTNiYS1jOTRhLWEwMTYtOWI4ZjBjZTkwNzU5IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjAxODAxMTc0MDcyMDY4MTE4MDgzQTI5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"/></pattern></defs><circle cx="13.5" cy="13.5" r="13.5" fill="url(#a)"/></svg>
              </div>
                <!-- container opened -->
                <div class="ml-40 mr-40">
              <?php include SP_PLUGIN_DIR_PATH ."pages/header_js.php"; ?>
                    <style>

                        input[type=number] {
                          width: 65px;
                          padding-left: 27px;
                          padding-right: 0;
                          height: 23px;
                          border: 1px solid #707070;
                          font-family: "Lato";
                          font-weight: 400;
                          font-size: 12px;
                          line-height: 22px;
                          color: #000;
                          opacity: 0.7;
                        }
  .td-set-forecast {
    font-family: "Lato";
    font-weight: 400;
    font-size: 16px;
    line-height: 22px;
    color: #000;
    opacity: 0.7;
  }
.line {
  border-top: 1px solid #A5A5A5;
  width: 100%;
  margin: 50px 0;
}
                    </style>
                    <h2 class="purchase-or-title"><?php echo esc_html( __( 'Settings', QA_MAIN_DOMAIN ) ); ?></h2>
                    <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Here you can manage general settings for your store, forecast, orders, etc.', QA_MAIN_DOMAIN )); ?></span>
                    <div class="d-flex nav-link-line" style="margin-top: 40px;">
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_store' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_store')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Store Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_forecast' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_forecast')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Forecast Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_product' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_product')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Product Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_po' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_po')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('PO Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_backorder' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_backorder')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Backorder', QA_MAIN_DOMAIN)); ?></span></a>
                    </div>
                    <?php do_action( 'after_page_header' ); ?>
                    <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div>
                        <div class="card-body" style="margin-top: 0px; padding-left: 0 !important;">
                            <h4 class="purchase-or-title"><?php echo esc_html( __( 'Forecast Settings', QA_MAIN_DOMAIN ) ); ?></h4>
                            <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Forecast Settings are the parameters that help to calculate the right order proposals.', QA_MAIN_DOMAIN )); ?></span>
                            <p class="mg-b-20"></p>
                            <form method="post">
                                <table class="sp-settings-forecast-table">
                                    <tr>
                                        <td style="width: 45%;" class="td-set-forecast">
                                        <?php echo esc_html( __( 'Default Weeks of Stock', QA_MAIN_DOMAIN ) ); ?>
                                        <svg class="quest" xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><g transform="translate(-34)"><g transform="translate(34)"><circle cx="5" cy="5" r="5" fill="#131313"/><text transform="translate(3 8)" fill="#fff" font-size="8" font-family="Lato-Regular, Lato"><tspan x="0" y="0">?</tspan></text></g></g></svg>                                        </td>
                                        <td class="td-set-forecast">
                                            <input type="number" name="default-weeks-of-stock" value="<?php echo  esc_attr( get_option( 'sp.settings.default_weeks_of_stock', 6 ) ) ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-set-forecast"><?php echo esc_html( __( 'Default Lead Time', QA_MAIN_DOMAIN ) ); ?>
                                        <svg class="quest-sec" xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><g transform="translate(-34)"><g transform="translate(34)"><circle cx="5" cy="5" r="5" fill="#131313"/><text transform="translate(3 8)" fill="#fff" font-size="8" font-family="Lato-Regular, Lato"><tspan x="0" y="0">?</tspan></text></g></g></svg>                                        </td>

                                      </td>
                                        <td class="td-set-forecast">
                                            <input type="number" name="default-lead-time" value="<?php echo  esc_attr( get_option( 'sp.settings.default_lead_time', 1 ) ) ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="td-set-forecast"><?php echo esc_html( __( 'Convert stats from '.get_woocommerce_currency().' to', QA_MAIN_DOMAIN ) ); ?></td>
                                        <td class="td-set-forecast">
                                            <select name="default_currency" id="default_currency">
                                                <?php foreach($currencies as $code => $currency) { ?>
                                                    <option value="<?php echo esc_attr($code); ?>" <?php if($code == $default_currency) { echo esc_attr('selected'); } ?>><?php echo esc_attr($currency); ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html( __( 'Exchange rates (auto-refresh after save)', QA_MAIN_DOMAIN ) ); ?></td>
                                        <td class="td-set-forecast">
                                            <input type="text" value="<?php echo esc_attr($rate); ?>" readonly style="width: 65px; text-align: center; font-family: Lato; font-weight: 400; font-size: 12px; line-height: 22px; color: #000; opacity: 0.7;" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo esc_html( __( 'Add rate multiplier (positive or negative value)', QA_MAIN_DOMAIN ) ); ?></td>
                                        <td class="td-set-forecast">
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
                                <input style="margin-top: 2em" type="submit" class="btn-save-set" value="<?php echo esc_html( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-forecast-settings"/>
                            </form>
                            <div class="d-flex align-items-center">
                              <div style="margin-right: 100px">
                                <h2 class="purchase-or-title"><?php echo esc_html(__( 'Include Items w/o Cost Price', QA_MAIN_DOMAIN )); ?></h2>
                                <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Include items without a cost price in all reports. ', QA_MAIN_DOMAIN )); ?></span>
                              </div>
                              <label class="switch" style="margin-top: 64px;"><input type="checkbox"><span class="slider round"></span></label>
                            </div>
                            <div class="line"></div>
                            <div style="max-width: 100%;">
                    <style>
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }

                        .sphd-p {
                            font-size: 16px;
                        }

                        .board {
                            font-size: 12px;

                            display: flex;
                            flex-wrap: wrap;

                            /*flex-basis: available;*/

                            align-items: stretch;
                            align-content: flex-start;

                            overflow-y: hidden;
                            width: 100%;

                            margin-top: 10px;
                            margin-left: 0;

                            height: 100%;
                        }

                        .column {
                            padding: 10px;
                            background: #ebebeb;
                            border: 1px dotted #bbb;
                            border-collapse: collapse !important;
                            min-width: 221.6px;

                            display: inline;
                            position: relative;

                            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
                            margin: 5px;
                            -webkit-box-shadow: 3px 3px 10px 3px #eee;
                            box-shadow: 3px 3px 10px 3px #eee;
                        }

                        .js-card {
                            background: #f7f7f7;
                            padding: 10px;
                            margin-bottom: 10px;
                            border-radius: 3px;
                            cursor: pointer;
                            width: 200px;
                            /*height: 60px;*/
                            cursor: grab;
                            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
                        }

                        .js-card:active {
                            cursor: grabbing;
                        }

                        .js-card.dragging {
                            opacity: .5;
                            transform: scale(.8);
                        }

                        .column h5 {
                            font-size: 12px;
                            font-weight: bold;
                            text-align: center;
                        }

                        .column.column-todo h2 {

                        }

                        .column.column-ip h2 {
                            background: #F39C12;
                        }

                        .column.column-ip {
                            margin: 0 20px;
                        }

                        .column.drop {
                            border: 2px dashed #FFF;
                        }

                        .column.drop article {
                            pointer-events: none;
                        }

                        .js-card:last-child {
                            margin-bottom: 0;
                        }
                    </style>
                    <?php do_action( 'after_page_header' ); ?>
                    <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div>
                        <form class="card-body card-body-form" method="post" style="padding: 0 !important;">
                            <div>
                                <h4 class="purchase-or-title mb-0"><?php echo esc_html( __( 'Category Mapping', QA_MAIN_DOMAIN ) ); ?></h4>
                                <div class="flex-gap-25">
                                  <p class="purchase-or-subtitle mb-0"><?php echo esc_html( __( 'To understand the patterns and performance of your products, we need to link your store’s categories to predefined segments and industries.', QA_MAIN_DOMAIN ) ); ?></p>
                                  <p class="purchase-or-subtitle mb-0"><?php echo esc_html( __( 'Please map your store’s categories to the segment that fits best, so we can create the perfect forecast and reports for your products.', QA_MAIN_DOMAIN ) ); ?></p>
                                  <p class="purchase-or-subtitle mb-0"><?php echo esc_html( __( "If you are unsure about what segment to assign a category to, or if you don’t find it in the list below, you can assign it to 'Other' and we will match it for you.", QA_MAIN_DOMAIN ) ); ?></p>
                                </div>
                                <input style="margin-top: 40px;" type="submit" class="btn-save-set mb-20" value="Save Settings" name="save-store-settings"> <input type="hidden" id="category-mapping" name="category_mapping" value="">
                            </div>
                            <main class="board">
                                <div class="column column-todo" ondrop="drop(event)" ondragover="allowDrop(event)" data-id="-1">
                                    <h5>Other</h5>
									<?php
									foreach ( $tmp_cats as $tmp_cat_id => $tmp_cat_title ): ?>
                                        <article class="js-card" draggable="true" ondragstart="drag(event)" style="text-align: center; border-radius: 5px" data-id="<?php echo esc_attr( (int) $tmp_cat_id ); ?>"><?php echo  esc_html( $tmp_cat_title ) ?></article>
									<?php endforeach; ?>
                                </div>
								<?php echo  implode( "\n", $industry_columns ) ?>
                            </main>
                            <script>
                                let category_mapping = <?php echo  json_encode( $category_mapping ) ?>;
                            </script>
                            <input style="margin-top: 2em" type="submit" class="btn-save-set" value="Save Settings" name="save-store-settings"/>
                        </form>
                    </div>
                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
