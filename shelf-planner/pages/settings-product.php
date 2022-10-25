<?php
if ( ! empty( $_POST ) ) {
	if ( isset( $_POST['save-product-settings'] ) ) {
		update_option( 'sp.settings.po_stock_type', sanitize_text_field( $_POST['po-stock-type'] ) );
	}
}
$po_stock_type = get_option( 'sp.settings.po_stock_type', 'ideal_stock' );
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
                    </style>
                    <h2 class="purchase-or-title" style="margin-top: 64px;"><?php echo esc_html(__( 'Settings', QA_MAIN_DOMAIN )); ?></h2>
                    <span class="purchase-or-subtitle"><?php echo esc_html(__( 'Here you can manage general settings for your store, forecast, orders, etc.', QA_MAIN_DOMAIN )); ?></span>
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
                        <div class="card-body" style="padding-left: 0;">
                            <h4 class="purchase-or-title"><?php echo esc_html( __( 'Product Settings', QA_MAIN_DOMAIN ) ); ?></h4>
                            <span class="purchase-or-subtitle"><?php echo esc_html(__( 'Product Settings are the parameters that help to calculate the right order proposals.', QA_MAIN_DOMAIN )); ?></span>
                            <p class="mg-b-20"></p>
                            <p style="font-weight: bold; font-size: inherit; margin-bottom: 1em"><?php echo esc_html( __( 'Shelf Planner calculates an Ideal Stock per product based on your stores\' sales forecast.', QA_MAIN_DOMAIN ) ); ?></p>
                            <form method="post" id="id-settings-product-form">
                                <p style="margin-bottom: 1em; font-size: inherit">
                                    <input type="radio" name="po-stock-type" value="min_stock"
										<?php echo esc_attr( $po_stock_type == 'min_stock' ? 'checked="checked"' : '' ); ?>
                                    /><?php echo esc_html( __( 'Use Min Stock threshold for my products instead of Ideal Stock when present', QA_MAIN_DOMAIN ) ); ?>
                                </p>
                                <p style="margin-bottom: 2em; font-size: inherit">
                                    <input type="radio" name="po-stock-type" value="ideal_stock"
										<?php echo esc_attr( $po_stock_type == 'ideal_stock' ? 'checked="checked"' : '' ); ?>
                                    /><?php echo esc_html( __( 'Always use Ideal Stock to calculate Order
                                    Proposals', QA_MAIN_DOMAIN ) ); ?>
                                </p>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="button-settings-po mb-40" value="<?php echo esc_attr( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-product-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php include __DIR__ . '/../' . "popups.php"; ?>
        </div>
    </div>
</div>
