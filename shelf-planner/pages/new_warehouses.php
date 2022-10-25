<?php

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
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }

                        .sphd-p {
                            font-size: 16px;
                        }

                        .slider:after, input:checked+.slider:after {
                          content: "";
                        }

                        input.form-control {
                          width: 63%;
                          margin-bottom: 15px;
                        }

                        .wp-core-ui select {
                          width: 55%;
                          max-width: unset;
                          border: 1px solid #DEDEDF;
                          color: #B5B5B5;
                          font-size: 14px;
                          line-height: 17px;
                          font-weight: 500;
                        }

                        .wp-core-ui select:focus, .wp-core-ui select:active, .wp-core-ui select:hover {
                          outline: none;
                          border-color: #DEDEDF;
                          box-shadow: none;
                          color: #B5B5B5;
                        }

                        .form-control:focus {
                            border-color: #DEDEDF !important;
                          }

                          .wp-core-ui select {
                            padding-left: 15px;
                            height: 38px;
                          }
                    </style>
                    <h2 class="purchase-or-title"><?php echo esc_html(__( 'Warehouses', QA_MAIN_DOMAIN )); ?></h2>
                    <div class="d-flex nav-link-line" style="margin-top: 40px;">
                    <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_warehouses' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_warehouses')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Warehouses', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_warehouses_add_new' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_warehouses_add_new')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Create New Warehouse', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_warehouses' ? 'not-active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_warehouses')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Settings', QA_MAIN_DOMAIN)); ?></span></a>
                    </div>
					<?php do_action( 'after_page_header' ); ?>
					<?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div>
                      <div>
                        <div class="mt-30" style="margin-top: 50px">
                            <form method="post">
                                <h4 class="fw-700 fs-18 lh-24 mb-2"><?php echo esc_html( __( 'Warehouse Details', QA_MAIN_DOMAIN ) ); ?></h4>
                                <span class='purchase-or-subtitle op-80'><?php echo esc_html(__( 'Here you can manage information about your warehouses and shipping locations.', QA_MAIN_DOMAIN )); ?></span>
                                <div class="new-warehouse-content mt-30">
                                  <div class="switches-warehouse mb-40">
                                  <div class="switch-with-text">
                                    <label class="switch"><input type="checkbox"><span class="slider round"></span></label>
                                    <span class="op-80">Virtual Warehouse</span>
                                    <svg class="quest-warehouse" xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10"><g transform="translate(-34)"><g transform="translate(34)"><circle cx="5" cy="5" r="5" fill="#131313"></circle><text transform="translate(3 8)" fill="#fff" font-size="8" font-family="Lato-Regular, Lato"><tspan x="0" y="0">?</tspan></text></g></g></svg>
                                  </div>
                                  <div class="switch-with-text">
                                    <label class="switch"><input type="checkbox"><span class="slider round"></span></label>
                                    <span class="op-80">Hide inventory from my store front</span>
                                  </div>
                                </div>
                                <div class="mb-40">
                                  <p class="d-flex align-items-center"><?php echo sp_settings_get_radio_3( '', 'Delivery Address is the same as Company Address' ); ?></p>
                                  <p class="d-flex align-items-center"><?php echo sp_settings_get_radio_3( '', 'Deliver my orders to this address:' ); ?></p>
                                </div>
                                <div>
                                <div class="row" id="js-add-new-warehouse">
                                  <div class="col-md-4">
                                      <label><?php echo esc_html( __( 'Warehouse Name', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="warehouse_name" required="required" value="" placeholder="Warehouse Name"/>
                                      <label><?php echo esc_html( __( 'Warehouse Address', QA_MAIN_DOMAIN ) ); ?></label>
                                      <input type="text" class="form-control" name="warehouse_address" value="" placeholder="Warehouse Address"/>
                                      <label><?php echo esc_html( __( 'Postal Code', QA_MAIN_DOMAIN ) ); ?></label>
                                      <input type="text" class="form-control" name="postal_code_warehouse" value="" placeholder="Postal Code"/>
                                      <label><?php echo esc_html( __( 'City', QA_MAIN_DOMAIN ) ); ?></label>
                                      <input type="text" class="form-control" name="city_warehouse" value="" placeholder="City"/>
                                      
                                  </div>
                                  <div class="col-md-4">
                                      <label><?php echo esc_html( __( 'Phone', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="warehouse_phone" value="" placeholder="Phone"/>
                                      <label><?php echo esc_html( __( 'Website', QA_MAIN_DOMAIN ) ); ?></label>
                                      <input type="text" class="form-control" name="warehouse_website" value="" placeholder="Website"/>
                                      <label><?php echo esc_html( __( 'Email', QA_MAIN_DOMAIN ) ); ?></label>
                                      <input type="text" class="form-control" name="warehouse_email" value="" placeholder="Email"/>
                                  </div>
                                </div>
                                <div class="d-flex flex-column">
                                <label><?php echo esc_html( __( 'Country', QA_MAIN_DOMAIN ) ); ?></label>
                                <select name="sp_countries_list">
                                          <option value="XX">Select Country</option>
                                          <?php
                                          global $sp_countries_normilized;
                                          ?>
                                          <?php foreach($sp_countries_normilized as $country_code => $country_name){  ?>
                                          <option value="<?php echo esc_attr($country_code); ?>"><?php echo esc_html($country_name); ?></option>
                                          <?php } ?>
                                          <?php?>
                                      </select>
                                      <input type="submit" style="width: 78px; height: 38px;" class="new-des-btn add-new-sup mt-40" value="<?php if ( ! isset( $_GET['supplier_id'] ) ) { ?>Save<?php } else { ?>Save<?php } ?>"/>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php include __DIR__ . '/../' . "popups.php"; ?>
            </div>
        </div>
    </div>
</div>
