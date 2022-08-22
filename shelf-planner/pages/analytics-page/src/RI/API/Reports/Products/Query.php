<?php // -*- coding: utf-8 -*-
/**
 * Class for parameter-based Products Report querying.
 *
 * Example usage:
 * $args = array(
 *          'before'       => '2018-07-19 00:00:00',
 *          'after'        => '2018-07-05 00:00:00',
 *          'page'         => 2,
 *          'categories'   => array(15, 18),
 *          'products'     => array(1,2,3)
 *         );
 * $report = new \QuickAssortments\COG\RI\API\Reports\Products\Query( $args );
 * $mydata = $report->get_data();
 *
 **/

namespace QuickAssortments\COG\RI\API\Reports\Products;

defined('ABSPATH') || exit;

use QuickAssortments\COG\RI\API\Reports\Query as ReportsQuery;

/**
 * API\Reports\Products\Query.
 */
class Query extends ReportsQuery
{
    /**
     * Get product data based on the current query vars.
     *
     * @throws \Exception
     *
     * @return array|mixed|object|void
     */
    public function get_data()
    {
        $args = apply_filters('qa_cog_reports_products_query_args', $this->get_query_vars());

        $data_store = \WC_Data_Store::load('qa-report-products');
        $results    = $data_store->get_data($args);

        return apply_filters('qa_cog_reports_products_select_query', $results, $args);
    }

    /**
     * Valid fields for Products report.
     *
     * @return array
     */
    protected function get_default_query_vars()
    {
        return [];
    }
}
