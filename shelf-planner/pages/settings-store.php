<?php

if ( ! empty( $_POST ) ) {
	if ( isset( $_POST['save-store-settings'] ) ) {
		update_option( 'sp.settings.business_model', sanitize_text_field( $_POST['business-model'] ) );
		update_option( 'sp.settings.assortment_size', sanitize_text_field( $_POST['assortment-size'] ) );
		unset( $_POST['save-store-settings'], $_POST['business-model'], $_POST['assortment-size'] );
		$post_data = array();
		foreach ( array_keys( $_POST ) as $v ) {
			$v = str_replace( 'industry-', '', $v );
			if ( is_numeric( $v ) && $v > 0 ) {
				$post_data[] = (int) $v;
			}
		}
		if ( ! empty( $post_data ) ) {
			sort( $post_data );
			update_option( 'sp.settings.industry', implode( ',', $post_data ) );
		} else {
			update_option( 'sp.settings.industry', '' );
		}
	}
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
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }

                        .sphd-p {
                            font-size: 16px;
                        }

                        input[type=checkbox], input[type=radio] {
                          border-color: #D4D4D5 !important;
                        }
                    </style>
                    <h2 class="purchase-or-title"><?php echo esc_html(__( 'Settings', QA_MAIN_DOMAIN )); ?></h2>
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
                        <div class="mt-30" style="margin-top: 50px">
                        <h3 class="purchase-or-title fs-20 mb-2"><?php echo esc_html(__( 'Industry', QA_MAIN_DOMAIN )); ?></h3>
                        <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Setting the correct industry improves the forecast for your products and allows us to provide you with insights for your business.', QA_MAIN_DOMAIN )); ?></span>
                            <form method="post">
                                <table style="width: 60%; margin: 40px 0; margin-left: 20px;">
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Fashion & Apparel' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Equestrian' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Health' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Footwear' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Drinks & Beverages' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Toys & Games' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Bags & Suitcases' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Food' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Bookshop' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Jewellery & Watches' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Kitchen & Dining' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Gardening' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Babywear' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Beauty & Personal Care' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'DIY' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Optical' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Home & Household' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Pet Store' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Sportswear & Sporting goods' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Furniture & Decoration' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Car Parts & Car Care' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Outdoor Life' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Consumer Electronics' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Other' ) ?>
                                        </td>
                                    </tr>
                                </table>
                                <p class="mg-b-20"></p>
                                <h4 class="purchase-or-title" style="margin: 2em 0 1em;"><?php echo esc_html( __( 'Please specify the breath of your store.', QA_MAIN_DOMAIN ) ); ?></h4>
                                <p class="d-flex align-items-center"><?php echo sp_settings_get_radio_2( 'A', 'my store has less than 250 products' ); ?></p>
                                <p class="d-flex align-items-center"><?php echo sp_settings_get_radio_2( 'B', 'my store has between 250 and 1000 products' ); ?></p>
                                <p class="d-flex align-items-center"><?php echo sp_settings_get_radio_2( 'C', 'my store has more than 1000 products' ); ?></p>
                                <p class="mg-b-20"></p>
                                <h2 class="purchase-or-title"><?php echo esc_html(__( 'Business Model', QA_MAIN_DOMAIN )); ?></h2>
                    <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Our machine learning algorithms are using different logics for business to business (B2B) companies than direct to consumer (DTC)', QA_MAIN_DOMAIN )); ?></span>
                    <p class="d-flex align-items-center mt-40"><?php echo sp_settings_get_radio_3( 'DTC -', 'my site sell directly to consumers.' ); ?></p>
                                <p class="d-flex align-items-center"><?php echo sp_settings_get_radio_3( 'B2B -', 'my site sells business to business only.' ); ?></p>
                                <p class="d-flex align-items-center"><?php echo sp_settings_get_radio_3( 'Multichannel -', 'my site sells business to business (B2B) as well as direct to consumer (DTC).' ); ?></p>
                    <div class="mt-40">
                      <h2 class="purchase-or-title"><?php echo esc_html(__( 'User Interface', QA_MAIN_DOMAIN )); ?></h2>
                      <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Choose between different interfaces dependent on your preferences.', QA_MAIN_DOMAIN )); ?></span>
                      <div class="row mt-40 ml-0 mr-0" style="gap: 155px">
                        <div class="user-old-design user-design">
                          <img class="old-design" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/old-des-interface.png">
                          <div class="border-for-des">
                            <h2 class="user-interface-title mb-2">
                              Shelf Planner Full Screen
                            </h2>
                            <span class="user-interface-subtitle m-0">
                              Use the Shelf Planner UI for all your Inventory Management.
                            </span>
                          </div>
                        </div>

                        <div class="user-new-design user-design user-choosen-des">
                          <img class="new-design" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/new-design-user.png">
                          <div class="border-for-des">
                            <h2 class="user-interface-title mb-2">
                              WooCommerce Integration
                            </h2>
                            <span class="user-interface-subtitle m-0">
                              Embed Shelf Planner in the WooCommerce standard pages.
                            </span>
                          </div>
                        </div>
                      </div>
                                <input style="margin-top: 3em" type="submit" class="button-settings-po" value="<?php echo esc_attr( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-store-settings"/>
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
<script>
  const borderChoose = document.querySelectorAll(".user-design");

for (let userClick of borderChoose) {
  
  userClick.addEventListener("click", function(){
    for (let userClick of borderChoose) {
      userClick.classList.remove('user-choosen-des');
    }
    
    this.classList.add('user-choosen-des');
  });
  
}
</script>