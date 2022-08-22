<?php // -*- coding: utf-8 -*-

namespace QuickAssortments\COG\CoG;

require_once QA_COG_BASE_PATH.'libs/SimpleXLSX/SimpleXLSX.php';
require_once QA_COG_BASE_PATH.'libs/SimpleXLSXGen/SimpleXLSXGen.php';

use QuickAssortments\COG\Helpers\AbstractSettings;

/**
 * Class Settings.
 *
 * @author   Khan M Rashedun-Naby <naby88@gmail.com>
 *
 * @package  QuickAssortments\COG\Premium\Settings
 *
 * @since    1.0.0
 */
class Settings extends AbstractSettings
{
    /**
     * Settings constructor.
     */
    public function __construct()
    {
        $this->id              = 'qa_cog_main_settings';
        $this->label           = __('Cost of Goods & Margins Settings', 'qa-cost-of-goods-margins');
        $this->default_section = $this->id;

        add_action('wp_ajax_import_qa_costs', [$this, 'import_qa_costs']);
        add_action('init', [$this, 'export_qa_costs']);
    }

    /**
     * Initialization Method.
     *
     * @return void
     *
     * @since 1.0.0
     *
     */
    public function init()
    {
        add_filter('qa_cog_main_tabs_array', [$this, 'tabs_array']);
        add_action('qa_cog_main_tabs', [$this, 'sections']);
        add_action('qa_cog_settings_save_' . $this->id, [$this, 'save_data']);
        add_filter('qa_cog_main_sections_' . $this->id, [$this, 'html_page_content']);
        add_action('qa_cog_main_' . $this->id, [$this, 'output_sections']);

        return $this;
    }

    /**
     * Import QA Costs
     */
    public function import_qa_costs()
    {
        if(isset($_POST) && isset($_FILES['file'])) {
            // Check if file is in XLSX format before import
            if($xlsx = \QASimpleXLSX::parse($_FILES['file']['tmp_name'])) {
                foreach($xlsx->rows() as $item => $row) {
                    if($item > 0 && $row[0] != "") {
                        update_post_meta($row[0], 'sp_cost', $row[2]);
                    }
                }

                wp_send_json([
                    'status' => 'true'
                ]);
            }
        }
    }

    /**
     * Export QA Costs
     */
    public function export_qa_costs()
    {
        if(isset($_GET['export_qa_products'])) {
            $products = new \WP_Query([
                'post_type' => 'product',
                'posts_per_page' => -1
            ]);

            // Prepare Array to Export
            $data[] = [
                'Product ID',
                'Product Name',
                'New Cost',
            ];

            // Add Rows to Array
            while($products->have_posts()) : $products->the_post();
                // Prepare Variables
                $product_id = get_the_ID();
                $title = get_the_title();
                $qa_price = get_post_meta($product_id, 'sp_cost', true) ? get_post_meta($product_id, 'sp_cost', true) : '0';

                $data[] = [(string) $product_id, (string) $title, (string) $qa_price];
            endwhile;

            // Export Data
            $table = \SimpleXLSXGen::fromArray($data)->downloadAs('export-qa-costs.xlsx');

            die;
        }
    }

    /**
     * Method for outputting html content.
     *
     * @return void
     *
     * @since 1.0.0
     *
     */
    public function html_page_content()
    {
        parent::output_fields($this->get_settings());
    }

    /**
     * Get settings array.
     *
     * @return array
     *
     * @since 1.0.0
     *
     */
    public function get_settings()
    {
        $section  = isset($_GET['section']) ? sanitize_key($_GET['section']) : $this->default_section;
        $settings = [];

        switch ($section) {
            case $this->id:
                $settings = apply_filters('qa_cog_main_' . $section . '_body', [
                    [
                        'title' => __('Product Settings', 'qa-cost-of-goods-margins'),
                        'type'  => 'title',
                        'id'    => $this->id . '_product_settings_section',
                    ],
                    [
                        'title'    => __('Show Markup', 'qa-cost-of-goods-margins'),
                        'desc_tip' => __('Show/Hide Markup column in product list', 'qa-cost-of-goods-margins'),
                        'id'       => $this->id . '_show_markup_checkbox',
                        'default'  => 'no',
                        'type'     => 'checkbox',
                    ],
                    [
                        'title'    => __('Show Stock Value', 'qa-cost-of-goods-margins'),
                        'desc_tip' => __('Show/Hide Stock Value column in product list', 'qa-cost-of-goods-margins'),
                        'id'       => $this->id . '_show_stock_value_checkbox',
                        'default'  => 'no',
                        'type'     => 'checkbox',
                    ],
                    [
                        'title'    => __('Show Margin', 'qa-cost-of-goods-margins') . ' ' . esc_html__('(incl. Tax)', 'qa-cost-of-goods-margins'),
                        'desc_tip' => __('Show/Hide Margin(incl. Tax) column in product list', 'qa-cost-of-goods-margins'),
                        'id'       => $this->id . '_show_margin_incl_tax_checkbox',
                        'default'  => 'no',
                        'type'     => 'checkbox',
                    ],
                    [
                        'title'    => __('Show Margin', 'qa-cost-of-goods-margins') . ' ' . esc_html__('(excl. Tax)', 'qa-cost-of-goods-margins'),
                        'desc_tip' => __('Show/Hide Margin(excl. Tax) column in product list', 'qa-cost-of-goods-margins'),
                        'id'       => $this->id . '_show_margin_excl_tax_checkbox',
                        'default'  => 'no',
                        'type'     => 'checkbox',
                    ],
                    [
                        'type' => 'sectionend',
                        'id'   => $this->id . '_product_settings_section',
                    ],
                    [
                        'type'         => 'submit_button',
                        'display_text' => __('Save Changes', 'qa-cost-of-goods-margins'),
                        'id'           => '',
                        'name'         => 'save',
                        'class'        => '',
                    ],

                    [
                        'type'      => 'nonce',
                        'nonce_key' => 'qa-cog-settings',
                    ],
                ]);
                break;
            default:
                wp_die('Either there is no defined page for current section or you do not have the sufficient permission to access this page.');
                break;
        }

        // echo 'qa_cog_get_settings_' . $this->id;
        return apply_filters('qa_cog_main_get_settings_' . $this->id, $settings);
    }

    /**
     * Method for rendering the sections.
     *
     * @param $sections
     *
     * @return mixed
     *
     * @since 1.0.0
     *
     */
    public function sections($sections)
    {
        $sections[$this->id] = __('Cost of Goods Settings', 'qa-cost-of-goods-margins');

        return $sections;
    }

    /**
     * Save settings.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function save_data()
    {
        $settings = $this->get_settings();
        parent::save_fields($settings);
    }
}
