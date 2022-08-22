<?php // -*- coding: utf-8 -*-

namespace QuickAssortmentsSP\COG\CoG;

use QuickAssortmentsSP\COG\Helpers;

/**
 * Class FieldsSP.
 *
 * @author   Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package  QuickAssortmentsSP\COG\Admin
 *
 * @since    1.0.0
 */
final class FieldsSP
{
    /**
     * @var string
     *
     * @since 1.0.0
     */
    private $prefix = QA_COG_PREFIXSP; // TODO: Use the constant QA_COG_PREFIXSP directly instead of prefix

    /**
     * @var string
     *
     * @since 1.0.0
     */
    private $name = '';

    /**
     * @var string
     *
     * @since 1.0.0
     */
    private $bi = '';

    /**
     * @var string
     *
     * @since 1.0.0
     */
    private $currency = '';

    /**
     * FieldsSP constructor.
     *
     * @param string $prefix
     *
     * @since 1.0.0
     *
     */
    public function __construct()
    {
        $this->name     = __('Cost of Goods & Margins', 'qa-cost-of-goods-margins');
        $this->bi       = 'background-image: url("' . QA_COG_BASE_URLSP . 'assets/img/icon-sq-bg.png")';
        $this->currency = 'UAH';
    }

    /**
     * Initiating hooks.
     *
     * @return object
     *
     * @since 1.0.0
     *
     */
    public function init()
    {
        /**
         * Adding cost field to product data tab.
         */
        add_action('woocommerce_product_options_pricing', [$this, 'add_cost_field_to_product_data_tab']);
        // Adding fields to variation admin panel
        add_action('woocommerce_variation_options_pricing', [$this, 'add_cost_field_to_variation_data_tab'], 10, 3);
        add_action('woocommerce_product_options_general_product_data', [$this, 'add_generic_cost_field_to_variable_product'], 10);

        /**
         * Adding cost field to bulk edit.
         */
        add_action('woocommerce_product_quick_edit_start', [$this, 'bulk_edit_cost_field']);

        /**
         * Saving cost field data.
         */
        add_action('woocommerce_admin_process_product_object', [$this, 'save_cost_field'], 10, 1);
        add_action('woocommerce_product_quick_edit_save', [$this, 'save_cost_field'], 10, 1);
        // Saving variation cost fields
        add_action('woocommerce_save_product_variation', [$this, 'save_variation_cost_field'], 10, 2);
        add_action('woocommerce_admin_process_product_object', [$this, 'save_variable_product_generic_cost_field'], 10, 1);

        return $this;
    }

    /**
     * Method for adding cost fields to product data tab.
     *
     * @return void
     *
     * @since 1.0.0
     *
     */
    public function add_cost_field_to_product_data_tab()
    {
        global $post;

        if (! ($product = wc_get_product($post)) instanceof \WC_Product) {
            return;
        }

        $cp = Helpers\MethodsSP::get_cost($product);
        $cp = apply_filters('qa_cog_product_pricing_cost_price', $cp, $product, $this->currency);

        $price          = apply_filters('qa_cog_product_price_incl_tax', wc_get_price_including_tax($product), $product);
        $price_excl_tax = apply_filters('qa_cog_product_price_excl_tax', wc_get_price_excluding_tax($product), $product);

        $fields['cost']            = [
            'id'          => $this->prefix . 'cost',
            'style'       => $this->bi,
            'class'       => 'qa-input-field',
            'value'       => $cp,
            'data_type'   => 'price',
            'placeholder' => '0',
            'label'       => __('Cost Price', 'qa-cost-of-goods-margins') . ' (' . $this->currency . ')',
        ];
        $fields['stock_value']     = [
            'id'                => $this->prefix . 'stock_value',
            'style'             => $this->bi,
            'class'             => 'qa-input-field',
            'value'             => $product->get_manage_stock() ? Helpers\FormulaeSP::stock_value($cp, $product->get_stock_quantity()) : '–',
            'data_type'         => 'price',
            'placeholder'       => '0',
            'label'             => __('Stock Value', 'qa-cost-of-goods-margins') . ' (' . $this->currency . ')',
            'custom_attributes' => ['readonly' => 'true'],
            'desc_tip'          => true,
            'description'       => __('Stock management for this product must need to be turned on', 'qa-cost-of-goods-margins'),
        ];
        $fields['mark_up']         = [
            'id'                => $this->prefix . 'mark_up',
            'style'             => $this->bi,
            'class'             => 'qa-input-field',
            'value'             => ($mu = Helpers\FormulaeSP::markup($cp, $price)) ? $mu : '-',
            'data_type'         => 'price',
            'placeholder'       => '0',
            'label'             => __('Mark Up', 'qa-cost-of-goods-margins'),
            'custom_attributes' => ['readonly' => 'true'],
        ];
        $fields['margin_incl_tax'] = [
            'id'                => $this->prefix . 'margin',
            'style'             => $this->bi,
            'name'              => '',
            'class'             => 'qa-input-field',
            'value'             => ($m = Helpers\FormulaeSP::margin($cp, $price)) ? $m . '%' : '-',
            'data_type'         => 'price',
            'placeholder'       => '0',
            'label'             => __('Margin', 'qa-cost-of-goods-margins') . ' ' . esc_html__('(incl. Tax)', 'qa-cost-of-goods-margins') . '(%)',
            'custom_attributes' => ['readonly' => 'true'],
        ];
        $fields['margin_excl_tax'] = [
            'id'                => $this->prefix . 'margin_tax',
            'style'             => $this->bi,
            'name'              => '',
            'class'             => 'qa-input-field',
            'value'             => ($m = Helpers\FormulaeSP::margin($cp, $price_excl_tax)) ? $m . '%' : '-',
            'data_type'         => 'price',
            'placeholder'       => '0',
            'label'             => __('Margin', 'qa-cost-of-goods-margins') . ' ' . esc_html__('(excl. Tax)', 'qa-cost-of-goods-margins') . '(%)',
            'custom_attributes' => ['readonly' => 'true'],
        ];
        $fields                    = apply_filters('qa_cog_product_data_tab_fields', $fields, $product);

        /**
         * qa_cog_product_data_tab_before action.
         *
         * @param \WC_Product $variation
         * @param int         $loop
         *
         * @since 1.0.0
         */
        do_action('qa_cog_product_data_tab_before', $product);

        foreach ($fields as $field) {
            woocommerce_wp_text_input($field);
        }

        /**
         * qa_cog_product_data_tab_after action.
         *
         * @param \WC_Product $product
         * @param int         $loop
         *
         * @since 1.0.0
         */
        do_action('qa_cog_product_data_tab_after', $product);
    }

