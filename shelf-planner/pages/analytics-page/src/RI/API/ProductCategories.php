<?php // -*- coding: utf-8 -*-
/**
 * REST API Product Categories Controller.
 *
 * Handles requests to /products/categories.
 *
 * @package QuickAssortments\COG\RI\API
 */

namespace QuickAssortments\COG\RI\API;

defined('ABSPATH') || exit;

/**
 * Class ProductCategories.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortments\COG\RI\API
 * @extends \WC_REST_Product_Categories_Controller
 *
 * @since   2.0.0
 */
final class ProductCategories extends \WC_REST_Product_Categories_Controller
{
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'sp/v1';
}
