<?php
require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';

global $current_user; wp_get_current_user();

function sp_calc_stock_analyses() {
	global $wpdb;

	$category_id = isset( $_GET['category'] ) && is_numeric( $_GET['category'] ) ? (int) $_GET['category'] : false;

	$categories    = sp_get_categories();
	$products_data = sp_get_products_data_home( $category_id > 0 ? $category_id : implode( ',', array_keys( $categories ) ) );

	$category_fields = array(
		'count_of_products'    => 0,
		'ideal_stock'          => 0,
		'current_stock'        => 0,
		'inbound_stock'        => 0,
		'order_proposal_units' => 0,
		'order_value_cost'     => 0,
		'order_value_retail'   => 0,
		'weeks_to_stock_out'   => 0,
		'sales_l4w'            => 0,
		'sales_n4w'            => 0,
	);

	foreach ( $categories as $category_id => &$categories_item ) {
		$categories_item = array(
			'term_id' => $category_id,
			'name'    => htmlspecialchars_decode( $categories_item ),
			'cat_url' => get_term_link( (int) $category_id, 'product_cat' )
		);
		$categories_item = array_merge( $categories_item, $category_fields );
	}

	foreach ( $products_data as $product_id => $product_item ) {
		if ( $product_item['sp_primary_category'] == 0 ) {
			$category_id = \QAMain_Core::get_product_primary_category_id( $product_id );
			$wpdb->update( $wpdb->product_settings, array( 'sp_primary_category' => $category_id ), array( 'product_id' => $product_item['term_id'] ) );
		} else {
			$category_id = $product_item['sp_primary_category'];
		}

		$group_by = &$categories[ $category_id ];

		$group_by['ideal_stock']          += intval( $product_item['ideal_stock'] );
		$group_by['current_stock']        += intval( $product_item['current_stock'] );
		$group_by['inbound_stock']        += intval( $product_item['inbound_stock'] );
		$group_by['order_proposal_units'] += intval( $product_item['order_proposal_units'] );
		$group_by['weeks_to_stock_out']   += intval( $product_item['weeks_to_stock_out'] );
		$group_by['sales_l4w']            += intval( $product_item['sales_l4w'] );
		$group_by['sales_n4w']            += floatval( $product_item['sales_n4w'] );

		$group_by['order_value_cost']   += floatval( $product_item['order_value_cost'] );
		$group_by['order_value_retail'] += floatval( $product_item['order_value_retail'] );

		$group_by['count_of_products'] ++;
	}

	foreach ( $categories as &$category_item ) {
		$category_item['order_value_cost']   = sp_get_price( $category_item['order_value_cost'] );
		$category_item['order_value_retail'] = sp_get_price( $category_item['order_value_retail'] );
		$category_item['weeks_to_stock_out'] = floor( $category_item['weeks_to_stock_out'] / max( $category_item['count_of_products'], 1 ) );
		$category_item['sales_n4w']          = round( $category_item['sales_n4w'], 1 );
	}

	return array(
		array_values( $products_data ),
		array_values( $categories )
	);
}

/**
 * Products table data
 */
list ( $products_data, $categories_data ) = sp_calc_stock_analyses();

$current_forecast_units = 0;
$current_forecast_value = 0;
foreach($products_data as $item_product) {
	$current_forecast_units += $item_product['this_week'];
	$current_forecast_value += $item_product['cost_price'] * $item_product['this_week'];
}

$categories    = sp_get_categories();
$products_data_last_year = sp_get_products_data_home_last_year( implode( ',', array_keys( $categories ) ), array(), 'current');

$last_year_forecast_units = 0;
$last_year_forecast_value = 0;
foreach($products_data_last_year as $item_product) {
	$last_year_forecast_units += $item_product['this_week'];
	$last_year_forecast_value += $item_product['cost_price'] * $item_product['this_week'];
}

$percent_compare_units = 0;
$percent_compare_value = 0;
if($last_year_forecast_units > 0) {
	$percent_compare_units = $last_year_forecast_units > $current_forecast_units ? ($last_year_forecast_units / $current_forecast_units) * 100 : ($current_forecast_units / $last_year_forecast_units) * 100;
	$percent_compare_units = round($percent_compare_units);
}
if($last_year_forecast_value > 0) {
	$percent_compare_value = $last_year_forecast_value > $current_forecast_value ? ($last_year_forecast_value / $current_forecast_value) * 100 : ($last_year_forecast_value / $current_forecast_value) * 100;
	$percent_compare_value = round($percent_compare_value);
}

