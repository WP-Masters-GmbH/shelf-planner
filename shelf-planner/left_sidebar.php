<?php if (display_admin_part() == false) : ?>

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
    </style>
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar sidebar-scroll">
        <div class="main-sidebar-header active">
            <a class="desktop-logo logo-light active" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner')); ?>"><img src="<?php echo esc_url(plugin_dir_url(__FILE__)); ?>assets/img/brand/logo.png?1" class="main-logo" style="width: 199px; height: 28px;" alt="logo"></a>
        </div>
        <div class="main-sidemenu">
            <ul class="side-menu">
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page'] == 'shelf_planner_retail_insights') ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_retail_insights')); ?>"><i class="fa fa-line-chart"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo esc_html(__('Store Performance', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page'] == 'shelf_planner') ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner')); ?>"><i class="fa fa-tachometer"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo esc_html(__('Stock Analyses', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="sub-slide">
                    <a class="side-menu__item" href="#" onclick="jQuery('#id-menu-po-root').slideToggle(); return false;"><i class="fa
                    fa-tasks"></i>&nbsp; &nbsp;<span class="side-menu__label"><?php echo esc_html(__('Purchase Orders', QA_MAIN_DOMAIN)); ?></span></a>
                    <ul style="<?php echo esc_attr(in_array(sanitize_text_field($_GET['page']), array(
                                    'shelf_planner_purchase_orders',
                                    'shelf_planner_po_create_po',
                                    'shelf_planner_po_orders',
                                )) ? '' : 'display: none'); ?>" id="id-menu-po-root" class="side-menu">
                        <li class="slide">
                            <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_po_create_po' ? 'sub-slide_active active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_po_create_po')); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo esc_html(__('New Order', QA_MAIN_DOMAIN)); ?></span></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_po_orders' ? 'sub-slide_active active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_po_orders')); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo esc_html(__('Orders History', QA_MAIN_DOMAIN)); ?></span></a>
                        </li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_suppliers' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_suppliers')); ?>"><i class="fa fa-truck"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo esc_html(__('Suppliers', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_warehouses' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_warehouses')); ?>"><i class="fa fa-archive"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo esc_html(__('Warehouses', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_product_management' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_product_management')); ?>"><i class="fa fa-database"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo esc_html(__('Product Management', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_backorder' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_backorder')); ?>"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo esc_html(__('Backorder', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="sub-slide">
                    <a class="side-menu__item" href="#" onclick="jQuery('#id-menu-settings-root').slideToggle(); return false"><i class="fa fa-wrench"></i>&nbsp; &nbsp;<span class="side-menu__label"><?php echo esc_html(__('Settings', QA_MAIN_DOMAIN)); ?></span></a>
                    <ul style="<?php echo esc_attr(in_array(sanitize_text_field($_GET['page']), array(
                                    'shelf_planner_settings_forecast',
                                    'shelf_planner_settings_po',
                                    'shelf_planner_settings_product',
                                    'shelf_planner_settings_store',
                                    'shelf_planner_settings_category_mapping',
                                )) ? '' : 'display: none'); ?>" id="id-menu-settings-root" class="side-menu">
                        <li class="slide">
                            <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_forecast' ? 'sub-slide_active active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_forecast')); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo esc_html(__('Forecast Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_po' ? 'sub-slide_active active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_po')); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo esc_html(__('PO Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_product' ? 'sub-slide_active active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_product')); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo esc_html(__('Product Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_store' ? 'sub-slide_active active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_store')); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo esc_html(__('Store Settings', QA_MAIN_DOMAIN)); ?></span></a>
                        </li>
                        <li class="slide">
                            <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_settings_category_mapping' ? 'sub-slide_active active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_category_mapping')); ?>"><span style="margin-left: 12px" class="side-menu__label"> <i class="fa fa-arrow-circle-right"></i>&nbsp;&nbsp;<?php echo esc_html(__('Category Mapping', QA_MAIN_DOMAIN)); ?></span></a>
                        </li>
                    </ul>
                </li>
                <li class="slide">
                    <a class="side-menu__item <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'sp_integrations' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=sp_integrations')); ?>"><i class="fa fa-cloud-upload"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo esc_html(__('Integrations', QA_MAIN_DOMAIN)); ?></span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item" href="<?php echo esc_url(admin_url('')); ?>"><i class="fa fa-reply-all"></i>&nbsp;&nbsp;<span class="side-menu__label"><?php echo esc_html(__('Exit to WP Admin', QA_MAIN_DOMAIN)); ?></span></a>
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