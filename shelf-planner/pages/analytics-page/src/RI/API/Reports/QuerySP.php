<?php // -*- coding: utf-8 -*-
/**
 * Class for parameter-based Reports querying.
 */

namespace QuickAssortmentsSP\COG\RI\API\Reports;

defined('ABSPATH') || exit;

/**
 * Class QuerySP.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortmentsSP\COG\RISP\API\Reports
 *
 * @since   2.0.0
 */
abstract class QuerySP extends \WC_Object_Query
{
    /**
     * Get report data matching the current query vars.
     *
     * @return array|object of WC_Product objects
     */
    public function get_data()
    {
        /* translators: %s: Method name */
        return new \WP_Error('invalid-method', sprintf(__("Method '%s' not implemented. Must be overridden in subclass.", 'qa-cost-of-goods-margins'), __METHOD__), ['status' => 405]);
    }
}