    /**
     * Adding cost fields to variation data tab.
     *
     * @param $loop
     * @param $variation_data
     * @param $variation
     *
     * @since 1.0.0
     *
     */
    public function add_cost_field_to_variation_data_tab($loop, $variation_data, $variation)
    {
        if ('product_variation' !== $variation->post_type) {
            return;
        }

        $variation = wc_get_product($variation->ID);

        $cp = Helpers\MethodsSP::get_cost($variation);
        $cp = apply_filters('qa_cog_variation_pricing_cost_price', $cp, $variation, $this->currency);

        $price          = apply_filters('qa_cog_variation_price_incl_tax', wc_get_price_including_tax($variation), $variation, $loop);
        $price_excl_tax = apply_filters('qa_cog_variation_price_excl_tax', wc_get_price_excluding_tax($variation), $variation, $loop);

        $fields['cost'] = [
            'id'            => $this->prefix . "cost_{$loop}",
            'name'          => $this->prefix . "cost[$loop]",
            'class'         => 'qa-input-field',
            'style'         => $this->bi,
            'value'         => $cp,
            'data_type'     => 'price',
            'placeholder'   => '0',
            'label'         => __('Cost Price', 'qa-cost-of-goods-margins') . ' (' . $this->currency . ')',
            'wrapper_class' => 'form-row form-row-first',
        ];

        $fields['stock_value'] = [
            'id'                => $this->prefix . "stock_value_{$loop}",
            'name'              => '',
            'class'             => 'qa-input-field',
            'style'             => $this->bi,
            'value'             => $variation->get_manage_stock() ? $this->currency . Helpers\FormulaeSP::stock_value($cp, $variation->get_stock_quantity()) : '–',
            'label'             => __('Stock Value', 'qa-cost-of-goods-margins') . ' (' . $this->currency . ')',
            'custom_attributes' => ['readonly' => 'true'],
            'wrapper_class'     => 'form-row form-row-last',
            'desc_tip'          => true,
            'description'       => __('Stock management for this product must need to be turned on', 'qa-cost-of-goods-margins'),
        ];

        $fields['mark_up'] = [
            'id'                => $this->prefix . "mark_up_{$loop}",
            'name'              => '',
            'class'             => 'qa-input-field',
            'style'             => $this->bi,
            'value'             => ($mu = Helpers\FormulaeSP::markup($cp, $price)) ? $mu : '-',
            'label'             => __('Mark Up', 'qa-cost-of-goods-margins'),
            'custom_attributes' => ['readonly' => 'true'],
            'wrapper_class'     => 'form-row form-row-first',
        ];

        $fields['margin_incl_tax'] = [
            'id'                => $this->prefix . "margin_{$loop}",
            'name'              => '',
            'class'             => 'qa-input-field',
            'style'             => $this->bi,
            'value'             => ($m = Helpers\FormulaeSP::margin($cp, $price)) ? $m . '%' : '-',
            'label'             => __('Margin', 'qa-cost-of-goods-margins') . ' ' . esc_html__('(incl. Tax)', 'qa-cost-of-goods-margins') . '(%)',
            'custom_attributes' => ['readonly' => 'true'],
            'wrapper_class'     => 'form-row form-row-last',
        ];
        $fields['margin_excl_tax'] = [
            'id'                => $this->prefix . "margin_tax_{$loop}",
            'name'              => '',
            'class'             => 'qa-input-field',
            'style'             => $this->bi,
            'value'             => ($m = Helpers\FormulaeSP::margin($cp, $price_excl_tax)) ? $m . '%' : '-',
            'label'             => __('Margin', 'qa-cost-of-goods-margins') . ' ' . esc_html__('(excl. Tax)', 'qa-cost-of-goods-margins') . '(%)',
            'custom_attributes' => ['readonly' => 'true'],
            'wrapper_class'     => 'form-row form-row-first',
        ];
        $fields                    = apply_filters('qa_cog_variation_data_tab_fields', $fields, $variation, $loop);

        /**
         * qa_cog_variation_data_tab_before action.
         *
         * @param \WC_Product_Variation $variation
         * @param int                   $loop
         *
         * @since 1.0.0
         */
        do_action('qa_cog_variation_data_tab_before', $variation, $loop);

        foreach ($fields as $field) {
            woocommerce_wp_text_input($field);
        }

        /**
         * qa_cog_variation_data_tab_after action.
         *
         * @param \WC_Product_Variation $variation
         * @param int                   $loop
         *
         * @since 1.0.0
         */
        do_action('qa_cog_variation_data_tab_after', $variation, $loop);
    }

