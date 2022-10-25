<?php
require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';
?>
<style>
  .border-for-des {
    max-width: 288px;
  }

  .user-design {
    max-width: 288px;
  }

  .line {
    border-top: 0.5px solid #A5A5A5;
    margin-top: 80px;
  }

  .user-interface-subtitle {
    font-size: 14px;
  }

  .border-for-des {
    padding-left: 1rem;
    padding-top: 1rem;
    padding-bottom: 5rem;
  }

  .wp-core-ui select {
    max-width: 30%;
    border: 1px solid #DEDEDF;
    color: #131313;
  }

  .wp-core-ui select:focus, .wp-core-ui select:active, .wp-core-ui select:hover {
    color: #131313;
    border-color: #DEDEDF;
  }

  .settings-store-label {
    margin: 0;
    opacity: 0.8;
  }

  .integrations-progress > label {
    font-weight: 400 !important;
    margin: 0;
    margin-top: 0.25rem;
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
                    <h2 class="purchase-or-title"><?php echo esc_html(__( 'Your Integrations', QA_MAIN_DOMAIN )); ?></h2>
                    <div class="d-flex nav-link-line" style="margin-top: 40px;">
                    <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_overview_integrations' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_overview_integrations')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Overview', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'sp_integrations' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=sp_integrations')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('API’s & Integrations', QA_MAIN_DOMAIN)); ?></span></a>
                    </div>
					<?php do_action( 'after_page_header' ); ?>
					<div class="row">
            <div class="col-md-6">
              <h2 class="purchase-or-title"><?php echo esc_html(__( 'Your Current Store Integration', QA_MAIN_DOMAIN )); ?></h2>
              <span class='purchase-or-subtitle'><?php echo esc_html(__( 'These are the current API’s and integrations for your store.', QA_MAIN_DOMAIN )); ?></span>
              <p class="mt-40 mb-40 currently-running">
                You are currently running Shelf Planner for WooCommerce.
              </p>
              <div class="user-old-design user-design user-integrations">
                <img class="old-design" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>../assets/img/currently-running.png">
                <div class="border-for-des">
                  <h2 class="user-interface-title mb-2">
                    WooCommerce
                  </h2>
                  <span class="user-interface-subtitle m-0">
                  Your account is connected to WooCommerce commerce platform.
                  </span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
            <h2 class="purchase-or-title"><?php echo esc_html(__( 'Import Historical Data', QA_MAIN_DOMAIN )); ?></h2>
              <span class='purchase-or-subtitle'><?php echo esc_html(__( 'This tool populates historical analytics data by processing orders and product data created prior to activating my.shelfplanner', QA_MAIN_DOMAIN )); ?></span>
              <div class="d-flex flex-column mt-40">
                <label><?php echo esc_html( __( 'Import Historical Data', QA_MAIN_DOMAIN ) ); ?></label>
                <select class="integrations-select">
                  <option value="0">All</option>
                  <option value="1">All</option>
                  <option value="2">All</option>
                </select>
              </div>
              <div class="mt-4 mb-4 d-flex align-items-end">
                <?php echo  sp_settings_get_checkbox( 'Skip previously imported customers and orders' ) ?>
              </div>
              <div class="integrations-progress mb-4">
                <div class="integrations-progress-bar">
                  0%
                </div>
              <label><?php echo esc_html( __( 'Imported Orders and Refunds 0 of 496', QA_MAIN_DOMAIN ) ); ?></label>
              </div>
              <div class="integrations-progress mb-4">
                <div class="integrations-progress-bar">
                  0%
                </div>
              <label><?php echo esc_html( __( 'Imported Product Settings 0 of 65', QA_MAIN_DOMAIN ) ); ?></label>
              </div>
              <div class="mb-5">
                <span class="integrations-status">Status: Nothing To Import</span>
              </div>
              <div class="d-flex" style="gap: 5px">
                <button class="btn-start-integ">
                  Start Import
                </button>
                <button class="btn-delete-integ">
                  Delete Previously Imported Data
                </button>
              </div>
            </div>

          </div>
          <div class="line"></div>
                </div>
            </div>
            <?php include __DIR__ . '/../' . "popups.php"; ?>

        </div>
    </div>
</div>
