<?php
require_once __DIR__ . '/admin_page_header.php';

?><?php require_once __DIR__ . '/../' . 'header.php'; ?>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
        <?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">

                <?php include SP_PLUGIN_DIR_PATH . "pages/header_js.php"; ?>
                <!-- container opened -->
                <div class="container">
                    <h2><?php echo esc_html(__( 'Store Performance', QA_MAIN_DOMAIN )); ?></h2>
                    <?php do_action('after_page_header'); ?>
                    <div class="wrap">
                        <div id="root"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../' . 'footer.php';