    /**
     * Adding cost field to variable product general data tab.
     *
     * @since 2.1.0
     *
     */
    public function add_generic_cost_field_to_variable_product()
    {
        $product = wc_get_product(get_the_ID());
        if (! $product instanceof \WC_Product_Variable && ! $product->get_parent_id()) {
            return;
        }

        echo '<div class="options_group show_if_variable">';
        woocommerce_wp_text_input(
            [
                'id'          => $this->prefix . 'generic_cost',
                'value'       => Helpers\MethodsSP::get_cost($product->get_id()),
                'label'       => __('Generic Cost', 'qa-cost-of-goods-margins') . ' (' . $this->currency . ')',
                'class'       => 'qa-input-field',
                'style'       => $this->bi,
                'placeholder' => __('Enter the generic cost of good', 'qa-cost-of-goods-margins'),
                'desc_tip'    => 'true',
                'description' => __('Put the generic cost price here. Please check the below box to active it.', 'qa-cost-of-goods-margins'),
                'data_type'   => 'price',
            ]
        );
        woocommerce_wp_checkbox(
            [
                'id'          => $this->prefix . 'enable_generic_cost',
                'value'       => get_post_meta($product->get_id(), $this->prefix . 'enable_generic_cost', true),
                'label'       => '',
                'description' => __('Enable generic cost', 'qa-cost-of-goods-margins'),
            ]
        );
        echo '</div>';
    }

    /**
     * Adding cost fields to bulk edit fields.
     *
     * @since 1.0.0
     */
    public function bulk_edit_cost_field()
    {
        $args = [
            'name'  => esc_attr($this->prefix . 'cost'),
            'label' => esc_html__('Cost Price', 'qa-cost-of-goods-margins'),
            'style' => esc_attr($this->bi),
        ];

        Helpers\TemplateSP::include_template(__FUNCTION__, $args, 'admin/fields');
    }

    /**
     * Saving cost field data.
     *
     * @param object $product
     *
     * @return bool
     *
     * @since 1.0.0
     *
     */
    public function save_cost_field($product)
    {
        $cp = apply_filters('qa_cog_general_product_before_save_cost_price', wc_format_decimal(sanitize_text_field($_POST[$this->prefix . 'cost'])), $product);

        if (! isset($cp) || is_null($cp) || ! is_numeric($cp)) {
            return false;
        }

        return update_post_meta($product->get_id(), $this->prefix . 'cost', $cp);
    }

    /**
     * Saving variation cost field.
     *
     * @param int $variation_id
     * @param int $i
     *
     * @return bool|int
     *
     * @since 1.0.0
     *
     */
    public function save_variation_cost_field($variation_id, $i)
    {
        $cp = apply_filters('qa_cog_variation_product_before_save_cost_price', wc_format_decimal(sanitize_text_field($_POST[$this->prefix . 'cost'][$i])), $variation_id, $i);

        $gp = get_post_meta(
            wc_get_product($variation_id)->get_parent_id(),
            $this->prefix . 'enable_generic_cost',
            true
        );

        if (empty($cp) || ! is_numeric($cp) || 'yes' === $gp) {
            return false;
        }

        return update_post_meta($variation_id, $this->prefix . 'cost', $cp);
    }

    /**
     * Saving cost field in variable product general data tab.
     *
     * @since 2.1.0
     *
     * @param mixed $product
     */
    public function save_variable_product_generic_cost_field($product)
    {
        $cp = apply_filters('qa_cog_variable_product_before_save_parent_cost', wc_format_decimal(sanitize_text_field($_POST[$this->prefix . 'generic_cost'])), $product);

        // Update field enable meta
        update_post_meta(
            $product->get_id(),
            $this->prefix . 'enable_generic_cost',
            sanitize_text_field($_POST[$this->prefix . 'enable_generic_cost'])
        );

        if (! isset($cp) || is_null($cp) || ! is_numeric($cp)) {
            return false;
        }

        return update_post_meta($product->get_id(), $this->prefix . 'cost', $cp);
    }
}
