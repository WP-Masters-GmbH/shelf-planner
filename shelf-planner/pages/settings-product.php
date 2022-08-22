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
                <!-- container opened -->
                <div class="container">
	                <?php include SP_PLUGIN_DIR_PATH ."pages/header_js.php"; ?>
                    <style>
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }
                    </style>
                    <h2><?php echo esc_html(__( 'Settings', QA_MAIN_DOMAIN )); ?></h2>
                    <?php do_action( 'after_page_header' ); ?>
                    <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div class="card">
                        <div class="card-body">
                            <h4><?php echo esc_html( __( 'Product Settings', QA_MAIN_DOMAIN ) ); ?></h4>
                            <p class="mg-b-20"></p>
                            <p style="font-weight: bold; font-size: inherit; margin-bottom: 2em"><?php echo esc_html( __( 'Shelf Planner calculates an Ideal Stock per product based on your stores\' sales forecast.', QA_MAIN_DOMAIN ) ); ?></p>
                            <form method="post" id="id-settings-product-form">
                                <p style="margin-bottom: 2em; font-size: inherit">
                                    <input type="radio" name="po-stock-type" value="min_stock"
										<?php echo esc_attr( $po_stock_type == 'min_stock' ? 'checked="checked"' : '' ); ?>
                                    /><?php echo esc_html( __( 'Use Min Stock threshold for my products instead of Ideal Stock when present', QA_MAIN_DOMAIN ) ); ?>
                                </p>
                                <p class="mg-b-20"></p>
                                <p style="margin-bottom: 2em; font-size: inherit">
                                    <input type="radio" name="po-stock-type" value="ideal_stock"
										<?php echo esc_attr( $po_stock_type == 'ideal_stock' ? 'checked="checked"' : '' ); ?>
                                    /><?php echo esc_html( __( 'Always use Ideal Stock to calculate Order
                                    Proposals', QA_MAIN_DOMAIN ) ); ?>
                                </p>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="btn btn-sm btn-success" value="<?php echo esc_attr( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-product-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>