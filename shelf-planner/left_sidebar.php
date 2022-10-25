<?php 
if (display_admin_part() == false) : ?>

    <!-- main-sidebar -->
    <style>
        .sub-slide {
            margin: 0;
            border-radius: 0;
            padding: 10px 20px 10px 22px;
        }

        .sub-slide>ul>li {
            margin-top: 20px;
        }

        .sub-slide_active::before {
            background: #3093BA !important;
            content: '';
            width: 3px;
            height: 31px;
            position: absolute;
            left: 0;
        }

        .side-menu__label, .side-menu__item {
            color: #EBEBEB !important;
        }

        .side-menu__item.active .side-menu__label {
          color: #EBEBEB !important;
        }

        .side-menu__item.active, .side-menu__label:hover, .side-menu__item:hover, .side-menu__item:focus {
          color: #EBEBEB !important;
        }

        .slide:hover .side-menu__label, .slide:hover .angle, .slide:hover .side-menu__icon {
          color: #EBEBEB !important;
          fill: #EBEBEB !important;
        }

        .side-menu__label {
          font-family: 'Lato' !important;
          font-weight: 700 !important;
          font-size: 14px !important;
          line-height: 20px !important;
        }

        .app-sidebar .slide .side-menu__item.active::before {
          background: none !important;
        }

        .svg-inline--fa {
          display: none !important;
        }

.sidebar-title {
  font-family: "Lato";
  font-weight: 900;
  font-size: 20px;
  line-height: 24px;
  color: #FFF;
  margin-left: 32px;
  margin-bottom: 10px;
}

.side-menu .slide .side-menu__item {
  padding: 10px 20px 10px 32px;
}

.active > .side-menu .slide .side-menu__item {
  padding: 10px 22px 10px 10px !important;
}

.active {
    padding: 10px 22px 10px 10px !important;
    margin: 0 20px 0 20px !important;
    background-color: #874C5F !important;
    border: 1px solid #707070;
    border-radius: 4px !important;
    display: flex;
    height: 40px;
}

.app-sidebar {
  width: 260px;
  z-index: 1000000;
}

@media (max-width: 1400px) {
  .app-sidebar {
    width: 20%;
  }
}

    </style>
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar sidebar-scroll" style="background: #131313">

        <div class="main-sidemenu">
            <ul class="side-menu">
                <li class="slide d-flex" style="margin-bottom: 25px;">
                    <a class="side-menu__item" href="<?php echo esc_url(admin_url('admin.php?page=wc-admin')); ?>">
                    <svg style="margin-right: 10px" xmlns="http://www.w3.org/2000/svg" width="7.339" height="13.264" viewBox="0 0 7.339 13.264"><g transform="translate(3778.779 -2967.459) rotate(-45)"><line x2="8.379" transform="translate(-4774.5 -568.5)" fill="none" stroke="#b8b8b8" stroke-width="2"/><line x2="8.379" transform="translate(-4774.5 -568.5) rotate(90)" fill="none" stroke="#b8b8b8" stroke-width="2"/></g></svg>
                    <span style="color: #B8B8B8 !important" class="side-menu__label"><?php echo esc_html(__('WooCommerce Home', QA_MAIN_DOMAIN)); ?></span></a>
                </li>

                <h2 class="sidebar-title">Inventory</h2>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page'] == 'shelf_planner') || ($_GET['page'] == 'shelf_planner_retail_insights') ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner')); ?>"><span class="side-menu__label"><?php echo esc_html(__('Home', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page'] == 'shelf_planner_inventory') || ($_GET['page'] == 'shelf_planner_manage_store') ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_inventory')); ?>"><span class="side-menu__label"><?php echo esc_html(__('Inventory', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page'] == 'shelf_planner_order_proposals') || ($_GET['page'] == 'shelf_planner_po_create_po') || ($_GET['page'] == 'shelf_planner_po_orders') ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_order_proposals')); ?>"><span class="side-menu__label"><?php echo esc_html(__('Replenishment', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_product_management' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_product_management')); ?>"><span class="side-menu__label"><?php echo esc_html(__('Product Management', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page'] == 'shelf_planner_suppliers') || ($_GET['page'] == 'shelf_planner_suppliers_add_new') ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_suppliers')); ?>"><span class="side-menu__label"><?php echo esc_html(__('Suppliers', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <!--<li class="slide">
                    <a class="side-menu__item <?php /*echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_warehouses' ? 'active' : ''); */?>" href="<?php /*echo esc_url(admin_url('admin.php?page=shelf_planner_warehouses')); */?>"><span class="side-menu__label"><?php /*echo esc_html(__('Warehouses', QA_MAIN_DOMAIN)); */?></span></a>
                </li>-->
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page'] == 'shelf_planner_overview_integrations') || ($_GET['page'] == 'sp_integrations') ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_overview_integrations')); ?>"><span class="side-menu__label"><?php echo esc_html(__('Integrations', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page'] == 'shelf_planner_settings_store') || ($_GET['page'] == 'shelf_planner_settings_forecast') || ($_GET['page'] == 'shelf_planner_settings_product') || ($_GET['page'] == 'shelf_planner_settings_po') || ($_GET['page'] == 'shelf_planner_backorder') ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_store')); ?>"><span class="side-menu__label"><?php echo esc_html(__('Settings', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page'] == 'shelf_planner_my_account') || ($_GET['page'] == 'shelf_planner_plans_payments') ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_my_account')); ?>"><span class="side-menu__label"><?php echo esc_html(__('My Account', QA_MAIN_DOMAIN)); ?></span></a>
              </li>

            </ul>
        </div>
    </aside><!-- main-sidebar -->
    <script>
        function slideSettings() {
            jQuery("#id-menu-settings-root").slideToggle();
        }
    </script>
<?php endif; ?>