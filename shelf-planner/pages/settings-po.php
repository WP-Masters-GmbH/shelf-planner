<?php

global $wpdb;

if ( ! empty( $_POST ) ) {
	if ( isset( $_POST['save-po-settings'] ) ) {
		update_option( 'sp.settings.po_auto-generate_orders', sanitize_text_field( $_POST['po-auto-generate'] ) );

		$po_prefix_new = trim( sanitize_text_field( $_POST['po-prefix'] ) );
		if ( strlen( $po_prefix_new ) > 0 ) {
			$po_prefix = $po_prefix_new;
			update_option( 'sp.settings.po_prefix', sanitize_text_field( $po_prefix ) );
		}

		$po_next_number = intval( trim( sanitize_text_field( $_POST['po-next-number'] ) ) );
		update_option( 'sp.settings.po_next_number', $po_next_number );

		$po_company_name = trim( sanitize_text_field( $_POST['po-company-name'] ) );
		update_option( 'sp.settings.po_company_name', $po_company_name );

		$po_company_address = trim( sanitize_text_field( $_POST['po-company-address'] ) );
		update_option( 'sp.settings.po_company_address', $po_company_address );

		$po_postal_code = trim( sanitize_text_field( $_POST['po-postal-code'] ) );
		update_option( 'sp.settings.po_postal_code', $po_postal_code );

		$po_city = trim( sanitize_text_field( $_POST['po-city'] ) );
		update_option( 'sp.settings.po_city', $po_city );

		$po_country = trim( sanitize_text_field( $_POST['po-country'] ) );
		update_option( 'sp.settings.po_country', $po_country );

		$po_description = trim( sanitize_text_field( $_POST['po-description'] ) );
		update_option( 'sp.settings.po_description', $po_description );

		$po_phone = trim( sanitize_text_field( $_POST['po-phone'] ) );
		update_option( 'sp.settings.po_phone', $po_phone );

		$po_website = trim( sanitize_text_field( $_POST['po-website'] ) );
		update_option( 'sp.settings.po_website', $po_website );

		$po_email = trim( sanitize_text_field( $_POST['po-email'] ) );
		update_option( 'sp.settings.po_email', $po_email );

		$po_vat_number = trim( sanitize_text_field( $_POST['po-vat-number'] ) );
		update_option( 'sp.settings.po_vat_number', $po_vat_number );

		$po_bank = trim( sanitize_text_field( $_POST['po-bank'] ) );
		update_option( 'sp.settings.po_bank', $po_bank );

		$po_branch = trim( sanitize_text_field( $_POST['po-branch'] ) );
		update_option( 'sp.settings.po_branch', $po_branch );

		$po_account_number = trim( sanitize_text_field( $_POST['po-account-number'] ) );
		update_option( 'sp.settings.po_account_number', $po_account_number );

		$po_swift_code = trim( sanitize_text_field( $_POST['po-swift-code'] ) );
		update_option( 'sp.settings.po_swift_code', $po_swift_code );

		$po_iban = trim( sanitize_text_field( $_POST['po-iban'] ) );
		update_option( 'sp.settings.po_iban', $po_iban );

		if ( isset( $_FILES['po-company-logo'] ) && 'image/png' == $_FILES['po-company-logo']['type'] ) {
			update_option( 'sp.settings.po_company_logo', 'data:image/png;base64,' . base64_encode( file_get_contents( $_FILES['po-company-logo']['tmp_name'] ) ) );
		}
	}
}

$po_autogenerate_orders_type = get_option( 'sp.settings.po_auto-generate_orders', 'auto' );
$po_prefix                   = get_option( 'sp.settings.po_prefix', 'PO-' );
$po_next_number              = sp_get_next_po();

