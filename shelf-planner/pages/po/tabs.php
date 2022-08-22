<div class="header-submenu">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link <?php echo esc_attr(sanitize_text_field( $_GET['page'] ) == 'shelf_planner_po_create_po' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url( admin_url('admin.php?page=shelf_planner_po_create_po') ); ?>"><?php echo esc_html( __( 'New Order', QA_MAIN_DOMAIN ) ); ?></a>
            <a href="#" data-pt-title="<?php echo esc_attr( __('New Order Help', QA_MAIN_DOMAIN) ); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo esc_attr(sanitize_text_field( $_GET['page'] ) == 'shelf_planner_po_orders' ? 'active' : ''); ?>" aria-current="page" href="<?php echo esc_url( admin_url('admin.php?page=shelf_planner_po_orders') ); ?>"><?php echo esc_html( __( 'Orders History', QA_MAIN_DOMAIN ) ); ?></a>
            <a href="#" data-pt-title="<?php echo esc_attr( __('Orders History Help', QA_MAIN_DOMAIN) ); ?>" data-pt-trigger="click" data-pt-gravity="bottom" data-pt-scheme="red" data-pt-size="tiny" class="help-icon protip"><i class="fas fa-question-circle"></i></a>
        </li>
    </ul>
</div>