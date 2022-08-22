<?php // -*- coding: utf-8 -*-
/**
 * QuickAssortments Order Helpers.
 *
 * Quick Assortments Order helper methods.
 *
 * @package QuickAssortments\COG\Helpers
 */

namespace QuickAssortments\COG\Helpers;

defined('ABSPATH') || exit;

/**
 * Order helper class.
 */
class Order
{
    /**
     * Calculate shipping amount for line item/product as a total shipping amount ratio based on quantity.
     *
     * @param \WC_Order_Item $item  Line item from order.
     * @param \WC_Order      $order Order class instance.
     *
     * @return float|int
     */
    public static function get_item_shipping_amount($item, $order)
    {
        // Shipping amount loosely based on woocommerce code in includes/admin/meta-boxes/views/html-order-item(s).php
        // distributed simply based on number of line items.
        $product_qty = $item->get_quantity('edit');
        $order_items = $order->get_item_count();
        if (0 === $order_items) {
            return 0;
        }

        $total_shipping_amount = $order->get_shipping_total();

        return $total_shipping_amount / $order_items * $product_qty;
    }

    /**
     * Calculate shipping tax amount for line item/product as a total shipping tax amount ratio based on quantity.
     *
     * Loosely based on code in includes/admin/meta-boxes/views/html-order-item(s).php.
     *
     * @todo If WC is currently not tax enabled, but it was before (or vice versa), would this work correctly?
     *
     * @param \WC_Order_Item $item  Line item from order.
     * @param \WC_Order      $order Order class instance.
     *
     * @return float|int
     */
    public static function get_item_shipping_tax_amount($item, $order)
    {
        $order_items = $order->get_item_count();
        if (0 === $order_items) {
            return 0;
        }

        $product_qty               = $item->get_quantity('edit');
        $order_taxes               = $order->get_taxes();
        $line_items_shipping       = $order->get_items('shipping');
        $total_shipping_tax_amount = 0;
        foreach ($line_items_shipping as $item_id => $shipping_item) {
            $tax_data = $shipping_item->get_taxes();
            if ($tax_data) {
                foreach ($order_taxes as $tax_item) {
                    $tax_item_id                = $tax_item->get_rate_id();
                    $tax_item_total             = isset($tax_data['total'][$tax_item_id]) ? (float) $tax_data['total'][$tax_item_id] : 0;
                    $total_shipping_tax_amount += $tax_item_total;
                }
            }
        }
        return $total_shipping_tax_amount / $order_items * $product_qty;
    }

    /**
     * Calculates coupon amount for specified line item/product.
     *
     * Coupon calculation based on woocommerce code in includes/admin/meta-boxes/views/html-order-item.php.
     *
     * @param \WC_Order_Item $item Line item from order.
     *
     * @return float
     */
    public static function get_item_coupon_amount($item)
    {
        return floatval($item->get_subtotal('edit') - $item->get_total('edit'));
    }
}
