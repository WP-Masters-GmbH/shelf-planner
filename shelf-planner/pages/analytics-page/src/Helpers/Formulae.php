<?php // -*- coding: utf-8 -*-

namespace QuickAssortments\COG\Helpers;

/**
 * Class Formulae.
 *
 * @author   Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package  QuickAssortments\COG\Helpers
 *
 * @since    1.0.0
 */
final class Formulae
{
    /**
     * Getting stock value.
     *
     * @param float|int $cost
     * @param float|int $stock_value
     *
     * @return float|int
     */
    public static function stock_value($cost, $stock_value)
    {
        if (! $stock_value || ! $cost) {
            return 0;
        }

        return self::format($cost * $stock_value);
    }

    /**
     * Formatting numbers.
     *
     * @param $input
     *
     * @return float|int
     */
    public static function format($input)
    {
        return number_format($input, 2, wc_get_price_decimal_separator(), wc_get_price_thousand_separator());
    }

    /**
     * Calculates profit based on cost and revenue.
     *
     * @param float|int $cost
     * @param float|int $revenue
     *
     * @return float|int
     */
    public static function profit($cost, $revenue)
    {
        return self::format($revenue - $cost);
    }

    /**
     * Calculates markup based on cost and revenue.
     *
     * @param float|int $cost
     * @param float|int $revenue
     *
     * @return float|int
     */
    public static function markup($cost, $revenue)
    {
        $cost = abs($cost);
        return $cost ? self::format(($revenue - $cost) / $cost) : false;
    }

    /**
     * Calculates margin based on cost and revenue.
     *
     * @param float|int $cost
     * @param float|int $revenue
     *
     * @return float|int
     */
    public static function margin($cost, $revenue)
    {
        return is_numeric($revenue) && is_numeric($cost) ? self::format(($revenue - $cost) * 100 / $revenue) : false;
    }
}
