<?php

if ( ! empty( $_POST ) ) {
	update_option( 'sp.backorder', isset( $_POST['backorder'] ) ? 'enable' : '' );
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
                        .border-for-des {
                          max-width: 294px;
                          padding-bottom: 4.5rem
                        }

                        .user-interface-subtitle, .user-interface-title {
                          font-size: 14px;
                        }

                        .wp-person a:focus .gravatar, a:focus, a:focus .media-icon img {
                          box-shadow: none;
                          outline: none;
                        }

                        .show-billing-history:hover {
                          color: #874C5F;
                          text-decoration: underline;
                        }

                        .line {
                          margin: 60px 0;
                        }

                        
                    </style>
                    <h2 class="purchase-or-title"><?php echo esc_html(__( 'My Account', QA_MAIN_DOMAIN )); ?></h2>
                    <div class="d-flex nav-link-line" style="margin-top: 40px;">
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_my_account' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_my_account')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Overview', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_plans_payments' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_plans_payments')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Plans & Payment', QA_MAIN_DOMAIN)); ?></span></a>
                    </div>
					<?php do_action( 'after_page_header' ); ?>
					<?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div>
                        <div class="mt-30" style="margin-top: 50px">
                          <h4 style="margin-bottom: 1em"><?php echo esc_html( __( 'Your Plans', QA_MAIN_DOMAIN ) ); ?></h4>
                          <div class="d-flex justify-content-between">
                            <div class="user-old-design user-design my-acc-after">
                              <img class="old-design" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/shelf-planner.png">
                              <div class="border-for-des">
                                <h2 class="user-interface-title mb-2">
                                  Shelf Planner
                                </h2>
                                <span class="user-interface-subtitle m-0">
                                  Shelf Planner Demand and Inventory Management for single users.
                                </span>
                              </div>
                            </div>
                            <div class="included-plan pt-2">
                              <h4 class="included-plan-text mb-4">
                                Included in plan
                              </h4>
                              <div class="d-flex align-items-center mb-3">
                                <img class="included-plan-img mr-3" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/s-for-myAcc.svg">
                                <span class="included-plan-text included-subtext">
                                  Shelf Planner Inventory Management
                                </span>
                              </div>
                              <div class="d-flex align-items-center">
                                <img class="included-plan-img mr-3" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/i-for-myAcc.svg">
                                <span class="included-plan-text included-subtext">
                                  Inventory Optimisation
                                </span>
                              </div>
                            </div>
                            <div class="included-plan pt-2">
                              <h4 class="included-plan-text mb-3">
                                License Key
                              </h4>
                              <form method="post" action="#">
                                  <input type="text" class="license-key-act pl-3 pr-2 mb-3" placeholder="XXXX-XXXX-XXXX-XXXX">
                                  <p class="suc-message mb-4">
                                    You have successfully registered your version of Shelf Planner
                                  </p>
                                  <button class="deactive-btn pl-3 pr-3">
                                    Deactivate Your License
                                  </button>
                              </form>
                            </div>
                            <!-- <div class="included-plan pt-2">
                              <h4 class="included-plan-text mb-4">
                                Invoices & Payments
                              </h4>
                              <div class="d-flex align-items-center mb-3">
                                <img class="invoice-plan-img mr-3" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/address-card.svg">
                                <span class="included-plan-text included-subtext">
                                  Credit Card ending in ****3231
                                </span>
                              </div>
                              <div class="d-flex mb-4">
                                <img class="invoice-img mr-3" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/calendar-alt.svg">
                                <span class="included-plan-text included-subtext">
                                9,95 €/Month
                                <br>
                                Next payment scheduled 02.05.2022
                                <br>
                                Annual Plan with monthly payment
                                </span>
                              </div>
                              <a class="show-billing-history" href="#">
                                Show Billing History
                              </a>
                              <button type="button" class="manage-invoice mt-40">
                                Manage Invoices & Payment
                              </button>
                            </div> -->
                          </div>
                          <div class="line"></div>
                          <h2 class="purchase-or-title mt-0 mb-5">
                            Other Extensions & Modules
                          </h2>
                          <div class="d-flex extensions-max">
                            <div class="extensions-modules">
                              <div class="background-my-acc-design">
                              <img class="extensions-img" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/extensions.png">
                              </div>
                              <h2 class="extensions-modules-title pt-2 mb-1">Multi Warehouses</h2>
                              <p class="extensions-modules-text">Manage multiple warehouses</p>
                            </div>
                            <div class="extensions-modules">
                              <div class="background-my-acc-design rose-bg">
                              <img class="extensions-img" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/extensions.png">
                              </div>
                              <h2 class="extensions-modules-title pt-2 mb-1">Pricing Analyses</h2>
                              <p class="extensions-modules-text">See the impact of events and campaigns on your store’s profitability and net margin.</p>
                            </div>
                          </div>
                </div>
            </div>
            <?php include __DIR__ . '/../' . "popups.php"; ?>
        </div>
    </div>
</div>
