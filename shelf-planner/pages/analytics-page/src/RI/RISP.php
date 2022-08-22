<?php // -*- coding: utf-8 -*-

namespace QuickAssortmentsSP\COG\RI;

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Class Retail Insights.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortmentsSP\COG\RISP
 *
 * @since   2.0.0
 */
final class RISP
{
    /**
     * RISP constructor.
     *
     * @since  2.0.0
     *
     * @retuen void
     */
    public function __construct()
    {
    }

    /**
     * Initial Method.
     *
     * @return object
     *
     * @since 2.0.0
     *
     */
    public function init()
    {
        // Add currency symbol to orders endpoint response.
        add_filter('woocommerce_rest_prepare_shop_order_object', [$this, 'add_currency_symbol_to_order_response']);

        // Hook in data stores.
        add_filter('woocommerce_data_stores', [$this, 'add_data_stores']);
        // REST API extensions init.
        add_action('rest_api_init', [$this, 'rest_api_init']);

        return $this;
    }

    /**
     * Add the currency symbol (in addition to currency code) to each OrderSP
     * object in REST API responses. For use in formatCurrency().
     *
     * @param {WP_REST_Response} $response REST response object.
     * @returns {WP_REST_Response}
     */
    public function add_currency_symbol_to_order_response($response)
    {
        $response_data                    = $response->get_data();
        $currency_code                    = $response_data['currency'];
        $currency_symbol                  = get_woocommerce_currency_symbol($currency_code);
        $response_data['currency_symbol'] = html_entity_decode($currency_symbol);
        $response->set_data($response_data);

        return $response;
    }

    /**
     * Init REST API.
     */
    public function rest_api_init()
    {
        $controllers = [
            'QuickAssortmentsSP\COG\RI\API\ProductCategoriesSP',
            'QuickAssortmentsSP\COG\RI\API\Reports\Products\ControllerSP',
            'QuickAssortmentsSP\COG\RI\API\Reports\Products\Stats\ControllerSP',
            'QuickAssortmentsSP\COG\RI\API\LeaderboardsSP',
        ];

        $controllers = apply_filters('qa_cog_rest_controllers', $controllers);

        foreach ($controllers as $controller) {
            $this->$controller = new $controller();
            $this->$controller->register_routes();
        }
    }

    /**
     * Adds data stores.
     *
     * @param array $data_stores List of data stores.
     *
     * @return array
     */
    public function add_data_stores($data_stores)
    {
        return array_merge(
            $data_stores,
            [
                'qa-report-products'       => 'QuickAssortmentsSP\COG\RI\API\Reports\Products\DataStoreSPSP',
                'qa-report-products-stats' => 'QuickAssortmentsSP\COG\RI\API\Reports\Products\Stats\DataStoreSP',
            ]
        );
    }
}
