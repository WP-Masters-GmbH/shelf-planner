<?php // -*- coding: utf-8 -*-

namespace QuickAssortmentsSP\COG\Admin;

use QuickAssortmentsSP\COG\Helpers;

/**
 * Class PageSP.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortmentsSP\COG\Admin
 *
 * @since   1.0.0
 */
final class PageSP
{
    /**
     * Including necessary classes.
     *
     * @since    1.0.0
     *
     * @access   private
     *
     * @var array $classes Including necessary classes.
     */
    private $controller;

    /**
     * Product settings.
     *
     * @since    1.0.0
     *
     * @access   private
     *
     * @var array $classes Including necessary classes.
     */
    private $prod_sett = [];

    /**
     * PageSP constructor.
     *
     * @since  1.0.0
     *
     * @retuen void
     */
    public function __construct()
    {
        $this->controller = new PageControllerSP();
        $this->prod_sett  = [
            'markup'          => get_option('qa_cog_main_settings_show_markup_checkbox'),
            'stock_value'     => get_option('qa_cog_main_settings_show_stock_value_checkbox'),
            'margin_incl_tax' => get_option('qa_cog_main_settings_show_margin_incl_tax_checkbox'),
            'margin_excl_tax' => get_option('qa_cog_main_settings_show_margin_excl_tax_checkbox'),
        ];
    }

    /**
     * Initial Method.
     *
     * @return object
     *
     * @since 1.0.0
     *
     */
    public function init()
    {
        add_action('admin_menu', [$this, 'register_pages']);

        add_action('qa_cog_admin_page_callback', [$this, 'admin_page_content']);
        add_action('qa_cog_admin_page_body', [$this, 'qa_cog_admin_page_body'], 0, 1);

        add_filter('qa_cog_additional_columns', [$this, 'addition_columns_settings'], 0, 1);

        //add_action('wp_ajax_get_page_bulk_products', [$this, 'get_page_bulk_products']);
        //add_action('wp_ajax_save_bulk_products_settings', [$this, 'save_bulk_products_settings']);

        add_action('admin_enqueue_scripts', [$this, 'admin_scripts_and_styles']);
    }

    /**
     * Include Scripts And Styles on Admin Pages
     */
    public function admin_scripts_and_styles()
    {
        $nonce = wp_create_nonce('ajax_nonce');

        // Register styles
        wp_enqueue_style('wpm-assets-admin', QA_COG_BASE_URLSP.'assets/dist/wpm_assets/admin.css');

        // Register Scripts
        wp_enqueue_script('wpm-assets-admin', QA_COG_BASE_URLSP.'assets/dist/wpm_assets/admin.js');
        wp_localize_script('wpm-assets-admin', 'admin', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => $nonce
        ));
        wp_enqueue_script('wpm-assets-admin');
    }

    /**
     * Registers report pages.
     */
    public function register_pages()
    {
        $report_pages = [
            [
                'id'     => 'qa-retail-insights',
                'title'  => __('Retail Insights', 'woocommerce-admin'),
                'parent' => 'shelf_planner',
                'path'   => QA_COG_RI_SLUGSP,
            ]
        ];

        $report_pages = apply_filters('woocommerce_analytics_report_menu_items', $report_pages);

        foreach ($report_pages as $report_page) {
            if (! is_null($report_page)) {
                $this->controller->register_page($report_page);
            }
        }
    }

    /**
     * TemplateSP for Bulk Edit Products PageSP
     */
    public function bulk_edit_costs_page()
    {
        // SettingsSPSP for Get Products
        $search = isset($_POST['bulk_search']) ? sanitize_text_field($_POST['bulk_search']) : '';
        $page = isset($_POST['page']) ? sanitize_text_field($_POST['page']) : 1;
        $limit = 10;

        // Get Products
        $products = $this->bulk_products_list($page, $limit, $search);

        // Load TemplateSP PageSP
        include(QA_COG_BASE_PATHSP.'src/Templates/admin/settings/qa_bulk_edit_costs.php');
    }

    /**
     * Get Products List for Bulk Edit
     */
    public function bulk_products_list($page, $limit, $search)
    {
        $products = new \WP_Query([
            'post_type' => 'product',
            'posts_per_page' => $limit,
            'paged' => $page,
            's' => $search
        ]);

        return $products;
    }

    /**
     * Load PageSP Bulk List by Ajax
     */
    public function get_page_bulk_products()
    {
        ob_start();
        $this->bulk_edit_costs_page();
        $content = ob_get_clean();

        wp_send_json( [
            'status' => 'true',
            'html' => $content
        ]);
    }

    /**
     * Save Bulk Products SettingsSPSP
     */
    public function save_bulk_products_settings()
    {
        if(isset($_POST['prices_data'])) {
            $prices_data = $_POST['prices_data'];

            // Change Price products
            foreach($prices_data as $item) {
                update_post_meta($item['product_id'], '_qa_cog_cost', $item['new_price']);
            }

            $this->get_page_bulk_products();
        }
    }

    /**
     * Method for handling admin page callback.
     *
     * @return void
     *
     * @since 1.0.0
     *
     */
    public function admin_page_callback()
    {
        do_action('qa_cog_admin_page_callback');
    }

    /**
     * Method for admin page content.
     *
     * @return void
     *
     * @since 1.0.0
     *
     */
    public function admin_page_content()
    {
        $args = [
            'icon' => QA_COG_BASE_URLSP . 'assets/img/icon-sq-bg.png',
        ];
        Helpers\TemplateSP::include_template(__FUNCTION__, $args, 'admin/settings');
    }

    /**
     * Method for handling admin page body content.
     *
     * @param $page
     *
     * @retuen void
     *
     * @since  1.0.0
     *
     */
    public function qa_cog_admin_page_body($page)
    {
        if (QA_COG_SETTINGS_SLUGSP !== $page) {
            return;
        }

        $current_tab = empty($_GET['tab']) ? 'qa_cog_main_settings' : sanitize_key($_GET['tab']);
        $args        = [
            'module'      => 'main',
            'current_tab' => $current_tab,
            'page_slug'   => QA_COG_SETTINGS_SLUGSP,
        ];

        $args['tabs'] = apply_filters('qa_cog_' . $args['module'] . '_tabs_array', []);

        Helpers\TemplateSP::include_template(__FUNCTION__, $args, 'admin/settings');
    }

    /**
     * Implementing the settings for columns.
     *
     * @param array $columns
     *
     * @return mixed
     *
     * @since 1.0.0
     *
     */
    public function addition_columns_settings($columns)
    {
        foreach ($this->prod_sett as $pk => $ps) {
            if ($ps === 'no') {
                unset($columns[$pk]);
            }
        }

        return $columns;
    }
}
