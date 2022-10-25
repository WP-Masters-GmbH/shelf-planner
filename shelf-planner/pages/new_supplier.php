<?php
require_once __DIR__ . '/admin_page_header.php';

global $wpdb;

if ( $_POST ) {

	$data = array();
	$data['supplier_name'] = isset($_POST['supplier_name']) ? sanitize_text_field( $_POST['supplier_name'] ) : '';
	$data['supplier_code'] = isset($_POST['supplier_code']) ? sanitize_text_field( $_POST['supplier_code'] ) : '';
	$data['tax_vat_number'] = isset($_POST['tax_vat_number']) ? sanitize_text_field( $_POST['tax_vat_number'] ) : '';
	$data['phone_number'] = isset($_POST['phone_number']) ? sanitize_text_field( $_POST['phone_number'] ) : '';
	$data['website'] = isset($_POST['website']) ? sanitize_text_field( $_POST['website'] ) : '';
	$data['email_for_ordering'] = isset($_POST['email_for_ordering']) ? sanitize_email( $_POST['email_for_ordering'] ) : '';
	$data['general_email_address'] = isset($_POST['general_email_address']) ? sanitize_email( $_POST['general_email_address'] ) : '';
	$data['description'] = isset($_POST['description']) ? sanitize_text_field( $_POST['description'] ) : '';
	$data['currency'] = isset($_POST['currency']) ? sanitize_text_field( $_POST['currency'] ) : '';
	$data['address'] = isset($_POST['address']) ? sanitize_text_field( $_POST['address'] ) : '';
	$data['city'] = isset($_POST['city']) ? sanitize_text_field( $_POST['city'] ) : '';
	$data['country'] = isset($_POST['country']) ? sanitize_text_field( $_POST['country'] ) : '';
	$data['state'] = isset($_POST['state']) ? sanitize_text_field( $_POST['state'] ) : '';
	$data['account_no'] = isset($_POST['account_no']) ? sanitize_text_field( $_POST['account_no'] ) : '';
	$data['account_id'] = isset($_POST['account_id']) ? sanitize_text_field( $_POST['account_id'] ) : '';
	$data['assigned_to'] = isset($_POST['assigned_to']) ? sanitize_text_field( $_POST['assigned_to'] ) : '';
	$data['ship_to_location'] = isset($_POST['ship_to_location']) ? sanitize_text_field( $_POST['ship_to_location'] ) : '';
	$data['discount'] = isset($_POST['discount']) ? sanitize_text_field( $_POST['discount'] ) : '';
	$data['tax_rate'] = isset($_POST['tax_rate']) ? sanitize_text_field( $_POST['tax_rate'] ) : '';
	$data['lead_times'] = isset($_POST['lead_times']) ? sanitize_text_field( $_POST['lead_times'] ) : '';
	$data['payment_terms'] = isset($_POST['payment_terms']) ? sanitize_text_field( $_POST['payment_terms'] ) : '';
	$data['delivery_terms'] = isset($_POST['delivery_terms']) ? sanitize_text_field( $_POST['delivery_terms'] ) : '';

	$data['dt_added'] = current_time( 'mysql', 1 );

	if ( isset( $_GET['supplier_id'] ) ) {
		$result = $wpdb->update( $wpdb->suppliers, $data, [ 'id' => (int) $_GET['supplier_id'] ] );
		$msg    = 'Supplier was updated successfully';
	} else {
		$result = $wpdb->insert( $wpdb->suppliers, $data );
		$msg    = 'Supplier was added successfully';
	}
	if ( $result ) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php echo  esc_html( $msg ) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><?php
	} else { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo esc_html( __( 'Error occurred, please try again', QA_MAIN_DOMAIN ) ); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button></div><?php }
}

?><?php require_once __DIR__ . '/../' . 'header.php'; ?>
  <style>
.form-control {
border: 1px solid #DEDEDF !important;
width: 66%;
outline: none !important;
color: #B5B5B5 !important;
font-family: "Lato" !important;
font-weight: 500 !important;
font-size: 14px !important;
line-height: 17px !important;
}

