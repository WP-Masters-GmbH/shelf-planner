<?php // -*- coding: utf-8 -*-
/**
 * Reports Data Store Interface.
 *
 */

namespace QuickAssortmentsSP\COG\RI\API\Reports;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Reports data store interface.
 *
 * @since 3.5.0
 */
interface DataStoreInterfaceSP
{
    /**
     * Get the data based on args.
     *
     * @param array $args QuerySP parameters.
     *
     * @return stdClass|WP_Error
     */
    public function get_data($args);
}
