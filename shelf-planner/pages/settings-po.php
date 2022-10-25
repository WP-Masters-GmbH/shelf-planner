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

                        .sp-settings-po-table {
                            margin-top: 2em;
                            width: 80%;
                        }

                        .sp-settings-po-table th {
                            text-align: center;
                        }

                        .sp-settings-po-table td {
                            padding-bottom: 2em;
                            text-align: center;
                        }
                    </style>
                    <h2><?php echo esc_html(__( 'Settings', QA_MAIN_DOMAIN )); ?></h2>
                    <?php do_action( 'after_page_header' ); ?>
                    <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div class="card">
                        <div class="card-body">
                            <h4><?php echo esc_html( __( 'Purchase Order Settings', QA_MAIN_DOMAIN ) ); ?></h4>
                            <p class="mg-b-20"></p>
                            <form method="post" enctype="multipart/form-data">
                                <p style="font-weight: bold; font-size: inherit"><?php echo esc_html( __( 'Here you can define the content and layout of your Purchase Orders.This information is included in the Purchase Order. If you do not wish to share this information, please leave blank.', QA_MAIN_DOMAIN ) ); ?></p>
                                <!-- begin new form -->
                                <div class="row" id="js-add-new-supplier">
                                    <div class="col-md-4 ">
                                        <label><?php echo esc_html( __( 'Company Name', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-company-name" required="required" value="<?php echo  esc_attr( $po_company_name ); ?>" placeholder="Company Name*"/> <label><?php echo esc_html( __( 'Company Address', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-company-address" required="required" value="<?php echo  esc_attr( $po_company_address ); ?>" placeholder="Company Address*"/> <label><?php echo esc_html( __( 'Postal Code', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-postal-code" required="required" value="<?php echo  esc_attr( $po_postal_code ); ?>" placeholder="Postal Code*"/> <label><?php echo esc_html( __( 'City', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-city" required="required" value="<?php echo  esc_attr( $po_city ); ?>" placeholder="City*"/>
                                        <label><?php echo esc_html( __( 'Country', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="po-country" required="required" placeholder="Country*" value="<?php echo  esc_attr( $po_country ); ?>"/>
                                        <label><?php echo esc_html( __( 'Additional Information', QA_MAIN_DOMAIN ) ); ?></label> <textarea class="form-control" name="po-description" placeholder="Additional Information"><?php echo  esc_textarea( $po_description ); ?></textarea> <br>
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
                                <p style="font-weight: bold; font-size: inherit"><?php echo esc_html( __( 'Your purchase order numbers are set on auto-generate mode to save you time.', QA_MAIN_DOMAIN ) ); ?></p>
                                <p style="font-weight: bold; font-size: inherit"><?php echo esc_html( __( 'Do you want to change settings?', QA_MAIN_DOMAIN ) ); ?></p>
                                <table class="sp-settings-po-table">
                                    <tr>
                                        <th></th>
                                        <th><?php echo esc_html( __( 'Prefix', QA_MAIN_DOMAIN ) ); ?></th>
                                        <th><?php echo esc_html( __( 'Next Number', QA_MAIN_DOMAIN ) ); ?></th>
                                    </tr>
                                    <tr>
                                        <td style="width: 40%; text-align: left; font-weight: bold"><input type="radio" name="po-auto-generate" value="auto"
												<?php echo esc_attr( $po_autogenerate_orders_type == 'auto' ? 'checked="checked"' : '' ); ?>
                                            /><?php echo esc_html( __( 'Continue auto-generating purchase order numbers', QA_MAIN_DOMAIN ) ); ?>
                                        </td>
                                        <td>
                                            <input type="text" name="po-prefix" value="<?php echo  esc_attr( $po_prefix ) ?>"/>
                                        </td>
                                        <td>
                                            <input type="text" name="po-next-number" value="<?php echo  esc_attr( $po_next_number ) ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: left; font-weight: bold"><input type="radio" name="po-auto-generate" value="manual"
												<?php echo esc_attr( $po_autogenerate_orders_type == 'manual' ? 'checked="checked"' : '' ); ?>
                                            /><?php echo esc_html( __( 'I will add them manually each time', QA_MAIN_DOMAIN ) ); ?>
                                        </td>
                                    </tr>
                                </table>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="btn btn-sm
                                    btn-success" value="<?php echo esc_attr( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-po-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>