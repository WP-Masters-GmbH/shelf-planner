<?php

if ( !empty( $_POST ) ) {
    if ( isset( $_POST['save-backorder'] ) ) {
        update_option( 'sp.backorder', isset( $_POST['backorder'] ) ? 'enable' : '' );
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
                <?php include SP_PLUGIN_DIR_PATH . "pages/header_js.php"; ?>
                <!-- container opened -->
                <div class="container">
                    <h2><?php echo esc_html(__( 'Backorder', QA_MAIN_DOMAIN )); ?></h2>
                    <?php do_action('after_page_header'); ?>
                    <p class="mg-b-20"></p>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <form method="post" id="id-settings-product-form">
                                        <p class="mg-b-20"></p>
                                        <p style="font-size: inherit"><input type="checkbox" id="id-backorder" name="backorder" <?php echo esc_attr( (get_option('sp.backorder', false) ? ' checked="checked"' : '') ); ?>> <label for="id-backorder" style="font-weight: normal"> <?php echo esc_html( __('Enable Backorder', QA_MAIN_DOMAIN) ); ?></label></p>
                                        <p class="mg-b-20"></p>
                                        <input style="margin-top: 2em" type="submit" class="btn btn-sm btn-success" value="<?php echo  esc_html( __('Save Settings', QA_MAIN_DOMAIN) ); ?>" name="save-backorder" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php require_once __DIR__ . '/../' . 'footer.php';