$po_company_name    = get_option( 'sp.settings.po_company_name', '' );
$po_company_address = get_option( 'sp.settings.po_company_address', '' );
$po_postal_code     = get_option( 'sp.settings.po_postal_code', '' );
$po_city            = get_option( 'sp.settings.po_city', '' );
$po_country         = get_option( 'sp.settings.po_country', '' );
$po_description     = get_option( 'sp.settings.po_description', '' );
$po_phone           = get_option( 'sp.settings.po_phone', '' );
$po_website         = get_option( 'sp.settings.po_website', '' );
$po_email           = get_option( 'sp.settings.po_email', '' );
$po_vat_number      = get_option( 'sp.settings.po_vat_number', '' );
$po_bank            = get_option( 'sp.settings.po_bank', '' );
$po_branch          = get_option( 'sp.settings.po_branch', '' );
$po_account_number  = get_option( 'sp.settings.po_account_number', '' );
$po_swift_code      = get_option( 'sp.settings.po_swift_code', '' );
$po_iban            = get_option( 'sp.settings.po_iban', '' );

$po_company_logo = get_option( 'sp.settings.po_company_logo', '' );

require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';

?>
<style>
  .switch {
    margin-top: 64px;
  }
</style>
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
<h2 class="purchase-or-title"><?php echo esc_html(__( 'Settings', QA_MAIN_DOMAIN )); ?></h2>
                    <span class='purchase-or-subtitle mb-30'><?php echo esc_html(__( 'Here you can manage general settings for your store, forecast, orders, etc.', QA_MAIN_DOMAIN )); ?></span>                    <?php do_action( 'after_page_header' ); ?>
                    <div class="d-flex nav-link-line" style="margin-top: 40px;">
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_store' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_store')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Store Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_forecast' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_forecast')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Forecast Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_product' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_product')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Product Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_po' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_po')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('PO Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_backorder' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_backorder')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Backorder', QA_MAIN_DOMAIN)); ?></span></a>
                    </div>
                    <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div>
                        <div class="card-body">
                            <h4 class="purchase-or-title"><?php echo esc_html( __( 'Purchase Order Settings', QA_MAIN_DOMAIN ) ); ?></h4>
                            <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Here you can manage general settings for your store, forecast, orders, etc.', QA_MAIN_DOMAIN )); ?></span>                    <?php do_action( 'after_page_header' ); ?>
                            <div class="d-flex align-items-center" style="gap: 50%;">
                              <div>
                                <h4 class="purchase-or-title fs-20 mb-2"><?php echo esc_html( __( 'Import Purchase Orders', QA_MAIN_DOMAIN ) ); ?></h4>
                                <span class='purchase-or-subtitle mb-30'><?php echo esc_html(__( 'Use 3rd Party Integrations or a manual upload via Excel to manage your incoming stock.', QA_MAIN_DOMAIN )); ?></span>
                              </div>
                              <div>
                                <label class="switch"><input type="checkbox"><span class="slider round"></span></label>
                              </div>
                            </div>
                            
                            <form method="post" enctype="multipart/form-data">
                                <!-- begin new form -->
                                <div class="row mt-60 mb-30" id="js-add-new-supplier">
                                    <div class="col-md-4 ">
                                        <label><?php echo esc_html( __( 'Company Name', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-company-name" required="required" value="<?php echo  esc_attr( $po_company_name ); ?>" placeholder="Company Name*"/> <label><?php echo esc_html( __( 'Company Address', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-company-address" required="required" value="<?php echo  esc_attr( $po_company_address ); ?>" placeholder="Company Address*"/> <label><?php echo esc_html( __( 'Postal Code', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-postal-code" required="required" value="<?php echo  esc_attr( $po_postal_code ); ?>" placeholder="Postal Code*"/> <label><?php echo esc_html( __( 'City', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-city" required="required" value="<?php echo  esc_attr( $po_city ); ?>" placeholder="City*"/>
                                        <label><?php echo esc_html( __( 'Country', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-country" required="required" placeholder="Country*" value="<?php echo  esc_attr( $po_country ); ?>"/>
                                        <label><?php echo esc_html( __( 'Additional Information', QA_MAIN_DOMAIN ) ); ?></label> <textarea class="form-control" name="po-description" style="color: #B5B5B5" placeholder="Additional Information"><?php echo  esc_textarea( $po_description ); ?></textarea> <br>
                                    </div>
                                    <div class="col-md-4">
                                        <label><?php echo esc_html( __( 'Phone', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="po-phone" value="<?php echo  esc_attr( $po_phone ); ?>" placeholder="Phone"/> <label><?php echo esc_html( __( 'Website', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="po-website" value="<?php echo  esc_attr( $po_website ); ?>" placeholder="Website"/> <label><?php echo esc_html( __( 'Email', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="po-email" value="<?php echo  esc_attr( $po_email ); ?>" placeholder="Email"/> <label><?php echo esc_html( __( 'VAT Registration Number', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="po-vat-number" value="<?php echo  esc_attr( $po_vat_number ); ?>" placeholder="VAT Registration Number"/> <label><?php echo esc_html( __( 'Your company logo', QA_MAIN_DOMAIN ) ); ?></label>
										<?php
										// Already escaped
										if($po_company_logo){
										    ?>
                                            <img onclick="jQuery('#company-logo-upload').show()" style="cursor:pointer" src="<?php echo esc_attr( $po_company_logo ); ?>" />
                                        <?php
										}
										?>
                                        <div id="company-logo-upload" style="<?php echo esc_attr( strlen( $po_company_logo ) > 0 ? 'display:none' : '' ); ?>">
                                            <input type="file" class="form-control" name="po-company-logo"/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label><?php echo esc_attr( __( 'Bank', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="po-bank" value="<?php echo  esc_attr( $po_bank ); ?>" placeholder="Bank"/> <label><?php echo esc_html( __( 'Branch', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="po-branch" value="<?php echo  esc_attr( $po_branch ); ?>" placeholder="Branch"/> <label><?php echo esc_html( __( 'Account Number', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="po-account-number" value="<?php echo  esc_attr( $po_account_number ); ?>" placeholder="Account Number"/> <label><?php echo esc_html( __( 'Swift Code', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="po-swift-code" value="<?php echo  esc_attr( $po_swift_code ); ?>" placeholder="Swift Code"/> <label><?php echo esc_html( __( 'IBAN', QA_MAIN_DOMAIN ) ); ?></label> <input type="text" class="form-control" name="po-iban" value="<?php echo  esc_attr( $po_iban ); ?>" placeholder="IBAN"/>
                                    </div>
                                </div>
                                <!-- end new form -->
                                <p class="settings-text-new"><?php echo esc_html( __( 'Your purchase order numbers are set on auto-generate mode to save you time.', QA_MAIN_DOMAIN ) ); ?></p>
                                <p class="settings-text-new"><?php echo esc_html( __( 'Do you want to change settings?', QA_MAIN_DOMAIN ) ); ?></p>
                                <div class="d-flex">
                                <div class="d-flex flex-column" style="margin-right: 75px">
                                <div style="margin-bottom: 20px; margin-top: 21px;">
                                    <input type="radio" name="po-auto-generate" value="auto" <?php echo esc_attr( $po_autogenerate_orders_type == 'auto' ? 'checked="checked"' : '' ); ?> /><?php echo esc_html( __( 'Continue auto-generating purchase order numbers', QA_MAIN_DOMAIN ) ); ?>
                                </div>
                                <div>
                                    <input type="radio" name="po-auto-generate" value="manual"<?php echo esc_attr( $po_autogenerate_orders_type == 'manual' ? 'checked="checked"' : '' ); ?>/><?php echo esc_html( __( 'I will add them manually each time', QA_MAIN_DOMAIN ) ); ?>
                                    </div>
                                  </div>
                                <div class="d-flex">
                                  <label class="label-for-pref" style="margin-right: 75px">
                                    <?php echo esc_html( __( 'Prefix', QA_MAIN_DOMAIN ) ); ?>
                                    <input class="prefix-inp" type="text" name="po-prefix" value="<?php echo  esc_attr( $po_prefix ) ?>"/>
                                  </label>
                                  <label class="label-for-pref">
                                    <?php echo esc_html( __( 'Next Number', QA_MAIN_DOMAIN ) ); ?>
                                    <input class="prefix-inp" type="text" name="po-next-number" value="<?php echo  esc_attr( $po_next_number ) ?>"/>
                                  </label>
                                  </div>
                                </div>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="button-settings-po" value="<?php echo esc_attr( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-po-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
                <?php include __DIR__ . '/../' . "popups.php"; ?>
            </div>
        </div>
    </div>
</div>
