<?php // -*- coding: utf-8 -*-

namespace QuickAssortments\COG\CoG;

use QuickAssortments\COG\Helpers;

/**
 * Class Columns.
 *
 * @author   Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package  QuickAssortments\COG\Admin
 *
 * @since    1.0.0
 */
final class Columns
{
    /**
     * @var string
     *
     * @since 1.0.0
     */
    private $currency = '';

    /**
     * Columns constructor.
     *
     * @param string $prefix
     *
     * @since 1.0.0
     *
     */
    public function __construct()
    {
        $this->currency = apply_filters('qa_cog_product_data_currency_filter', get_woocommerce_currency_symbol());
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
         * Adding columns to product backend.
         */
        add_filter('manage_edit-product_columns', [$this, 'additional_columns'], 10, 1);

        /**
         * Adding value to the custom columns at products backend.
         */
        add_action('manage_product_posts_custom_column', [$this, 'column_cost_price_and_stock_value_data'], 10, 2);

        return $this;
    }

    /**
     * Method for additional columns.
     *
     * @param array $columns
     *
     * @return array
     */
    public function additional_columns($columns)
    {
        $return = [];
        foreach ($columns as $k => $n) {
            $return[$k] = $n;

            if ('price' !== $k) {
                continue;
            }

            $return['cost_price'] 	     = esc_html__('Cost Price', 'qa-cost-of-goods-margins');
            $return['stock_value']	     = esc_html__('Stock Value', 'qa-cost-of-goods-margins');
            $return['markup'] 		        = esc_html__('Mark Up', 'qa-cost-of-goods-margins');
            $return['margin_incl_tax'] 	= esc_html__('Margin', 'qa-cost-of-goods-margins') . '<br />' . esc_html__('(incl. Tax)', 'qa-cost-of-goods-margins');
            $return['margin_excl_tax'] 	= esc_html__('Margin', 'qa-cost-of-goods-margins') . '<br />' . esc_html__('(excl. Tax)', 'qa-cost-of-goods-margins');
        }

        return apply_filters('qa_cog_additional_columns', $return);
    }

    /**
     * Method for adding columns cost price and stock value data.
     *
     * @param string $column
     * @param int    $post_id
     *
     * @return void
     *
     * @since 1.0.0
     *
     */
    public function column_cost_price_and_stock_value_data($column, $post_id)
    {
        // Instantiating individual product.
        $product = wc_get_product($post_id);

        $cp = Helpers\Methods::get_cost($product);
        $cp = is_numeric($cp) ? $cp : 0;

        if ($product->is_type('variable') || $product->is_type('grouped')) {
            $this->variable_products_column_data($column, $product, $cp);
        } else {
            $this->general_products_column_data($column, $product, $cp);
        }
    }

    /**
     * Method for general products column data.
     *
     * @since 1.0.0
     *
     * @param string $column
     * @param object $product
     * @param float  $cp
     *
     * @return void
     */
    public function general_products_column_data($column, $product, $cp)
    {
        $cp = apply_filters('qa_cog_column_cost_price_general', $cp, $product, $this->currency);

        $price          = apply_filters('qa_cog_column_incl_tax_price_general', wc_get_price_including_tax($product), $product, $this->currency);
        $price_excl_tax = apply_filters('qa_cog_column_excl_tax_price_general', wc_get_price_excluding_tax($product), $product, $this->currency);

        switch ($column) {
            case 'cost_price':
                echo $cp ? esc_html($this->currency . wc_format_localized_price($cp)) : '–';
                break;

            case 'stock_value':
                if ($product->get_manage_stock()) {
                    echo esc_html($this->currency . Helpers\Formulae::stock_value($cp, $product->get_stock_quantity()));
                } else {
                    echo '–';
                }
                break;

            case 'markup':
                $mu = Helpers\Formulae::markup($cp, $price);
                echo $mu ? esc_html($mu) : '–';
                break;

            case 'margin_incl_tax':
                $m = Helpers\Formulae::margin($cp, $price);
                echo $m ? esc_html(($m) . '%') : '–';
                break;

            case 'margin_excl_tax':
                $m = Helpers\Formulae::margin($cp, $price_excl_tax);
                echo $m ? esc_html(($m) . '%') : '–';
                break;
        }
    }