.form-control::placeholder{
color: #B5B5B5 !important;
font-family: "Lato" !important;
font-weight: 500 !important;
font-size: 14px !important;
line-height: 17px !important;
}

  label {
font-family: "Lato" !important;
font-weight: 700 !important;
font-size: 13px !important;
line-height: 19px !important;
color: #333333 !important;
}

.form-control:focus {
  border-color: #DEDEDF !important;
}

.wp-core-ui select {
    max-width: unset;
    width: 100%;
    border: 1px solid #DEDEDF;
    color: #B5B5B5;
    height: 38px;
}

.wp-core-ui select:active, .wp-core-ui select:focus, .wp-core-ui select:hover {
  border-color: #DEDEDF;
  color: #B5B5B5;
}
  </style>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
        <?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
          <div id="root"></div>
            <div class="page">
            <?php include __DIR__ . '/../' . "page_header.php"; ?>

                <?php include SP_PLUGIN_DIR_PATH . "pages/header_js.php"; ?>
                <!-- container opened -->
                <div class="ml-40 mr-40">
                        <h2 class="purchase-or-title"><?php echo esc_html(__( 'Create New Suppliers', QA_MAIN_DOMAIN )); ?></h2>
                        <div class="d-flex nav-link-line" style="margin-top: 40px;">
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_suppliers' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_suppliers')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Suppliers', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_suppliers_add_new' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_suppliers_add_new')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Create New Supplier', QA_MAIN_DOMAIN)); ?></span></a>
                        </div>
                        
                        <div class="card-body" style="padding-left:25px !important;">
                                <div class="main-content-label mg-b-5" style="margin-top: 50px;"></div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                    <?php
												$supplier = [
													'supplier_name'         => '',
													'supplier_code'         => '',
													'tax_vat_number'        => '',
													'phone_number'          => '',
													'website'               => '',
													'email_for_ordering'    => '',
													'general_email_address' => '',
													'supplier_id'           => '',
													'currency'              => '',
													'address'               => '',
													'city'                  => '',
													'country'               => '',
													'state'                 => '',
													'zip_code'              => '',
													'account_no'            => '',
													'assigned_to'           => '',
													'ship_to_location'      => '',
													'discount'              => '',
													'tax_rate'              => '',
													'lead_times'            => '',
													'weeks_of_stock'        => '',
													'description'           => '',
													'account_id'            => '',
													'payment_terms'         => '',
													'delivery_terms'        => '',
												];
											?>
                                            <form method="post" action="">
                                                <div class="row" id="js-add-new-supplier">
                                                    <div class="col-md-4 ">
                                                        <label><?php echo esc_html( __( 'Supplier Name', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="supplier_name" required="required" value="<?php echo  esc_attr( $supplier['supplier_name'] ); ?>" placeholder="Supplier Name*"/> <label><?php echo esc_html( __( 'Supplier Code', QA_MAIN_DOMAIN ) ); ?>*</label>
                                                        <input type="text" class="form-control" name="supplier_code" required="required" value="<?php echo  esc_attr( $supplier['supplier_code'] ); ?>" placeholder="Supplier Code*"/>
                                                        <label><?php echo esc_html( __( 'TAX / VAT Number', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="tax_vat_number" value="<?php echo  esc_attr( $supplier['tax_vat_number'] ); ?>" placeholder="TAX / VAT Number"/>
                                                        <label><?php echo esc_html( __( 'Website', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="website" value="<?php echo  esc_attr( $supplier['website'] ); ?>" placeholder="Website"/>
                                                        <label><?php echo esc_html( __( 'Email for Ordering', QA_MAIN_DOMAIN ) ); ?>*</label>
                                                        <input type="text" class="form-control" name="email_for_ordering" value="<?php echo  esc_attr( $supplier['email_for_ordering'] ); ?>" placeholder="Email for Ordering*"/>
                                                        <label><?php echo esc_html( __( 'General Email Address', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="general_email_address" value="<?php echo  esc_attr( $supplier['general_email_address'] ); ?>" placeholder="General Email Address"/> <label><?php echo esc_html( __( 'Description', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <textarea class="form-control" name="description" placeholder="Description"><?php echo  esc_textarea( $supplier['description'] ); ?></textarea> <br>
                                                        <input type="submit" class="new-des-btn add-new-sup" value="<?php if ( ! isset( $_GET['supplier_id'] ) ) { ?>Add New Supplier<?php } else { ?>Save<?php } ?>"/>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><?php echo esc_html( __( 'Currency', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="currency" required="required" value="<?php echo  esc_attr( $supplier['currency'] ); ?>" placeholder="Currency"/>
                                                        <label><?php echo esc_html( __( 'Address', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="address" value="<?php echo  esc_attr( $supplier['address'] ); ?>" placeholder="Address"/>
                                                        <label><?php echo esc_html( __( 'City', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="city" value="<?php echo  esc_attr( $supplier['city'] ); ?>" placeholder="City*"/>
                                                        <label><?php echo esc_html( __( 'Country', QA_MAIN_DOMAIN ) ); ?></label> 
                                                        <select name="sp_countries_list" class="country-select-sup">
                                                                  <option value="XX">Select Country</option>
                                                                  <?php
                                                                  global $sp_countries_normilized;
                                                                  ?>
                                                                  <?php foreach($sp_countries_normilized as $country_code => $country_name){  ?>
                                                                  <option value="<?php echo esc_attr($country_code); ?>"><?php echo esc_html($country_name); ?></option>
                                                                  <?php } ?>
                                                                  <?php?>
                                                              </select>
                                                        <label>State</label> <input type="text" class="form-control" name="state" value="<?php echo  esc_attr( $supplier['state'] ); ?>" placeholder="State"/>
                                                        <label><?php echo esc_html( __( 'Zip Code', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="zip_code" value="<?php echo  esc_attr( $supplier['zip_code'] ); ?>" placeholder="Zip Code"/>
                                                        <label><?php echo esc_html( __( 'Account Number', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="account_no" value="<?php echo  esc_attr( $supplier['account_no'] ); ?>" placeholder="Account Number"/>
                                                        <label><?php echo esc_html( __( 'Account ID', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="account_id" value="<?php echo  esc_attr( $supplier['account_id'] ); ?>" placeholder="Account ID"/>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <label><?php echo esc_html( __( 'Phone', QA_MAIN_DOMAIN ) ); ?></label>
                                                    <input type="text" class="form-control" name="phone_number" value="<?php echo  esc_attr( $supplier['phone_number'] ); ?>" placeholder="Phone"/>
                                                        <label><?php echo esc_html( __( 'Assigned To', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="assigned_to" required="required" value="<?php echo  esc_attr( $supplier['assigned_to'] ); ?>" placeholder="Assigned To"/>
                                                        <label><?php echo esc_html( __( 'Ship To Location', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="ship_to_location" value="<?php echo  esc_attr( $supplier['ship_to_location'] ); ?>" placeholder="Ship To Location"/>
                                                        <label><?php echo esc_html( __( 'Discount', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="discount" value="<?php echo  esc_attr( $supplier['discount'] ); ?>" placeholder="Discount"/>
                                                        <label><?php echo esc_html( __( 'Tax Rate', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="tax_rate" value="<?php echo  esc_attr( $supplier['tax_rate'] ); ?>" placeholder="Tax Rate"/>
                                                        <label><?php echo esc_html( __( 'Lead Times (in weeks)', QA_MAIN_DOMAIN ) ); ?>*</label>
                                                        <input type="text" class="form-control" name="lead_times" required="required" value="<?php echo  esc_attr( $supplier['lead_times'] ); ?>" placeholder="Lead Times (in weeks) *"/> <label><?php echo esc_html( __( 'Payment Terms', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="payment_terms" value="<?php echo  esc_attr( $supplier['payment_terms'] ); ?>" placeholder="Payment Terms"/>
                                                        <label><?php echo esc_html( __( 'Delivery Terms', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="delivery_terms" value="<?php echo  esc_attr( $supplier['delivery_terms'] ); ?>" placeholder="Delivery Terms"/>
                                                    </div>
                                                </div>
                                            </form>
                                            <?php ?>
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

<?php require_once __DIR__ . '/../' . 'footer.php';
