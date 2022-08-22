<?php // -*- coding: utf-8 -*-
/**
 * REST API Product Categories ControllerSP.
 *
 * Handles requests to /products/categories.
 *
 * @package QuickAssortmentsSP\COG\RISP\API
 */

namespace QuickAssortmentsSP\COG\RI\API;

defined('ABSPATH') || exit;

/**
 * Class ProductCategoriesSP.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortmentsSP\COG\RISP\API
 * @extends \WC_REST_Product_Categories_Controller
 *
 * @since   2.0.0
 */
final class ProductCategoriesSP extends \WC_REST_Product_Categories_Controller
{
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'sp/v1';
}
