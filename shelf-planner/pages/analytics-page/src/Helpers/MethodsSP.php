<?php // -*- coding: utf-8 -*-

namespace QuickAssortmentsSP\COG\Helpers;

/**
 * Class MethodsSP.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortmentsSP\COG\Helpers
 *
 * @since   2.1.0
 */
final class MethodsSP
{
    /**
     * Retrieve cost price for products.
     *
     * @param int $product
     *
     * @since 2.1.0
     */
    public static function get_cost($product)
    {
        /*global $wpdb;

        $product_id = $product->get_id();

        $table = $wpdb->prefix.'sp_product_settings';
        $data = $wpdb->get_row("SELECT * FROM {$table} WHERE product_id='{$product_id}'");

        return $data->sp_cost;*/

        $meta = 'sp_cost';

        $product = wc_get_product($product);
	    $price = get_post_meta($product->get_id(), $meta, true);

        return $price;
    }
}
