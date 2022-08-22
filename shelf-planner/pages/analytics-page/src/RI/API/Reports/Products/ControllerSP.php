<?php // -*- coding: utf-8 -*-
/**
 * REST API Reports products controller.
 *
 * Handles requests to the /reports/products endpoint.
 *
 *
 */

namespace QuickAssortmentsSP\COG\RI\API\Reports\Products;

defined('ABSPATH') || exit;

/**
 * REST API Reports products controller class.
 *
 *
 * @extends WC_REST_Reports_Controller
 */
class ControllerSP extends \WC_REST_Reports_Controller
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
    protected $rest_base = 'reports/products';

    /**
     * Mapping between external parameter name and name used in query class.
     *
     * @var array
     */
    protected $param_mapping = [
        'products' => 'product_includes',
    ];

    /**
     * Get items.
     *
     * @param WP_REST_Request $request Request data.
     *
     * @return array|WP_Error
     */
    public function get_items($request)
    {
        $args       = [];
        $registered = array_keys($this->get_collection_params());
        foreach ($registered as $param_name) {
            if (isset($request[$param_name])) {
                if (isset($this->param_mapping[$param_name])) {
                    $args[$this->param_mapping[$param_name]] = $request[$param_name];
                } else {
                    $args[$param_name] = $request[$param_name];
                }
            }
        }

        $reports       = new QuerySP($args);
        $products_data = $reports->get_data();

        $data = [];

        foreach ($products_data->data as $product_data) {
            $item   = $this->prepare_item_for_response($product_data, $request);
            $data[] = $this->prepare_response_for_collection($item);
        }

        $response = rest_ensure_response($data);
        $response->header('X-WP-Total', (int) $products_data->total);
        $response->header('X-WP-TotalPages', (int) $products_data->pages);

        $page      = $products_data->page_no;
        $max_pages = $products_data->pages;
        $base      = add_query_arg($request->get_query_params(), rest_url(sprintf('/%s/%s', $this->namespace, $this->rest_base)));
        if ($page > 1) {
            $prev_page = $page - 1;
            if ($prev_page > $max_pages) {
                $prev_page = $max_pages;
            }
            $prev_link = add_query_arg('page', $prev_page, $base);
            $response->link_header('prev', $prev_link);
        }
        if ($max_pages > $page) {
            $next_page = $page + 1;
            $next_link = add_query_arg('page', $next_page, $base);
            $response->link_header('next', $next_link);
        }

        return $response;
    }

    /**
     * Get the query params for collections.
     *
     * @return array
     */
    public function get_collection_params()
    {
        $params                  = [];
        $params['context']       = $this->get_context_param(['default' => 'view']);
        $params['page']          = [
            'description'       => __('Current page of the collection.', 'qa-cost-of-goods-margins'),
            'type'              => 'integer',
            'default'           => 1,
            'sanitize_callback' => 'absint',
            'validate_callback' => 'rest_validate_request_arg',
            'minimum'           => 1,
        ];
        $params['per_page']      = [
            'description'       => __('Maximum number of items to be returned in result set.', 'qa-cost-of-goods-margins'),
            'type'              => 'integer',
            'default'           => 10,
            'minimum'           => 1,
            'maximum'           => 100,
            'sanitize_callback' => 'absint',
            'validate_callback' => 'rest_validate_request_arg',
        ];
        $params['after']         = [
            'description'       => __('Limit response to resources published after a given ISO8601 compliant date.', 'qa-cost-of-goods-margins'),
            'type'              => 'string',
            'format'            => 'date-time',
            'validate_callback' => 'rest_validate_request_arg',
        ];
        $params['before']        = [
            'description'       => __('Limit response to resources published before a given ISO8601 compliant date.', 'qa-cost-of-goods-margins'),
            'type'              => 'string',
            'format'            => 'date-time',
            'validate_callback' => 'rest_validate_request_arg',
        ];
        $params['order']         = [
            'description'       => __('OrderSP sort attribute ascending or descending.', 'qa-cost-of-goods-margins'),
            'type'              => 'string',
            'default'           => 'desc',
            'enum'              => ['asc', 'desc'],
            'validate_callback' => 'rest_validate_request_arg',
        ];
        $params['orderby']       = [
            'description'       => __('Sort collection by object attribute.', 'qa-cost-of-goods-margins'),
            'type'              => 'string',
            'default'           => 'date',
            'enum'              => [
                'date',
                'net_revenue',
                'net_profit',
                'cost_of_goods_sold',
                'gross_revenue',
                'product_name',
                'variations',
                'sku',
            ],
            'validate_callback' => 'rest_validate_request_arg',
        ];
        $params['categories']    = [
            'description'       => __('Limit result to items from the specified categories.', 'qa-cost-of-goods-margins'),
            'type'              => 'array',
            'sanitize_callback' => 'wp_parse_id_list',
            'validate_callback' => 'rest_validate_request_arg',
            'items'             => [
                'type' => 'integer',
            ],
        ];
        $params['match']         = [
            'description'       => __('Indicates whether all the conditions should be true for the resulting set, or if any one of them is sufficient. Match affects the following parameters: status_is, status_is_not, product_includes, product_excludes, coupon_includes, coupon_excludes, customer, categories', 'qa-cost-of-goods-margins'),
            'type'              => 'string',
            'default'           => 'all',
            'enum'              => [
                'all',
                'any',
            ],
            'validate_callback' => 'rest_validate_request_arg',
        ];
        $params['products']      = [
            'description'       => __('Limit result to items with specified product ids.', 'qa-cost-of-goods-margins'),
            'type'              => 'array',
            'sanitize_callback' => 'wp_parse_id_list',
            'validate_callback' => 'rest_validate_request_arg',
            'items'             => [
                'type' => 'integer',
            ],
        ];
        $params['extended_info'] = [
            'description'       => __('Add additional piece of info about each product to the report.', 'qa-cost-of-goods-margins'),
            'type'              => 'boolean',
            'default'           => false,
            'sanitize_callback' => 'wc_string_to_bool',
            'validate_callback' => 'rest_validate_request_arg',
        ];

        return $params;
    }

    /**
     * Prepare a report object for serialization.
     *
     * @param Array           $report  Report data.
     * @param WP_REST_Request $request Request object.
     *
     * @return WP_REST_Response
     */
    public function prepare_item_for_response($report, $request)
    {
        $data = $report;

        $context = ! empty($request['context']) ? $request['context'] : 'view';
        $data    = $this->add_additional_fields_to_object($data, $request);
        $data    = $this->filter_response_by_context($data, $context);

        // Wrap the data in a response object.
        $response = rest_ensure_response($data);
        $response->add_links($this->prepare_links($report));

        /**
         * Filter a report returned from the API.
         *
         * Allows modification of the report data right before it is returned.
         *
         * @param WP_REST_Response $response The response object.
         * @param object           $report   The original report object.
         * @param WP_REST_Request  $request  Request used to generate the response.
         */
        return apply_filters('qa_cog_rest_prepare_report_products', $response, $report, $request);
    }

    /**
     * Prepare links for the request.
     *
     * @param Array $object Object data.
     *
     * @return array Links for the given post.
     */
    protected function prepare_links($object)
    {
        $links = [
            'product' => [
                'href' => rest_url(sprintf('/%s/%s/%d', $this->namespace, 'products', $object['product_id'])),
            ],
        ];

        return $links;
    }

    /**
     * Get the Report's schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema()
    {
        $schema = [
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'report_products',
            'type'       => 'object',
            'properties' => [
                'product_id'    => [
                    'type'        => 'integer',
                    'readonly'    => true,
                    'context'     => ['view', 'edit'],
                    'description' => __('Product ID.', 'qa-cost-of-goods-margins'),
                ],
                'gross_revenue' => [
                    'type'        => 'number',
                    'readonly'    => true,
                    'context'     => ['view', 'edit'],
                    'description' => __('Total gross revenue of all items sold.', 'qa-cost-of-goods-margins'),
                ],
                'net_revenue'   => [
                    'type'        => 'number',
                    'readonly'    => true,
                    'context'     => ['view', 'edit'],
                    'description' => __('Total net revenue of all items sold.', 'qa-cost-of-goods-margins'),
                ],
                'net_profit'    => [
                    'type'        => 'integer',
                    'readonly'    => true,
                    'context'     => ['view', 'edit'],
                    'description' => __('Number of orders product appeared in.', 'qa-cost-of-goods-margins'),
                ],
                'cost_of_goods_sold'    => [
                    'type'        => 'integer',
                    'readonly'    => true,
                    'context'     => ['view', 'edit'],
                    'description' => __('Number of orders product appeared in.', 'qa-cost-of-goods-margins'),
                ],
                'extended_info' => [
                    'name'             => [
                        'type'        => 'string',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product name.', 'qa-cost-of-goods-margins'),
                    ],
                    'price'            => [
                        'type'        => 'number',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product price.', 'qa-cost-of-goods-margins'),
                    ],
                    'image'            => [
                        'type'        => 'string',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product image.', 'qa-cost-of-goods-margins'),
                    ],
                    'permalink'        => [
                        'type'        => 'string',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product link.', 'qa-cost-of-goods-margins'),
                    ],
                    'category_ids'     => [
                        'type'        => 'array',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product category IDs.', 'qa-cost-of-goods-margins'),
                    ],
                    'stock_status'     => [
                        'type'        => 'string',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product inventory status.', 'qa-cost-of-goods-margins'),
                    ],
                    'stock_quantity'   => [
                        'type'        => 'integer',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product inventory quantity.', 'qa-cost-of-goods-margins'),
                    ],
                    'low_stock_amount' => [
                        'type'        => 'integer',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product inventory threshold for low stock.', 'qa-cost-of-goods-margins'),
                    ],
                    'variations'       => [
                        'type'        => 'array',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product variations IDs.', 'qa-cost-of-goods-margins'),
                    ],
                    'sku'              => [
                        'type'        => 'string',
                        'readonly'    => true,
                        'context'     => ['view', 'edit'],
                        'description' => __('Product SKU.', 'qa-cost-of-goods-margins'),
                    ],
                ],
            ],
        ];

        return $this->add_additional_fields_schema($schema);
    }
}
