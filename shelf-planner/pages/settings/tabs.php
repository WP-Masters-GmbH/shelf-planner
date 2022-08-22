<div class="header-submenu">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link <?php echo esc_attr(sanitize_text_field( $_GET['page'] ) == 'shelf_planner_settings_forecast' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url( admin_url('admin.php?page=shelf_planner_settings_forecast') ); ?>"><?php echo esc_html( __('Forecast Settings', QA_MAIN_DOMAIN) ); ?></a>
            <a href="#" data-pt-title="<?php echo esc_attr( __('Forecast Settings Help', QA_MAIN_DOMAIN) ); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo esc_attr(sanitize_text_field( $_GET['page'] ) == 'shelf_planner_settings_po' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url( admin_url('admin.php?page=shelf_planner_settings_po') ); ?>"><?php echo esc_html( __('PO Settings', QA_MAIN_DOMAIN) ); ?></a>
            <a href="#" data-pt-title="<?php echo esc_attr( __('PO Settings Help', QA_MAIN_DOMAIN) ); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo esc_attr(sanitize_text_field( $_GET['page'] ) == 'shelf_planner_settings_product' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url( admin_url('admin.php?page=shelf_planner_settings_product') ); ?>"><?php echo esc_html( __('Product Settings', QA_MAIN_DOMAIN) ); ?></a>
            <a href="#" data-pt-title="<?php echo esc_attr( __('Product Settings Help', QA_MAIN_DOMAIN) ); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo esc_attr(sanitize_text_field( $_GET['page'] ) == 'shelf_planner_settings_store' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url( admin_url('admin.php?page=shelf_planner_settings_store') ); ?>"><?php echo esc_html( __('Store Settings', QA_MAIN_DOMAIN) ); ?></a>
            <a href="#" data-pt-title="<?php echo esc_attr( __('Store Settings Help', QA_MAIN_DOMAIN) ); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo esc_attr(sanitize_text_field( $_GET['page'] ) == 'shelf_planner_settings_category_mapping' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url( admin_url('admin.php?page=shelf_planner_settings_category_mapping') ); ?>"><?php echo esc_html( __( 'Category Mapping', QA_MAIN_DOMAIN ) ); ?></a>
            <a href="#" data-pt-title="<?php echo esc_attr( __('Category Mapping Help', QA_MAIN_DOMAIN) ); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
        </li>
    </ul>
</div>