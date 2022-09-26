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
            <?php include __DIR__ . '/../' . "page_header.php"; ?>
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

.wp-core-ui select {
  max-width: 50%;
  color: #000000;
  opacity: 0.6;
  border: 1px solid #707070;
}

.wp-core-ui select:hover, .wp-core-ui select:focus {
  color: #000000;
  opacity: 0.6;
  border: 1px solid #707070;
  outline: none;
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
										<?php echo esc_attr( ( get_option( 'sp.settings.force_zero_price_products', true ) ? ' checked="checked"' : '' ) ); ?>> <label for="id-force-zero-price-products" style="font-weight: normal"> <?php echo esc_html( __( 'Include Items w/o Cost Price In All Reports', QA_MAIN_DOMAIN ) ); ?></label></p>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="btn-save-set" value="<?php echo esc_html( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-forecast-settings"/>
                            </form>

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
                                <h4 class="purchase-or-title mb-1"><?php echo esc_html( __( 'Category Mapping', QA_MAIN_DOMAIN ) ); ?></h4>
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
                        <?php include __DIR__ . '/../' . "popups.php"; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
