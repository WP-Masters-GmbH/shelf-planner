<?php

$remove_analytics_from = ['_qa_cog_retail_insights', '_qa_cog_bulk_edit_costs', '_qa_cog_retail_insights_settings'];

if(isset($_GET['page']) && in_array($_GET['page'], $remove_analytics_from) || strpos(home_url($_SERVER['REQUEST_URI']), 'wp-json/qa/v1') !== false) {

} elseif(!isset($_GET['post_type']) && !isset($_GET['post'])) {
	require_once('pages/analytics-page/qa-cost-of-goods-margins.php');
}

define('MAIN_SP_PATH', __DIR__);
define('MAIN_SP_URL', plugin_dir_url( __FILE__ ));

class SPHD_Admin {

    /**
     * Init
     */
    public static function init() {

        add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );

        add_filter( 'safe_style_css', function( $styles ) {
            $styles[] = 'display';
            return $styles;
        } );
        if ( display_admin_part() == false ) {
            add_action('admin_footer', array( __CLASS__, 'remove_other_menu'));
        }
        
        add_action('wp_ajax_get_page_bulk_products_sp', [__CLASS__, 'get_page_bulk_products_sp']);
        add_action('wp_ajax_save_bulk_products_settings_sp', [__CLASS__, 'save_bulk_products_settings_sp']);
        add_action( 'after_page_header', array(__CLASS__, 'add_header_menu') );
    }


    /**
     * Add header menu
     *
     * @return void
     */
    public static function add_header_menu() {

        if ( display_admin_part() == true ) {
            ob_start();
            include SP_PLUGIN_DIR_PATH . "pages/header_menu.php";
            ob_end_flush();
        }
    }

    /**
     * Delete Other Menu from Sidebar
     */
    public static function remove_other_menu()
    { ?>
        <style type="text/css">
            .toplevel_page_shelf_planner .wp-submenu.wp-submenu-wrap{
                display: none !important;
            }
            .toplevel_page__qa_cog_retail_insights label {
                display: contents !important;
            }
            .toplevel_page__qa_cog_retail_insights .woocommerce-calendar {
                height: 412px !important;
            }
        </style>
        <script>
            jQuery("a").each(function( index ) {
                if(jQuery(this).text() === 'Cost of Goods & Margins Settings SP' || jQuery(this).text() === 'Retail Insights Settings SP') {
                    jQuery(this).remove();
                }
            });
        </script>
        <?php
    }

    /**
     * Plugin Deactivation Event
     */
    public static function include_scripts_styles() {

	    $remove_pages = ['shelf_planner', 'sp_integrations', 'shelf_planner_retail_insights', 'shelf_planner_api_logs', 'shelf_planner_product_management', 'shelf_planner_purchase_orders', 'shelf_planner_po_create_po', 'shelf_planner_po_orders', 'shelf_planner_suppliers', 'shelf_planner_warehouses', 'quick_assortments_suppliers_page', 'shelf_planner_settings_forecast', 'shelf_planner_settings_po', 'shelf_planner_settings_product', 'shelf_planner_settings_store', 'shelf_planner_settings_category_mapping', 'shelf_planner_backorder'];

        if(isset($_GET['page']) && in_array($_GET['page'], $remove_pages)) {
	        wp_enqueue_script( 'sp-wp-deactivation-message', plugin_dir_url( __FILE__ ) . 'assets/js/sp_deactivate.js', array(), time(), true );
	        wp_enqueue_script( 'sp-moment', plugin_dir_url( __FILE__ ) . 'assets/js/moment.min.js', array( 'jquery' ), time(), false );
	        wp_enqueue_script( 'sp-tabulator', plugin_dir_url( __FILE__ ) . 'assets/js/tabulator.min.js', array( 'jquery', 'sp-moment' ), time(), false );
	        wp_enqueue_script( 'sp-xlsx', plugin_dir_url( __FILE__ ) . 'assets/js/xlsx.full.min.js', array( 'jquery' ), time(), true );
	        wp_enqueue_script( 'sp-apexcharts', plugin_dir_url( __FILE__ ) . 'assets/js/apexcharts.js', array( 'jquery' ), time(), false );
	        wp_enqueue_script( 'sp-custom', plugin_dir_url( __FILE__ ) . 'assets/js/custom.js', array( 'jquery' ), time(), false );
	        wp_enqueue_script( 'sp-drag-n-drop-new', plugin_dir_url( __FILE__ ) . 'assets/js/drag-n-drop-new.js', array( 'jquery' ), time(), false );
	        wp_enqueue_script( 'sp-tips', plugin_dir_url( __FILE__ ) . 'assets/protip/protip.min.js', array( 'jquery' ), time(), false );
	        wp_enqueue_style( 'sp-tabulator-css', plugin_dir_url( __FILE__ ) . 'assets/tabulator.min.css' );
	        wp_enqueue_style( 'sp-icons-css', plugin_dir_url( __FILE__ ) . 'assets/css/icons.css' );
	        //wp_enqueue_style( 'sp-sidebar-css', plugin_dir_url( __FILE__ ) . 'assets/plugins/sidebar/sidebar.css' );
	        wp_enqueue_style( 'sp-style-css', plugin_dir_url( __FILE__ ) . 'assets/css/style.css?48' );
	        wp_enqueue_style( 'sp-style-dark-css', plugin_dir_url( __FILE__ ) . 'assets/css/style-dark.css' );
	        wp_enqueue_style( 'sp-skin-modes-css', plugin_dir_url( __FILE__ ) . 'assets/css/skin-modes.css' );
	        wp_enqueue_style( 'sp-animate-css', plugin_dir_url( __FILE__ ) . 'assets/css/animate.css' );
	        wp_enqueue_style( 'sp-closed-sidemenu-css', plugin_dir_url( __FILE__ ) . 'assets/css/closed-sidemenu.css' );
	        wp_enqueue_style( 'sp-tips', plugin_dir_url( __FILE__ ) . 'assets/protip/protip.min.css' );

	        wp_localize_script( 'sp-custom', 'admin', array(
		        'ajaxurl' => admin_url( 'admin-ajax.php' ),
		        'nonce' => wp_create_nonce( 'sp-nonce' )
	        ) );
        }
    }

    /**
     * Add Menu Items
     */
    public static function register_menu() {
        $tmp_hooks[] = add_menu_page( __( 'Shelf Planner', QA_MAIN_DOMAIN ), __( 'Shelf Planner', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner', array( __CLASS__, 'stock_analyses_page' ), plugin_dir_url( __FILE__ ) . 'assets/img/menu-icon.png', 2 );
        $tmp_hooks[] = add_submenu_page( null, __( 'Integrations', QA_MAIN_DOMAIN ), __( 'Integrations', QA_MAIN_DOMAIN ), 'edit_others_posts', 'sp_integrations', array( __CLASS__, 'integrations_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'API Logs', QA_MAIN_DOMAIN ), __( 'API Logs', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_api_logs', array( __CLASS__, 'shelf_planner_api_logs' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Product Management', QA_MAIN_DOMAIN ), __( 'Product Management', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_product_management', array( __CLASS__, 'product_management_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Purchase Orders', QA_MAIN_DOMAIN ), __( 'Purchase Orders', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_purchase_orders', array( __CLASS__, 'purchase_orders_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Purchase Orders', QA_MAIN_DOMAIN ), __( 'Create PO', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_po_create_po', array( __CLASS__, 'shelf_planner_po_create_po_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Purchase Orders', QA_MAIN_DOMAIN ), __( 'PO Orders', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_po_orders', array( __CLASS__, 'shelf_planner_po_orders_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Suppliers', QA_MAIN_DOMAIN ), __( 'Suppliers', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_suppliers', array( __CLASS__, 'suppliers_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Warehouses', QA_MAIN_DOMAIN ), __( 'Warehouses', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_warehouses', array( __CLASS__, 'warehouses_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Suppliers', QA_MAIN_DOMAIN ), __( 'Suppliers', QA_MAIN_DOMAIN ), 'edit_others_posts', 'quick_assortments_suppliers_page', array( __CLASS__, 'suppliers_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_forecast', array( __CLASS__, 'shelf_planner_settings_forecast_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'PO Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_po', array( __CLASS__, 'shelf_planner_settings_po_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'Product Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_product', array( __CLASS__, 'shelf_planner_settings_product_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'Store Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_store', array( __CLASS__, 'shelf_planner_settings_store_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Shelf Planner Settings', QA_MAIN_DOMAIN ), __( 'Category Settings', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_settings_category_mapping', array( __CLASS__, 'shelf_planner_settings_category_mapping_page' ), null );
        $tmp_hooks[] = add_submenu_page( null, __( 'Backorder', QA_MAIN_DOMAIN ), __( 'Backorder', QA_MAIN_DOMAIN ), 'edit_others_posts', 'shelf_planner_backorder', array( __CLASS__, 'shelf_planner_backorder' ), null );

        foreach ($tmp_hooks as $hook){
            add_action( 'load-' . $hook, array(
                __CLASS__,
                'include_scripts_styles',
            ) );
        }

    }

    /**
     * Retail Insights Page
     */
    public static function shelf_planner_backorder() {
        require_once __DIR__ . '/pages/backorder.php';
    }


    /**
     * API Logs Page
     */
    public static function shelf_planner_api_logs() {
        global $wpdb;
        require_once __DIR__ . '/pages/api_logs.php';
    }

    /**
     * Integrations Page
     */
    public static function integrations_page() {
        global $wpdb;
        require_once __DIR__ . '/pages/integrations.php';
    }

    /**
     * Admin Page
     */
    public static function product_management_page()
    {
        global $wpdb;

        // Settings for Get Products
        $columns = isset($_POST['columns']) ? $_POST['columns'] : ['product_id', 'product_title', 'sp_cost', 'product_price', 'product_stock'];
        $search = isset($_POST['bulk_search']) ? sanitize_text_field($_POST['bulk_search']) : '';
        $page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : 1;
        $limit = 10;

        // Get Products
        $products = SPHD_Admin::bulk_products_list($page, $limit, $search);

        require_once __DIR__ . '/pages/product_management.php';
    }

    /**
     * Get Products List for Bulk Edit
     */
    public static function bulk_products_list($page, $limit, $search)
    {
        $products = new \WP_Query([
            'post_type' => ['product', 'product_variation'],
            'posts_per_page' => $limit,
            'paged' => $page,
            's' => $search
        ]);

        return $products;
    }

    /**
     * Load Page Bulk List by Ajax
     */
    public static function get_page_bulk_products_sp()
    {
        global $wpdb;

        // Settings for Get Products
        $columns = isset($_POST['columns']) ? $_POST['columns'] : ['product_id', 'product_title', 'sp_cost', 'product_price', 'product_stock'];
        $search = isset($_POST['bulk_search']) ? sanitize_text_field($_POST['bulk_search']) : '';
        $page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : 1;
        $limit = 10;

        // Get Products
        $products = SPHD_Admin::bulk_products_list($page, $limit, $search);

        ob_start();
        require_once __DIR__ . '/pages/bulk_edit_table.php';
        $content = ob_get_clean();

        wp_send_json( [
            'status' => 'true',
            'html' => $content
        ]);
    }

    /**
     * Save Bulk Products Settings
     */
    public static function save_bulk_products_settings_sp()
    {
        if(isset($_POST['products_data'])) {
            global $wpdb;

            $table = $wpdb->prefix.'sp_product_settings';
            $products_data = $_POST['products_data'];

            // Change Price products
            foreach($products_data as $item) {

                // Get Product
                $product = wc_get_product($item['product_id']);

                // Prepare Data to Save
                if($product->get_stock_quantity() && is_numeric($item['sp_cost'])) {
                    $item['sp_stock_value'] = $product->get_stock_quantity() * $item['sp_cost'];
                }
                $profit = (float) $product->get_price() - (float) $item['sp_cost'];
                $item['sp_mark_up'] = round( $profit / max($item['sp_cost'], 0.01 ), 2 );

                $with_tax    = wc_get_price_including_tax( $product );
                $without_tax = wc_get_price_excluding_tax( $product );

                if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
                    $with_tax    = $product->get_price_including_tax();
                    $without_tax = $product->get_price_excluding_tax();
                }

                if ( ! is_numeric( $with_tax ) || ! is_numeric( $without_tax ) ) {
                    $with_tax    = 0;
                    $without_tax = 0;
                }

                $tax_amount = $with_tax - $without_tax;
                $percent    = ( $tax_amount / max( $without_tax, 0.01 ) ) * 100;

                $item['sp_margin']     = round( $profit / max( $with_tax, 0.01 ) * 100, 2 ) . '%';
                $item['sp_margin_tax'] = round( ( (float) $product->get_price() - (float) $tax_amount - (float) $item['sp_cost'] ) / max( (float) $without_tax, 0.01 ) * 100, 2 );
	            update_post_meta($item['product_id'], 'variation_cost_price', $item['sp_cost']);

                // Save Data to DB
                if($wpdb->get_row("SELECT * FROM {$table} WHERE product_id='{$item['product_id']}'")) {
                    $wpdb->update($table, $item, ['product_id' => $item['product_id']]);
                } else {
                    $wpdb->insert($table, $item);
                }
            }

            SPHD_Admin::get_page_bulk_products_sp();
        }
    }


    /**
     * Stock Analyses Page
     */
    public static function stock_analyses_page() {
        global $wpdb;
        require_once __DIR__ . '/pages/stock_analyses.php';
    }

    /**
     * Purchase Orders Page
     */
    public static function purchase_orders_page() {
        global $wpdb;
        //require_once __DIR__ . '/pages/purchase_orders.php';
        require_once __DIR__ . '/pages/po_orders.php';
    }

    /**
     * Suppliers Page
     */
    public static function suppliers_page() {
        global $wpdb;
        require_once __DIR__ . '/pages/suppliers.php';
    }

    /**
     * Warehouses Page
     */
    public static function warehouses_page() {
        require_once __DIR__ . '/pages/warehouses.php';
    }

    /**
     * Forecast Settings page
     */
    public static function shelf_planner_settings_forecast_page() {
	    require_once __DIR__ . '/pages/settings-forecast.php';
    }

    /**
     * PO Settings page
     */
    public static function shelf_planner_settings_po_page() {
        require_once __DIR__ . '/pages/settings-po.php';
    }

    /**
     * Product Settings page
     */
    public static function shelf_planner_settings_product_page() {
        require_once __DIR__ . '/pages/settings-product.php';
    }

    /**
     * Store Settings page
     */
    public static function shelf_planner_settings_store_page() {
        require_once __DIR__ . '/pages/settings-store.php';
    }

    /**
     * Category Mapping page
     */
    public static function shelf_planner_settings_category_mapping_page() {
        require_once __DIR__ . '/pages/settings-category-mapping.php';
    }

    /**
     * Purchase Orders Orders page
     */
    public static function shelf_planner_po_orders_page() {
        require_once __DIR__ . '/pages/po_orders.php';
    }

    /**
     * Purchase Orders Create PO page
     */
    public static function shelf_planner_po_create_po_page() {
        require_once __DIR__ . '/pages/po_create_po.php';
    }

}