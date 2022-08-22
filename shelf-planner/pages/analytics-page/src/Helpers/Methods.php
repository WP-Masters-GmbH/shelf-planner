<?php // -*- coding: utf-8 -*-

namespace QuickAssortments\COG\Helpers;

/**
 * Class Methods.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortments\COG\Helpers
 *
 * @since   2.1.0
 */
final class Methods
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
        $meta = QA_COG_PREFIX . 'cost';

        $product = wc_get_product($product);

        if ('product_variation' !== $product->post_type) {
            return get_post_meta($product->get_id(), $meta, true);
        }

        if ('yes' === get_post_meta($product->get_parent_id(), '_qa_cog_enable_generic_cost', true)) {
            return get_post_meta($product->get_parent_id(), $meta, true);
        }

        return get_post_meta($product->get_id(), $meta, true);
    }
}
