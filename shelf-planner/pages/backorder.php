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
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }

                        .sphd-p {
                            font-size: 16px;
                        }
                    </style>
                    <h2 class="purchase-or-title"><?php echo esc_html(__( 'Backorder', QA_MAIN_DOMAIN )); ?></h2>
                    <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Enable buy products if they not in stock', QA_MAIN_DOMAIN )); ?></span>
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
                            <form method="post">
                                <h4 style="margin-bottom: 1em"><?php echo esc_html( __( 'Backorder', QA_MAIN_DOMAIN ) ); ?></h4>
                                <p style="font-size: inherit;"><input type="checkbox" id="id-backorder" name="backorder" <?php echo esc_attr( (get_option('sp.backorder', false) ? 'checked' : '') ); ?>> <label for="id-backorder" style="font-weight: normal"> <?php echo esc_html( __('Enable Backorder', QA_MAIN_DOMAIN) ); ?></label></p>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="button-settings-po" value="<?php echo esc_attr( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-store-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
                <?php include __DIR__ . '/../' . "popups.php"; ?>
            </div>
        </div>
    </div>
</div>