?> 
<style>
  .card-tab {
    border: 1px solid #ccd0d4 !important;
    padding: 20px 30px !important;
  }

  .new-des-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    color: #FFF !important;
    font-family: "Lato";
    font-weight: 700;
    font-size: 14px;
    line-height: 20px;
    border: none;
    background: #F98AB1;
    height: 36px;
    border-radius: 4px;
  }

  .wp-core-ui select {
    max-width: 100%;
    font-weight: 500;
  }

  .wp-core-ui select:hover {
    color: #B5B5B5;
    outline: none;
  }

  .new-des-btn {
    box-shadow: none !important;
    outline: none !important;
  }
</style>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
        <?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">
            <?php include __DIR__ . '/../' . "page_header.php"; ?>
                <?php include SP_PLUGIN_DIR_PATH . "pages/header_js.php"; ?>
                <!-- container opened -->
                <div class="ml-40 mr-40">
                    <h2 class="purchase-or-title fs-22 lh-28 mb-0"><?php echo esc_html(__( 'Good Morning ', QA_MAIN_DOMAIN )); ?><?php if(isset($my_account_settings['first_name'])) { echo esc_html($my_account_settings['first_name']); } ?></h2>
                    <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Your data is synced. ', QA_MAIN_DOMAIN )); ?></span>
                    <div class="d-flex nav-link-line" style="margin-top: 40px;">
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Home', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_retail_insights' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_retail_insights')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Store Perfomance', QA_MAIN_DOMAIN)); ?></span></a>
                        </div>
                    <?php do_action('after_page_header'); ?>
                    <p class="mg-b-20"></p>
                <div class="card-body d-flex card-body-home table-first-flex" style="padding-left: 0 !important;">
                    <div class="card-tab">
                        <div class="woocommerce-dashboard__columns" style="display: block;">
                            <div class="woocommerce-card woocommerce-analytics__card woocommerce-table has-action">
                                <div class="woocommerce-card__body">
                                    <div class="woocommerce-table__table" aria-hidden="false" aria-labelledby="caption-7" role="group">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <th role="columnheader" scope="col" class="woocommerce-table__header is-left-aligned"><span aria-hidden="false">Top Items to Replenish</span></th>
                                                <th role="columnheader" scope="col" class="woocommerce-table__header"><span aria-hidden="false">Stock</span></th>
                                                <th role="columnheader" scope="col" class="woocommerce-table__header"><span aria-hidden="false">Replenish</span></th>
                                                <th role="columnheader" scope="col" class="woocommerce-table__header"><span aria-hidden="false">Cost</span></th>
                                                <th role="columnheader" scope="col" class="woocommerce-table__header"><span aria-hidden="false">Retail</span></th>
                                            </tr>
                                            <?php

                                            usort($products_data, function($a, $b) {
	                                            return $b['order_proposal_units'] - $a['order_proposal_units'];
                                            });

                                            foreach($products_data as $number => $item) { if($number == 10) { break; } ?>
                                            <tr>
                                                <th scope="row" class="woocommerce-table__item is-left-aligned">
                                                    <div><a title="To Product Page" href=<?php echo esc_attr($item['cat_url']) ?>><?php echo esc_html($item['name']); ?></a></div>
                                                </th>
                                                <td class="woocommerce-table__item">
                                                    <div><?php echo esc_html($item['current_stock']); ?></div>
                                                </td>
                                                <td class="woocommerce-table__item">
                                                    <div><?php echo esc_html($item['order_proposal_units']); ?></div>
                                                </td>
                                                <td class="woocommerce-table__item">
                                                    <div><?php echo get_woocommerce_currency_symbol(); ?><?php echo esc_html(number_format($item['order_proposal_units'] * $item['order_value_cost'], 2)) ?></div>
                                                </td>
                                                <td class="woocommerce-table__item">
                                                    <div><?php echo get_woocommerce_currency_symbol(); ?><?php echo esc_html(number_format($item['order_proposal_units'] * $item['order_value_retail'], 2)) ?></div>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                      <a href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_order_proposals')); ?>" class="new-des-btn fs-14 lh-16" style="width: 166px; margin-top: 20px; margin-left: auto;">
                        Replenishment Report
                      </a>
                </div>
                    <div class="card-tab h-100">
                        <label class="card-tab-label fs-14">
                            Date Range:
                            <select id="replenish-stat-selector" class="card-tab-select">
                                <option value="this_week">This Week</option>
                                <option value="next_week">Next Week</option>
                                <option value="next_4_weeks">Next 4 Weeks</option>
                                <option value="next_8_weeks">Next 8 Weeks</option>
                            </select>
                        </label>
                        <table class="card-tab-second-table" id="replenish-table-stat">
                            <tr>
                                <td class="forecast-tab">
                                    Forecast Units
                                </td>
                                <td class="forecast-numbers">
                                    <?php echo esc_html($current_forecast_units); ?>
                                    <span class="numbers-grey">Previous Year:<br><?php echo esc_html($last_year_forecast_units); ?></span>
                                </td>
                                <td class="card-tab-percent <?php if($last_year_forecast_units > $current_forecast_units && $last_year_forecast_units != 0) { echo esc_attr('lower'); } elseif($last_year_forecast_units < $current_forecast_units && $last_year_forecast_units != 0) { echo esc_attr('higher'); } else { echo esc_attr('zero'); } ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="8.539" height="8.539" viewBox="0 0 8.539 8.539">
                                        <path d="M436.93,55.229l-1.349,1.349v-6.22l-6.229,6.229-.96-.96L434.62,49.4H428.4l1.349-1.349h7.181Z" transform="translate(-428.391 -48.048)" fill="#00b050"/>
                                    </svg>
                                    <span><?php echo esc_html($percent_compare_units); ?> %</span>
                                </td>
                            </tr>
                            <tr class="tab-second-row">
                                <td class="forecast-tab">
                                    Forecast Value
                                </td>
                                <td class="forecast-numbers">
                                    <?php echo esc_html(get_woocommerce_currency_symbol().$current_forecast_value); ?>
                                    <span class="numbers-grey">Previous Year:<br><?php echo esc_html(get_woocommerce_currency_symbol().$last_year_forecast_value); ?></span>
                                </td>
                                <td class="card-tab-percent <?php if($last_year_forecast_value > $current_forecast_value && $last_year_forecast_value != 0) { echo esc_attr('lower'); } elseif($last_year_forecast_value < $current_forecast_value && $last_year_forecast_value != 0) { echo esc_attr('higher'); } else { echo esc_attr('zero'); } ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="8.539" height="8.539" viewBox="0 0 8.539 8.539">
                                        <path d="M436.93,55.229l-1.349,1.349v-6.22l-6.229,6.229-.96-.96L434.62,49.4H428.4l1.349-1.349h7.181Z" transform="translate(-428.391 -48.048)" fill="#00b050"/>
                                    </svg>
                                    <span><?php echo esc_html($percent_compare_value); ?> %</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card-body d-flex table-two-flex" style="padding-left: 0 !important;border-top: 0;">
                    <div class="card-tab h-100">
                        <label class="card-tab-label fs-14">
                            Date Range:
                            <select class="card-tab-select">
                                <option value="this_week">This Week</option>
                                <option value="next_week">Next Week</option>
                                <option value="next_4_weeks">Next 4 Weeks</option>
                                <option value="next_8_weeks">Next 8 Weeks</option>
                            </select>
                        </label>
                        <table class="card-tab-second-table">
                            <tr>
                                <td class="forecast-tab">
                                    Forecast Units
                                </td>
                                <td class="forecast-numbers">
                                    52
                                    <span class="numbers-grey">Previous Year:<br>41</span>
                                </td>
                                <td class="card-tab-percent">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="8.539" height="8.539" viewBox="0 0 8.539 8.539"><path d="M436.93,55.229l-1.349,1.349v-6.22l-6.229,6.229-.96-.96L434.62,49.4H428.4l1.349-1.349h7.181Z" transform="translate(-428.391 -48.048)" fill="#00b050"/></svg>
                                    <span>121 %</span>
                                </td>
                            </tr>

                            <tr class="tab-second-row">
                                <td class="forecast-tab">
                                    Forecast Value
                                </td>
                                <td class="forecast-numbers">
                                    €632.50
                                    <span class="numbers-grey">Previous Year:<br>€123.45</span>
                                </td>
                                <td class="card-tab-percent">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="8.539" height="8.539" viewBox="0 0 8.539 8.539"><path d="M436.93,55.229l-1.349,1.349v-6.22l-6.229,6.229-.96-.96L434.62,49.4H428.4l1.349-1.349h7.181Z" transform="translate(-428.391 -48.048)" fill="#00b050"/></svg>
                                    <span>143 %</span>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="card-tab">
                                                    <div class="woocommerce-dashboard__columns" style="display: block;">
                                                        <div class="woocommerce-card woocommerce-analytics__card woocommerce-table has-action">
                                                            <div class="woocommerce-card__body">
                                                                <div class="woocommerce-table__table" aria-hidden="false" aria-labelledby="caption-7" role="group">
                                                                    <table>
                                                                        <tbody>
                                                                        <tr>
                                                                            <th role="columnheader" scope="col" class="woocommerce-table__header is-left-aligned"><span aria-hidden="false">Overstocked Items</span></th>
                                                                            <th role="columnheader" scope="col" class="woocommerce-table__header"><span aria-hidden="false">Current</span></th>
                                                                            <th role="columnheader" scope="col" class="woocommerce-table__header"><span aria-hidden="false">Ideal</span></th>
                                                                            <th role="columnheader" scope="col" class="woocommerce-table__header"><span aria-hidden="false">Weeks to Stock Out</span></th>
                                                                        </tr>
                                                                        <?php

                                                                        usort($products_data, function($a, $b) {
	                                                                        return intval($b['weeks_to_stock_out']) - intval($a['weeks_to_stock_out']);
                                                                        });

                                                                        foreach($products_data as $number => $item) { if($number == 10) { break; } ?>
                                                                        <tr>
                                                                            <th scope="row" class="woocommerce-table__item is-left-aligned">
                                                                                <div><a title="To Product Page" href=<?php echo esc_attr($item['cat_url']); ?>><?php echo esc_html($item['name']); ?></a></div>
                                                                            </th>
                                                                            <td class="woocommerce-table__item">
                                                                                <div><?php echo esc_html($item['current_stock']); ?></div>
                                                                            </td>
                                                                            <td class="woocommerce-table__item">
                                                                                <div><?php echo esc_html($item['ideal_stock']); ?></div>
                                                                            </td>
                                                                            <td class="woocommerce-table__item">
                                                                                <div><?php echo esc_html($item['weeks_to_stock_out']); ?></div>
                                                                            </td>
                                                                        </tr>
                                                                        <?php } ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                  <a href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_inventory')); ?>" class="new-des-btn fs-14 lh-16" style="width: 146px; height: 36px; margin-top: 20px; margin-left: auto;">
                                                    Overstock Report
                                                  </a>
                                            </div>
                </div>
            </div>
            <div class="ml-40 mr-40">
                <div class="woocommerce-section-header sp-styles">
                    <h2 class="woocommerce-section-header__title woocommerce-section-header__header-item fs-24 lh-30 fw-700">Leaderboards</h2>
                    <hr role="presentation">
                    <div class="woocommerce-section-header__menu woocommerce-section-header__header-item">
                        <div class="woocommerce-ellipsis-menu">
                            <div class="components-dropdown">
                                <button type="button" title="Choose which leaderboards to display and other settings" aria-expanded="true" id="toggle-select-rows-home" class="components-button components-icon-button woocommerce-ellipsis-menu__toggle is-opened">
                                    <svg aria-hidden="true" role="img" focusable="false" class="dashicon dashicons-ellipsis" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                        <path d="M5 10c0 1.1-.9 2-2 2s-2-.9-2-2 .9-2 2-2 2 .9 2 2zm12-2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-7 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
                                    </svg>
                                </button>
                                <div class="select-rows-leaderboards">
                                    <label class="components-base-control__label" for="inspector-select-control-2">Rows Per Table</label>
                                    <select id="inspector-select-control-2" class="components-select-control__input">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10" selected>10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              <span class="purchase-or-subtitle">Please have a look at the items below.</span>
                <div class="woocommerce-dashboard__columns" id="leaderboards-rows-home" style="display: grid;margin-top: 70px;">
                    <div class="woocommerce-card woocommerce-analytics__card woocommerce-table has-action">
                        <div class="woocommerce-card__header">
                            <div class="woocommerce-card__title-wrapper">
                                <h2 style="opacity: 0.9; color: #000;" class="woocommerce-card__title woocommerce-card__header-item fs-14 lh-30 fw-700">Top Categories - Potential Lost Sales</h2>
                            </div>
                            <div class="woocommerce-card__action woocommerce-card__header-item"></div>
                        </div>
                        <div class="woocommerce-card__body">
                            <div class="woocommerce-table__table" aria-hidden="false" aria-labelledby="caption-7" role="group">
                                <table>
                                    <caption id="caption-7" class="woocommerce-table__caption screen-reader-text">Top Categories - Potential Lost Sales</caption>
                                    <tbody>
                                    <tr>
                                        <th role="columnheader" scope="col" class="woocommerce-table__header is-left-aligned"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Category</span></th>
                                        <th role="columnheader" scope="col" class="woocommerce-table__header"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Lost Sales (Units)</span></th>
                                        <th role="columnheader" scope="col" class="woocommerce-table__header"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Lost Sales (Value)</span></th>
                                    </tr>
                                    <?php

                                    foreach($categories_data as &$item) {
	                                    $item['order_value_retail'] = str_replace('.00', '', str_replace(',', '', $item['order_value_retail'])) * $item['order_proposal_units'];
                                    }

                                    usort($categories_data, function($a, $b) {
	                                    return $b['order_value_retail'] - $a['order_value_retail'];
                                    });

                                    foreach($categories_data as $number => $item) { if($number == 10) { break; } ?>
                                    <tr>
                                        <th scope="row" class="woocommerce-table__item is-left-aligned">
                                          <div><a title="To Category Page" href=<?php echo esc_attr($item['cat_url']) ?>><?php echo esc_html($item['name']); ?></a></div>
                                        </th>
                                        <td class="woocommerce-table__item">
                                            <div><?php echo esc_html($item['ideal_stock']); ?></div>
                                        </td>
                                        <td class="woocommerce-table__item">
                                            <div><?php echo esc_html(get_woocommerce_currency_symbol().$item['order_value_retail']); ?></div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="woocommerce-card woocommerce-analytics__card woocommerce-table has-action">
                        <div class="woocommerce-card__header">
                            <div class="woocommerce-card__title-wrapper">
                                <h2 style="opacity: 0.9; color: #000;" class="woocommerce-card__title woocommerce-card__header-item fs-14 lh-30 fw-700">Top Products - Potential Lost Sales</h2>
                            </div>
                            <div class="woocommerce-card__action woocommerce-card__header-item"></div>
                        </div>
                        <div class="woocommerce-card__body">
                            <div class="woocommerce-table__table" aria-hidden="false" aria-labelledby="caption-7" role="group">
                                <table>
                                    <caption id="caption-7" class="woocommerce-table__caption screen-reader-text">Top Products - Lost Sales</caption>
                                    <tbody>
                                    <tr>
                                        <th role="columnheader" scope="col" class="woocommerce-table__header is-left-aligned"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Product</span></th>
                                        <th role="columnheader" scope="col" class="woocommerce-table__header"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Lost Sales (Units)</span></th>
                                        <th role="columnheader" scope="col" class="woocommerce-table__header"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Lost Sales (Value)</span></th>
                                    </tr>
                                    <?php

                                    foreach($products_data as &$item) {
	                                    $item['total_lost_price'] = $item['order_proposal_units'] * $item['order_value_price'];
                                    }

                                    usort($products_data, function($a, $b) {
	                                    return $b['total_lost_price'] - $a['total_lost_price'];
                                    });

                                    foreach($products_data as $number => $item) { if($number == 10) { break; } ?>
                                    <tr>
                                        <th scope="row" class="woocommerce-table__item is-left-aligned">
                                            <div><a title="To Product Page" href=<?php echo esc_attr($item['cat_url']); ?>><?php echo esc_html($item['name']) ?></a></div>
                                        </th>
                                        <td class="woocommerce-table__item">
                                            <div><?php echo esc_html($item['order_proposal_units']); ?></div>
                                        </td>
                                        <td class="woocommerce-table__item">
                                            <div><?php echo esc_html(get_woocommerce_currency_symbol().$item['total_lost_price']); ?></div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
              <a href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner')); ?>" class="new-des-btn" style="width: 138px; margin-top: 20px; font-size: 14px;">
                Go To Stock Detail
              </a>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../' . "popups.php"; ?>
</div>
</div>
</div>

<?php require_once __DIR__ . '/../' . 'footer.php';
