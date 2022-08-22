<?php // -*- coding: utf-8 -*-
/**
 * QuickAssortments PWSP Helpers.
 *
 * Quick Assortments PWSP helper methods.
 *
 * @package QuickAssortmentsSP\COG\Helpers
 */

namespace QuickAssortmentsSP\COG\Helpers;

defined('ABSPATH') || exit;

final class PWSP
{
    /**
     * Constructor method.
     */
    public function __contruct()
    {
    }

    /**
     * Initialization method.
     */
    public function init()
    {
        add_filter('pwbe_product_columns', [$this, 'pwbe_product_columns'], 1, 1);
    }

    /**
     * Adding the extra cost price column.
     *
     * @param array $product_columns
     *
     * @return array
     */
    public function pwbe_product_columns($product_columns)
    {
        $column = [
            [
                'name'       => __('Cost price', 'qa-cost-of-goods-margins'),
                'type'       => 'currency',
                'table'      => 'meta',
                'field'      => 'sp_cost',
                'readonly'   => 'false',
                'visibility' => 'parent_variation',
                'sortable'   => 'true',
                'views'      => ['all', 'standard'],
            ]
        ];

        array_splice($product_columns, 4, 0, $column);

        return $product_columns;
    }
}