    /**
     * Method for showing the variable products data in the column.
     *
     * @param string $column
     * @param object $product
     * @param float  $cp
     *
     * @return void
     *
     * @since   1.0.0
     *
     */
    public function variable_products_column_data($column, $product, $cp)
    {
        $children = $product->get_children();

        $data = $this->get_children_data($children, $product->get_type());

        switch ($column) {
            case 'cost_price':
                $this->formatted_column_data($children, $data, $column, $this->currency);
                break;
            case 'stock_value':
                $this->formatted_column_data($children, $data, $column, $this->currency);
                break;
            case 'markup':
                $this->formatted_column_data($children, $data, $column);
                break;
            case 'margin_incl_tax':
                $this->formatted_column_data($children, $data, $column, '', '%');
                break;
            case 'margin_excl_tax':
                $this->formatted_column_data($children, $data, $column, '', '%');
                break;
        }
    }

    /**
     * Helper method to get children data for variable products.
     *
     * @param object $children
     * @param string $type
     *
     * @return array
     *
     * @since 1.0.0
     *
     */
    protected function get_children_data($children, $type = 'variation')
    {
        $data = [];

        foreach ($children as $child) {
            $product = wc_get_product($child);

            $data['price'][$child]          = apply_filters('qa_cog_column_price_incl_tax_' . $type, wc_get_price_including_tax($product), $product, $this->currency);
            $data['price'][$child]          = is_numeric($data['price'][$child]) ? $data['price'][$child] : null;
            $data['price_excl_tax'][$child] = apply_filters('qa_cog_column_price_excl_tax_' . $type, wc_get_price_excluding_tax($product), $product, $this->currency);
            $data['price_excl_tax'][$child] = is_numeric($data['price_excl_tax'][$child]) ? $data['price_excl_tax'][$child] : null;

            $data['cost_price'][$child] = Helpers\Methods::get_cost($product);
            $data['cost_price'][$child] = is_numeric($data['cost_price'][$child]) ? $data['cost_price'][$child] : null;
            $data['cost_price'][$child] = apply_filters('qa_cog_column_cost_price_' . $type, $data['cost_price'][$child], $product, $this->currency);

            if ('-' === $data['price'][$child] || '-' === $data['cost_price'][$child]) {
                $data['stock_value'][$child] = $data['markup'][$child] = $data['margin_incl_tax'][$child] = null;
                continue;
            }

            $data['stock_value'][$child] = null;
            if ($product->get_manage_stock()) {
                $data['stock_value'][$child] = Helpers\Formulae::stock_value($data['cost_price'][$child], $product->get_stock_quantity());
                $data['stock_value'][$child] = apply_filters('qa_cog_column_stock_value_' . $type, $data['stock_value'][$child], $product, $this->currency);
            }

            $data['markup'][$child] = Helpers\Formulae::markup($data['cost_price'][$child], $data['price'][$child]);
            $data['markup'][$child] = apply_filters('qa_cog_column_markup_' . $type, $data['markup'][$child], $product, $this->currency);

            $data['margin_incl_tax'][$child] = Helpers\Formulae::margin($data['cost_price'][$child], $data['price'][$child]);
            $data['margin_incl_tax'][$child] = apply_filters('qa_cog_column_margin_incl_tax_' . $type, $data['margin_incl_tax'][$child], $product, $this->currency);

            $data['margin_excl_tax'][$child] = Helpers\Formulae::margin($data['cost_price'][$child], $data['price_excl_tax'][$child]);
            $data['margin_excl_tax'][$child] = apply_filters('qa_cog_column_margin_excl_tax_' . $type, $data['margin_excl_tax'][$child], $product, $this->currency);

            $data['cost_price'][$child] = wc_format_localized_price($data['cost_price'][$child]);
        }

        return $data;
    }

    /**
     * Helper method to print data in column fields.
     *
     * @param array  $children
     * @param array  $data
     * @param string $column
     * @param string $prefix
     * @param string $suffix
     *
     * @return void
     *
     * @since 1.0.0
     *
     */
    protected function formatted_column_data($children = [], $data = [], $column = '', $prefix = '', $suffix = '')
    {
        if (empty($data[$column]) || empty($children)) {
            echo '–';

            return;
        }

        $data = $this->get_min_max($data[$column]);
        if (is_array($data)) {
            echo $prefix . $data['min'] . $suffix . ' – ' . $prefix . $data['max'] . $suffix;
        } elseif (! $data) {
            echo '–';
        } else {
            echo $prefix . $data . $suffix;
        }
    }

    /**
     * Helper function to get minimum and maximum.
     *
     * @param array $args
     *
     * @return mixed
     *
     * @since 1.0.0
     *
     */
    protected function get_min_max($args = [])
    {
        if (! is_array($args) || count($args) < 2) {
            return end($arg);
        }
        $data['min'] = array_filter($args, 'strlen') ? min(array_filter($args, 'strlen')) : 0;
        $data['max'] = max($args) ? max($args) : 0;
        if ($data['min'] == $data['max']) {
            return $data['min'];
        }

        return $data;
    }
}
