<?php

require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';

// it allows us to use wp_handle_upload() function
if (!function_exists('wp_generate_attachment_metadata')){
	require_once(ABSPATH . "wp-admin" . '/includes/image.php');
	require_once(ABSPATH . "wp-admin" . '/includes/file.php');
	require_once(ABSPATH . "wp-admin" . '/includes/media.php');
}

if(isset($_FILES['sp_avatar_account'])) {
	$upload = wp_handle_upload(
		$_FILES['sp_avatar_account'],
		array( 'test_form' => false )
	);

	$_POST['sp_avatar_account'] = $upload[ 'url' ];
}


if(isset($_POST)) {
	$my_account_settings = get_option('sp_my_account_settings') ? array_merge(unserialize(get_option('sp_my_account_settings')), $_POST) : $_POST;
    update_option('sp_my_account_settings', serialize($my_account_settings));
}

$my_account_settings = unserialize(get_option('sp_my_account_settings'));

?>
<style>
      .wp-core-ui select {
      width: 22%;
      max-width: unset;
      border: 1px solid #DEDEDF;
      color: #B5B5B5;
      font-size: 14px;
      line-height: 17px;
      font-weight: 500;
      height: 38px;
    }

    .wp-core-ui select:focus, .wp-core-ui select:active, .wp-core-ui select:hover {
      outline: none;
      border-color: #DEDEDF;
      box-shadow: none;
      color: #B5B5B5;
    }

    input.form-control {
      width: 22%;
    }

    input.form-control:focus {
      border-color: #DEDEDF !important;
    }

    label {
      opacity: 0.9;
    }

    .small-form-control {
      width: 18% !important;
    }

    .image-upload img {
      width: 100px;
      height: 100px;
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
                    <h2 class="purchase-or-title"><?php echo esc_html(__( 'Welcome to your account,', QA_MAIN_DOMAIN )); ?> <?php if(isset($my_account_settings['first_name'])) { echo esc_html($my_account_settings['first_name']); } ?></h2>
                    <div class="d-flex nav-link-line" style="margin-top: 40px;">
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_my_account' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_my_account')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Overview', QA_MAIN_DOMAIN)); ?></span></a>
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_plans_payments' ? 'active nav-link-page_active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_plans_payments')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Plans & Payment', QA_MAIN_DOMAIN)); ?></span></a>
                    </div>
					<?php do_action( 'after_page_header' ); ?>
					<?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div style="position: relative;">
                        <div class="mt-30" style="margin-top: 50px">
                            <form method="post" enctype="multipart/form-data">
                            <div class="image-upload">
                              <label for="file-input">
                                <img class="circle-gal" src="<?php if(isset($my_account_settings['sp_avatar_account'])) { echo esc_url($my_account_settings['sp_avatar_account']); } else { echo esc_url( plugin_dir_url( __FILE__ ) )."../assets/img/circle-gal.png"; } ?>" >
                              </label>
                              <input id="file-input" name="sp_avatar_account" type="file" />
                            </div>
                                <h4 class="purchase-or-title" style="margin-bottom: 1em; margin-top: 40px;"><?php echo esc_html( __( 'Your Account', QA_MAIN_DOMAIN ) ); ?></h4>
                                <div class="mb-2">
                                  <label><?php echo esc_html( __( 'First name', QA_MAIN_DOMAIN ) ); ?></label> 
                                  <input type="text" class="form-control small-form-control" name="first_name" required="required" value="<?php if(isset($my_account_settings['first_name'])) { echo esc_attr($my_account_settings['first_name']); } ?>" placeholder="First Name"/>
                                </div>
                                <div class="mb-3">
                                  <label><?php echo esc_html( __( 'Last name', QA_MAIN_DOMAIN ) ); ?></label> 
                                  <input type="text" class="form-control small-form-control" name="last_name" required="required" value="<?php if(isset($my_account_settings['last_name'])) { echo esc_attr($my_account_settings['last_name']); } ?>" placeholder="Last Name"/>
                                </div>
                                <div class="mb-3">
                                  <label><?php echo esc_html( __( 'Primary Email Address (Shelf Planner ID)', QA_MAIN_DOMAIN ) ); ?></label> 
                                  <input type="text" class="form-control" name="email_address" required="required" value="<?php if(isset($my_account_settings['email_address'])) { echo esc_attr($my_account_settings['email_address']); } ?>" placeholder="Primary Email Address"/>
                                </div>
                                <div class="mb-3">
                                  <label><?php echo esc_html( __( 'Secondary Email Address', QA_MAIN_DOMAIN ) ); ?></label> 
                                  <input type="text" class="form-control" name="secondary_email" required="required" value="<?php if(isset($my_account_settings['secondary_email'])) { echo esc_attr($my_account_settings['secondary_email']); } ?>" placeholder="Secondary Email Address"/>
                                </div>
                                  <div>
                              <h2 class="purchase-or-title mb-40">
                                Company Information
                              </h2>
                              <div class="mb-3">
                                <label><?php echo esc_html( __( 'Company Name', QA_MAIN_DOMAIN ) ); ?></label> 
                                <input type="text" class="form-control" name="company_name" required="required" value="<?php if(isset($my_account_settings['company_name'])) { echo esc_attr($my_account_settings['company_name']); } ?>" placeholder="Company Name"/>
                              </div>
                                <div class="d-flex flex-column">
                                <label><?php echo esc_html( __( 'Country', QA_MAIN_DOMAIN ) ); ?></label> 
                                <select name="sp_countries_list">
                                          <option value="XX">Select Country</option>
                                          <?php
                                          global $sp_countries_normilized;
                                          ?>
                                          <?php foreach($sp_countries_normilized as $country_code => $country_name){  ?>
                                          <option value="<?php echo esc_attr($country_code); ?>" <?php if(isset($my_account_settings['sp_countries_list']) && $my_account_settings['sp_countries_list'] == $country_code) { echo esc_attr('selected'); } ?>><?php echo esc_html($country_name); ?></option>
                                          <?php } ?>
                                      </select>
                                      </div>
                            </div>
                            <input type="submit" style="width: 78px; height: 38px;" class="new-des-btn mt-40" value="Save"/>
                              </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include __DIR__ . '/../' . "popups.php"; ?>
        </div>
    </div>
</div>
