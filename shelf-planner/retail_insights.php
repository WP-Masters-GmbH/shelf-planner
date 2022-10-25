<?php
require_once __DIR__ . '/admin_page_header.php';

?><?php require_once __DIR__ . '/../' . 'header.php'; ?>
<style>
    .plugin-header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 64px;
    background: #F4F4F4;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 20px;
    padding-right: 40px;
  }

  .container > h2 {
    font-family: "Lato";
    font-weight: 700;
    font-size: 24px;
    line-height: 30px;
    margin-top: 64px;
  }

  .nav-link-line {
    position: relative;
    align-items: center;
    gap: 30px;
  }

  .nav-link-line:after {
    background-color: #e2e2e3;
    content: "";
    display: inline-block;
    height: 2px;
    position: absolute;
    top: 25px;
    width: 100%;
  }

  .nav-link-line > .nav-link-page > .side-menu__label{
    cursor: pointer;
    font-family: Lato !important;
    font-size: 13px !important;
    font-weight: 400 !important;
    line-height: 14px !important;
    text-decoration: none !important;
    color: #4e4e4e !important;
  }

  .nav-link-line > .nav-link-page > .side-menu__label:hover {
    color: #f98ab1 !important;
    transition: all 0.4s;
  }

  .nav-link-line > .active {
    background: none !important;
    padding: 0 !important;
    margin: 0 10px 0 10px !important;
    border: none;
    border-radius: 0 !important;
    display: flex;
    height: auto;
    font-weight: 700 !important;
  }

  .nav-link-line > .active > .side-menu__label {
    color: #f98ab1 !important;
    position: relative;
  }

  .nav-link-line > .active > .side-menu__label:before {
    background-color: #f98ab1;
    content: "";
    display: inline-block;
    height: 2px;
    position: absolute;
    top: 25px;
    width: 100%;
    z-index: 1000;
  }
</style>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
        <?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">
            <?php include __DIR__ . '/../' . "page_header.php"; ?>

                <?php include SP_PLUGIN_DIR_PATH . "pages/header_js.php"; ?>
                <!-- container opened -->
                <div class="container">
                    <h2 class="purchase-or-title"><?php echo esc_html(__( 'Store Performance', QA_MAIN_DOMAIN )); ?></h2>
                    <div class="d-flex nav-link-line" style="margin-top: 25px;">
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_home' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_home')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Home', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_retail_insights' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_retail_insights')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Store Perfomance', QA_MAIN_DOMAIN)); ?></span></a>
                        </div>
                    <?php do_action('after_page_header'); ?>
                    <div class="wrap">
                        <div id="root"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  document.getElementById("sidebar-title").innerHTML = "Home";
</script>
<?php require_once __DIR__ . '/../' . 'footer.php';
