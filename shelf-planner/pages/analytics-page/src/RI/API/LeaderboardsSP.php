<?php // -*- coding: utf-8 -*-
/**
 * REST API LeaderboardsSP ControllerSP
 * NOTE: THIS CLASS IS A MODIFIED VERSION FROM WOOCOMMERCE-ADMIN.
 */

namespace QuickAssortmentsSP\COG\RI\API;

defined('ABSPATH') || exit;

use QuickAssortmentsSP\COG\RI\API\Reports\Products\DataStoreSPSP as ProductsDataStore;

/**
 * Class LeaderboardsSP.
 *
 * @author  Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package QuickAssortmentsSP\COG\RISP\API
 * @extends \WC_REST_Data_Controller
 *
 * @since   2.0.0
 */
final class LeaderboardsSP extends \WC_REST_Data_Controller
{
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'sp/v1';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'leaderboards';

    /**
     * Register routes.
     */
    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_items'],
                    'permission_callback' => [$this, 'get_items_permissions_check'],
                    'args'                => $this->get_collection_params(),
                ],
                'schema' => [$this, 'get_public_item_schema'],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/allowed',
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_allowed_items'],
                    'permission_callback' => [$this, 'get_items_permissions_check'],
                ],
                'schema' => [$this, 'get_public_allowed_item_schema'],
            ]
        );
    }

    /**
     * Get the query params for collections.
     *
     * @return array
     */
    public function get_collection_params()
    {
        $params                    = [];
        $params['page']            = [
            'description'       => __('Current page of the collection.', 'qa-cost-of-goods-margins'),
            'type'              => 'integer',
            'default'           => 1,
            'sanitize_callback' => 'absint',
            'validate_callback' => 'rest_validate_request_arg',
            'minimum'           => 1,
        ];
        $params['per_page']        = [
            'description'       => __('Maximum number of items to be returned in result set.', 'qa-cost-of-goods-margins'),
            'type'              => 'integer',
            'default'           => 5,
            'minimum'           => 1,
            'maximum'           => 20,
            'sanitize_callback' => 'absint',
            'validate_callback' => 'rest_validate_request_arg',
        ];
        $params['after']           = [
            'description'       => __('Limit response to resources published after a given ISO8601 compliant date.', 'qa-cost-of-goods-margins'),
            'type'              => 'string',
            'format'            => 'date-time',
            'validate_callback' => 'rest_validate_request_arg',
        ];
        $params['before']          = [
            'description'       => __('Limit response to resources published before a given ISO8601 compliant date.', 'qa-cost-of-goods-margins'),
            'type'              => 'string',
            'format'            => 'date-time',
            'validate_callback' => 'rest_validate_request_arg',
        ];
        $params['persisted_query'] = [
            'description'       => __('URL query to persist across links.', 'qa-cost-of-goods-margins'),
            'type'              => 'string',
            'validate_callback' => 'rest_validate_request_arg',
        ];

        return $params;
    }

    /**
     * Check whether a given request has permission to read site data.
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_Error|bool
     */
    public function get_items_permissions_check($request)
    {
        if (! wc_rest_check_manager_permissions('reports', 'read')) {
            return new \WP_Error('woocommerce_rest_cannot_view', __('Sorry, you cannot list resources.', 'woocommerce'), ['status' => rest_authorization_required_code()]);
        }

        return true;
    }

    /**
     * Return all leaderboards.
     *
     * @param WP_REST_Request $request Request data.
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_items($request)
    {
        $persisted_query = json_decode($request['persisted_query'], true);
        $leaderboards    = $this->get_leaderboards($request['per_page'], $request['after'], $request['before'], $persisted_query);
        $data            = [];

        if (! empty($leaderboards)) {
            foreach ($leaderboards as $leaderboard) {
                $response = $this->prepare_item_for_response($leaderboard, $request);
                $data[]   = $this->prepare_response_for_collection($response);
            }
        }

        return rest_ensure_response($data);
    }

    /**
     * Get an array of all leaderboards.
     *
     * @param int    $per_page        Number of rows.
     * @param string $after           Items after date.
     * @param string $before          Items before date.
     * @param string $persisted_query URL query string.
     *
     * @return array
     */
    public function get_leaderboards($per_page, $after, $before, $persisted_query)
    {
        $leaderboards = [
            $this->get_categories_net_profit_leaderboard($per_page, $after, $before, $persisted_query),
            $this->get_products_net_profit_leaderboard($per_page, $after, $before, $persisted_query),
            $this->get_categories_net_margin_leaderboard($per_page, $after, $before, $persisted_query),
            $this->get_products_net_margin_leaderboard($per_page, $after, $before, $persisted_query),
        ];

        return apply_filters('qa_cog_leaderboards', $leaderboards, $per_page, $after, $before, $persisted_query);
    }

    /**
     * Get the data for the products leaderboard.
     *
     * @param int    $per_page        Number of rows.
     * @param string $after           Items after date.
     * @param string $before          Items before date.
     * @param string $persisted_query URL query string.
     */
    public function get_categories_net_profit_leaderboard($per_page, $after, $before, $persisted_query)
    {
        $products_data_store = new ProductsDataStore();
        $products_data       = $per_page > 0 ? $products_data_store->get_data(
            [
                'orderby'       => 'net_profit',
                'order'         => 'desc',
                'after'         => $after,
                'before'        => $before,
                'per_page'      => 9999,
                'extended_info' => true,
            ]
        )->data : [];


        $rows = [];
        $category_stats = [];
        foreach($products_data as $product) {
	        $category = null;
            if(!empty($product['extended_info']['category_ids'])) {
            	foreach($product['extended_info']['category_ids'] as $cat_id) {
		            $category = get_term($cat_id);

		            if($category) {
			            $category_url = get_edit_term_link($category->term_id);
			            $category_name = $category->name;

			            $category_stats[$category_name]['category_url'] = $category_url;
			            $category_stats[$category_name]['category_name'] = $category_name;
			            $category_stats[$category_name]['items_sold'] = isset($category_stats[$category_name]['items_sold']) ? $category_stats[$category_name]['items_sold'] + $product['items_sold'] : $product['items_sold'];
			            $category_stats[$category_name]['net_profit'] = isset($category_stats[$category_name]['net_profit']) ? $category_stats[$category_name]['net_profit'] + $product['net_profit'] : $product['net_profit'];
		            }
	            }
            } else {
	            $categories = get_the_terms( $product['product_id'], 'product_cat' );

	            if(empty($categories)) {
		            $variation = wc_get_product($product['product_id']);
		            if($variation->get_parent_id()) {
			            $categories = get_the_terms( $variation->get_parent_id(), 'product_cat' );
		            }
	            }

	            if(!empty($categories)) {
		            foreach ($categories as $category) {
			            $category_url = get_edit_term_link($category->term_id);
			            $category_name = $category->name;

			            $category_stats[$category_name]['category_url'] = $category_url;
			            $category_stats[$category_name]['category_name'] = $category_name;
			            $category_stats[$category_name]['items_sold'] = isset($category_stats[$category_name]['items_sold']) ? $category_stats[$category_name]['items_sold'] + $product['items_sold'] : $product['items_sold'];
			            $category_stats[$category_name]['net_profit'] = isset($category_stats[$category_name]['net_profit']) ? $category_stats[$category_name]['net_profit'] + $product['net_profit'] : $product['net_profit'];
		            }
	            }
            }
        }

        // Optimize Array and Sort
        $keys = array_column($category_stats, 'net_profit');
        array_multisort($keys, SORT_DESC, $category_stats);
        $category_stats = array_slice($category_stats, 0, $per_page);

	    $woocommerce_currency = get_woocommerce_currency();
	    $default = get_option( 'sp.default_currency', true);
	    $rate = get_option( 'sp.currency_rate', true);
	    $rate_add = get_option( 'sp.rate_add') ? get_option( 'sp.rate_add', true) : 0;

        foreach($category_stats as $stat) {
            $rows[] = [
                [
                    'display' => "<a href='{$stat['category_url']}'>{$stat['category_name']}</a>",
                    'value'   => $stat['category_name'],
                ],
                [
                    'display' => $stat['items_sold'],
                    'value'   => $stat['items_sold'],
                ],
                [
                    'display' => $woocommerce_currency != $default ? html_entity_decode(get_woocommerce_currency_symbol($default)).(round($stat['net_profit'] * ($rate + $rate_add))) : wc_price($stat['net_profit']),
                    'value'   => $woocommerce_currency != $default ? $stat['net_profit'] * ($rate + $rate_add) : $stat['net_profit'],
                ],
            ];
        }

        return [
            'id'      => 'categories_net_profit',
            'label'   => __('Top Categories - Net Profit', 'qa-cost-of-goods-margins'),
            'headers' => [
                [
                    'label' => __('Category', 'qa-cost-of-goods-margins'),
                ],
                [
                    'label' => __('Items Sold', 'qa-cost-of-goods-margins'),
                ],
                [
                    'label' => __('Net Profit', 'qa-cost-of-goods-margins'),
                ],
            ],
            'rows'    => $rows,
        ];
    }

    /**
     * Get the data for the products leaderboard.
     *
     * @param int    $per_page        Number of rows.
     * @param string $after           Items after date.
     * @param string $before          Items before date.
     * @param string $persisted_query URL query string.
     */
    public function get_products_net_profit_leaderboard($per_page, $after, $before, $persisted_query)
    {
        $products_data_store = new ProductsDataStore();
        $products_data       = $per_page > 0 ? $products_data_store->get_data(
            [
                'orderby'       => 'net_profit',
                'order'         => 'desc',
                'after'         => $after,
                'before'        => $before,
                'per_page'      => $per_page,
                'extended_info' => true,
            ]
        )->data : [];

	    $woocommerce_currency = get_woocommerce_currency();
	    $default = get_option( 'sp.default_currency', true);
	    $rate = get_option( 'sp.currency_rate', true);
	    $rate_add = get_option( 'sp.rate_add') ? get_option( 'sp.rate_add', true) : 0;

        $rows = [];
        foreach ($products_data as $product) {
            $product_url  = get_edit_post_link($product['product_id']);
            $product_name = isset($product['extended_info']) && isset($product['extended_info']['name'])
                ? $product['extended_info']['name']
                : '';

            $rows[] = [
                [
                    'display' => "<a href='{$product_url}'>{$product_name}</a>",
                    'value'   => $product_name,
                ],
                [
                    'display' => $product['items_sold'],
                    'value'   => $product['items_sold'],
                ],
                [
	                'display' => $woocommerce_currency != $default ? html_entity_decode(get_woocommerce_currency_symbol($default)).(round($product['net_profit'] * ($rate + $rate_add))) : wc_price($product['net_profit']),
	                'value'   => $woocommerce_currency != $default ? $product['net_profit'] * ($rate + $rate_add) : $product['net_profit'],
                ],
            ];
        }

        return [
            'id'      => 'products_net_profit',
            'label'   => __('Top Products - Net Profit', 'qa-cost-of-goods-margins'),
            'headers' => [
                [
                    'label' => __('Product', 'qa-cost-of-goods-margins'),
                ],
                [
                    'label' => __('Items Sold', 'qa-cost-of-goods-margins'),
                ],
                [
                    'label' => __('Net Profit', 'qa-cost-of-goods-margins'),
                ],
            ],
            'rows'    => $rows,
        ];
    }

    /**
     * Get the data for the products leaderboard.
     *
     * @param int    $per_page        Number of rows.
     * @param string $after           Items after date.
     * @param string $before          Items before date.
     * @param string $persisted_query URL query string.
     */
    public function get_categories_net_margin_leaderboard($per_page, $after, $before, $persisted_query)
    {
        $products_data_store = new ProductsDataStore();
        $products_data       = $per_page > 0 ? $products_data_store->get_data(
            [
                'orderby'       => 'net_margin',
                'order'         => 'desc',
                'after'         => $after,
                'before'        => $before,
                'per_page'      => 9999,
                'extended_info' => true,
            ]
        )->data : [];


	    $rows = [];
	    $category_stats = [];
	    foreach($products_data as $product) {
		    $category = null;
		    if(!empty($product['extended_info']['category_ids'])) {
			    foreach ( $product['extended_info']['category_ids'] as $cat_id ) {
				    $category = get_term( $cat_id );

				    if ( $category ) {
					    $category_url  = get_edit_term_link( $category->term_id );
					    $category_name = $category->name;

					    $category_stats[ $category_name ]['category_url']     = $category_url;
					    $category_stats[ $category_name ]['category_name']    = $category_name;
					    $category_stats[ $category_name ]['items_sold']       = isset( $category_stats[ $category_name ]['items_sold'] ) ? $category_stats[ $category_name ]['items_sold'] + $product['items_sold'] : $product['items_sold'];
					    $category_stats[ $category_name ]['net_margin']       = isset( $category_stats[ $category_name ]['net_margin'] ) ? $category_stats[ $category_name ]['net_margin'] + $product['net_margin'] : $product['net_margin'];
					    $category_stats[ $category_name ]['net_margin_items'] = isset( $category_stats[ $category_name ]['net_margin_items'] ) ? $category_stats[ $category_name ]['net_margin_items'] + 1 : 1;
				    }
			    }
		    } else {
			    $categories = get_the_terms( $product['product_id'], 'product_cat' );

			    if(empty($categories)) {
				    $variation = wc_get_product($product['product_id']);
				    if($variation->get_parent_id()) {
					    $categories = get_the_terms( $variation->get_parent_id(), 'product_cat' );
				    }
			    }

			    if(!empty($categories)) {
				    foreach ($categories as $category) {
					    $category_url  = get_edit_term_link( $category->term_id );
					    $category_name = $category->name;

					    $category_stats[ $category_name ]['category_url']     = $category_url;
					    $category_stats[ $category_name ]['category_name']    = $category_name;
					    $category_stats[ $category_name ]['items_sold']       = isset( $category_stats[ $category_name ]['items_sold'] ) ? $category_stats[ $category_name ]['items_sold'] + $product['items_sold'] : $product['items_sold'];
					    $category_stats[ $category_name ]['net_margin']       = isset( $category_stats[ $category_name ]['net_margin'] ) ? $category_stats[ $category_name ]['net_margin'] + $product['net_margin'] : $product['net_margin'];
					    $category_stats[ $category_name ]['net_margin_items'] = isset( $category_stats[ $category_name ]['net_margin_items'] ) ? $category_stats[ $category_name ]['net_margin_items'] + 1 : 1;
				    }
			    }
		    }
	    }

        foreach($category_stats as &$state) {
            $state['net_margin'] = round($state['net_margin'] / $state['net_margin_items'], 2);
        }

        // Optimize Array and Sort
        $keys = array_column($category_stats, 'net_margin');
        array_multisort($keys, SORT_DESC, $category_stats);
        $category_stats = array_slice($category_stats, 0, $per_page);

        foreach($category_stats as $stat) {
            $rows[] = [
                [
                    'display' => "<a href='{$stat['category_url']}'>{$stat['category_name']}</a>",
                    'value'   => $stat['category_name'],
                ],
                [
                    'display' => $stat['items_sold'],
                    'value'   => $stat['items_sold'],
                ],
                [
                    'display' => number_format(
                            (float) $stat['net_margin'],
                            wc_get_price_decimals(),
                            wc_get_price_decimal_separator(),
                            wc_get_price_thousand_separator()
                        ) . ' %',
                    'value'   => $stat['net_margin'],
                ],
            ];
        }


        return [
            'id'      => 'categories_net_margin',
            'label'   => __('Top Categories - Net Margin', 'qa-cost-of-goods-margins'),
            'headers' => [
                [
                    'label' => __('Category', 'qa-cost-of-goods-margins'),
                ],
                [
                    'label' => __('Items Sold', 'qa-cost-of-goods-margins'),
                ],
                [
                    'label' => __('Net Margin', 'qa-cost-of-goods-margins'),
                ],
            ],
            'rows'    => $rows,
        ];
    }

    /**
     * Get the data for the products leaderboard.
     *
     * @param int    $per_page        Number of rows.
     * @param string $after           Items after date.
     * @param string $before          Items before date.
     * @param string $persisted_query URL query string.
     */
    public function get_products_net_margin_leaderboard($per_page, $after, $before, $persisted_query)
    {
        $products_data_store = new ProductsDataStore();
        $products_data       = $per_page > 0 ? $products_data_store->get_data(
            [
                'orderby'       => 'net_margin',
                'order'         => 'desc',
                'after'         => $after,
                'before'        => $before,
                'per_page'      => $per_page,
                'extended_info' => true,
            ]
        )->data : [];
        $rows                = [];
        foreach ($products_data as $product) {
            $product_url  = get_edit_post_link($product['product_id']);
            $product_name = isset($product['extended_info']) && isset($product['extended_info']['name'])
                ? $product['extended_info']['name']
                : '';
            $rows[]       = [
                [
                    'display' => "<a href='{$product_url}'>{$product_name}</a>",
                    'value'   => $product_name,
                ],
                [
                    'display' => $product['items_sold'],
                    'value'   => $product['items_sold'],
                ],
                [
                    'display' => number_format(
                        (float) $product['net_margin'],
                        wc_get_price_decimals(),
                        wc_get_price_decimal_separator(),
                        wc_get_price_thousand_separator()
                    ) . ' %',
                    'value'   => $product['net_margin'],
                ],
            ];
        }

        return [
            'id'      => 'products_net_margin',
            'label'   => __('Top Products - Net Margin', 'qa-cost-of-goods-margins'),
            'headers' => [
                [
                    'label' => __('Product', 'qa-cost-of-goods-margins'),
                ],
                [
                    'label' => __('Items Sold', 'qa-cost-of-goods-margins'),
                ],
                [
                    'label' => __('Net Margin', 'qa-cost-of-goods-margins'),
                ],
            ],
            'rows'    => $rows,
        ];
    }

    /**
     * Prepare the data object for response.
     *
     * @param object          $item    Data object.
     * @param WP_REST_Request $request Request object.
     *
     * @return WP_REST_Response $response Response data.
     */
    public function prepare_item_for_response($item, $request)
    {
        $data     = $this->add_additional_fields_to_object($item, $request);
        $data     = $this->filter_response_by_context($data, 'view');
        $response = rest_ensure_response($data);

        /**
         * Filter the list returned from the API.
         *
         * @param WP_REST_Response $response The response object.
         * @param array            $item     The original item.
         * @param WP_REST_Request  $request  Request used to generate the response.
         */
        return apply_filters('qa_cog_rest_prepare_leaderboard', $response, $item, $request);
    }

    /**
     * Returns a list of allowed leaderboards.
     *
     * @param WP_REST_Request $request Request data.
     *
     * @return array|WP_Error
     */
    public function get_allowed_items($request)
    {
        $leaderboards = $this->get_leaderboards(0, null, null, null);

        $data = [];
        foreach ($leaderboards as $leaderboard) {
            $data[] = (object) [
                'id'      => $leaderboard['id'],
                'label'   => $leaderboard['label'],
                'headers' => $leaderboard['headers'],
            ];
        }

        $objects = [];
        foreach ($data as $item) {
            $prepared  = $this->prepare_item_for_response($item, $request);
            $objects[] = $this->prepare_response_for_collection($prepared);
        }

        $response = rest_ensure_response($objects);
        $response->header('X-WP-Total', count($data));
        $response->header('X-WP-TotalPages', 1);

        $base = add_query_arg($request->get_query_params(), rest_url(sprintf('/%s/%s', $this->namespace, $this->rest_base)));

        return $response;
    }

    /**
     * Get the schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema()
    {
        $schema = [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'leaderboard',
            'type'       => 'object',
            'properties' => [
                'id'      => [
                    'type'        => 'string',
                    'description' => __('Leaderboard ID.', 'qa-cost-of-goods-margins'),
                    'context'     => ['view'],
                    'readonly'    => true,
                ],
                'label'   => [
                    'type'        => 'string',
                    'description' => __('Displayed title for the leaderboard.', 'qa-cost-of-goods-margins'),
                    'context'     => ['view'],
                    'readonly'    => true,
                ],
                'headers' => [
                    'type'        => 'array',
                    'description' => __('Table headers.', 'qa-cost-of-goods-margins'),
                    'context'     => ['view'],
                    'readonly'    => true,
                    'items'       => [
                        'type'       => 'array',
                        'properties' => [
                            'label' => [
                                'description' => __('Table column header.', 'qa-cost-of-goods-margins'),
                                'type'        => 'string',
                                'context'     => ['view', 'edit'],
                                'readonly'    => true,
                            ],
                        ],
                    ],
                ],
                'rows'    => [
                    'type'        => 'array',
                    'description' => __('Table rows.', 'qa-cost-of-goods-margins'),
                    'context'     => ['view'],
                    'readonly'    => true,
                    'items'       => [
                        'type'       => 'array',
                        'properties' => [
                            'display' => [
                                'description' => __('Table cell display.', 'qa-cost-of-goods-margins'),
                                'type'        => 'string',
                                'context'     => ['view', 'edit'],
                                'readonly'    => true,
                            ],
                            'value'   => [
                                'description' => __('Table cell value.', 'qa-cost-of-goods-margins'),
                                'type'        => 'string',
                                'context'     => ['view', 'edit'],
                                'readonly'    => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $this->add_additional_fields_schema($schema);
    }

    /**
     * Get schema for the list of allowed leaderboards.
     *
     * @return array $schema
     */
    public function get_public_allowed_item_schema()
    {
        $schema = $this->get_public_item_schema();
        unset($schema['properties']['rows']);

        return $schema;
    }
}
