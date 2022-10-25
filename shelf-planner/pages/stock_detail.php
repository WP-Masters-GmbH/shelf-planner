<?php
require_once __DIR__ . '/admin_page_header.php';

?><?php require_once __DIR__ . '/../' . 'header.php'; ?>
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
                    <div class="d-flex nav-link-line" style="margin-top: 40px;">
                    <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_stock_detail' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_stock_detail')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Stock Perfomance', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_manage_store' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_manage_store')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Manage Inventory', QA_MAIN_DOMAIN)); ?></span></a>
                          <!-- <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Stock Detail', QA_MAIN_DOMAIN)); ?></span></a> -->
                        </div>
                    <?php do_action('after_page_header'); ?>
                    <div class="wrap">
                        <div id="root"></div>
                    </div>
                    <div class="mr-40">
              <div style="margin-top: 60px;" class="d-flex justify-content-between">
                <table class="leaderboards-tab">
                  <tr>
                    <td class="leadeboards-tab-title">
                      Top Categories - Lost Sales
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title" style="border-right: 1px solid #E2E2E3;">
                      Category
                    </td>
                    <td class="leaderboards-tab-title" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: center">
                      Ideal Stock
                    </td>
                    <td class="leaderboards-tab-title" style="width: 200px; text-align: right;">
                      Lost Sales (Value)
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3;">
                      Handbags
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%;">
                      10
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right;">
                      € 1.146,03
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3;">
                      Wallets
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%;">
                      6
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right;">
                    € 604.18
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3;">
                    Shoulderbags
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%;">
                      2
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right;">
                    € 221.71
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3;">
                    Doctor Bag
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%;">
                      1
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right;">
                    € 120.00
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3; border-bottom: none;">
                    Weekend Bag
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%; border-bottom: none;">
                      1
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right; border-bottom: none;">
                    € 100.00
                    </td>
                  </tr>
                </table>

                <table class="leaderboards-tab">
                  <tr>
                    <td class="leadeboards-tab-title">
                      Top Products - Lost Sales
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title" style="border-right: 1px solid #E2E2E3;">
                      Product
                    </td>
                    <td class="leaderboards-tab-title" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: center">
                      Lost Sales (Units)
                    </td>
                    <td class="leaderboards-tab-title" style="width: 200px; text-align: right;">
                      Lost Sales (Value)
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3;">
                      Iris Tan
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%;">
                      10
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right;">
                      € 1.146,03
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3;">
                      Wallets
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%;">
                      6
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right;">
                    € 604.18
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3;">
                    Shoulderbags
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%;">
                      2
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right;">
                    € 221.71
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3;">
                    Doctor Bag
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%;">
                      1
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right;">
                    € 120.00
                    </td>
                  </tr>
                  <tr>
                    <td class="leaderboards-tab-title content-tabs" style="border-right: 1px solid #E2E2E3; border-bottom: none;">
                    Weekend Bag
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 150px; border-right: 1px solid #E2E2E3; text-align: right; padding-right: 6%; border-bottom: none;">
                      1
                    </td>
                    <td class="leaderboards-tab-title content-tabs" style="width: 200px; text-align: right; border-bottom: none;">
                    € 100.00
                    </td>
                  </tr>
                </table>
              </div>
            </div>
        </div>
        <?php include __DIR__ . '/../' . "popups.php"; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../' . 'footer.php';
