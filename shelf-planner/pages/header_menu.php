<style>
    .header-menu .nav-item {
        display: flex;
    }

    .header-submenu .nav-item {
        display: flex;
    }

    .header-menu .nav-item a.nav-link {
        text-decoration: underline;
        padding-right: 5px;
        font-weight: 600;
        font-size: 18px;
    }

    .header-menu .nav-item:first-child a.nav-link {
        padding-left: 0;
    }

    .header-menu .nav-item a.active {
        text-decoration: none;
        color: black;
    }

    .header-submenu .nav-item a.nav-link {
        text-decoration: underline;
    }

    .header-submenu .nav-item:first-child a.nav-link {
        padding-left: 0;
    }

    .header-submenu .nav-item a.active {
        text-decoration: none;
        color: black;
    }

    .header-menu a.help-icon {
        color : #802731;
    }

    .header-submenu a.help-icon {
        color : #802731;
    }

    .header-submenu .nav-item {
    display: flex;
    align-items: center;
    }

    .header-menu .nav-item {
        display: flex;
        align-items: center;
    }

    .header-submenu .nav-item a.nav-link {
    padding-right: 5px;
    }
</style>
<div class="header-menu">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link <?php echo esc_attr( sanitize_text_field($_GET['page']) == 'shelf_planner_retail_insights' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_retail_insights')); ?>"><?php echo esc_html(__('Store Performance', QA_MAIN_DOMAIN)); ?></a>
                <a href="#" data-pt-title="<?php echo esc_attr(__('Store Performance Help', QA_MAIN_DOMAIN)); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner')); ?>"><?php echo esc_html(__('Stock Analyses', QA_MAIN_DOMAIN)); ?></a>
                <a href="#" data-pt-title="<?php echo esc_attr(__('Stock Analyses Help', QA_MAIN_DOMAIN)); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo esc_attr( in_array(sanitize_text_field($_GET['page']), array(
                                        'shelf_planner_purchase_orders',
                                        'shelf_planner_po_create_po',
                                        'shelf_planner_po_orders'
                                    )) ? 'active' : '' ); ?>" aria-current="page" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_po_orders')); ?>"><?php echo esc_html(__('Purchase Orders', QA_MAIN_DOMAIN)); ?></a>
                <a href="#" data-pt-title="<?php echo esc_attr(__('Purchase Orders Help', QA_MAIN_DOMAIN)); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_suppliers' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_suppliers')); ?>"><?php echo esc_html(__('Suppliers', QA_MAIN_DOMAIN)); ?></a>
                <a href="#" data-pt-title="<?php echo esc_attr(__('Suppliers Help', QA_MAIN_DOMAIN)); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_warehouses' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_warehouses')); ?>"><?php echo esc_html(__('Warehouses', QA_MAIN_DOMAIN)); ?></a>
                <a href="#" data-pt-title="<?php echo esc_attr(__('Warehouses Help', QA_MAIN_DOMAIN)); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_product_management' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_product_management')); ?>"><?php echo esc_html(__('Product Management', QA_MAIN_DOMAIN)); ?></a>
                <a href="#" data-pt-title="<?php echo esc_attr(__('Product Management Help', QA_MAIN_DOMAIN)); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_backorder' ? 'active' : ''); ?>" aria-current="page" href="<?php echo admin_url('admin.php?page=shelf_planner_backorder'); ?>"><?php echo esc_html(__('Backorder', QA_MAIN_DOMAIN)); ?></a>
                <a href="#" data-pt-title="<?php echo esc_attr(__('Backorder Help', QA_MAIN_DOMAIN)); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo esc_attr(in_array(sanitize_text_field($_GET['page']), array(
                                        'shelf_planner_settings_forecast',
                                        'shelf_planner_settings_po',
                                        'shelf_planner_settings_product',
                                        'shelf_planner_settings_store',
                                        'shelf_planner_settings_category_mapping',
                                    )) ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_settings_forecast')); ?>"><?php echo esc_html(__('Settings', QA_MAIN_DOMAIN)); ?></a>
                <a href="#" data-pt-title="<?php echo esc_attr(__('Settings Help', QA_MAIN_DOMAIN)); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'sp_integrations' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url(admin_url('admin.php?page=sp_integrations')); ?>"><?php echo esc_html(__('Integrations', QA_MAIN_DOMAIN)); ?></a>
                <a href="#" data-pt-title="<?php echo esc_attr(__('Integrations Help', QA_MAIN_DOMAIN)); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
            </li>
        </ul>
</div>