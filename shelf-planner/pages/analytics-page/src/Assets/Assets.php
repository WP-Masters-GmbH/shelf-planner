<?php // -*- coding: utf-8 -*-

namespace QuickAssortments\COG\Assets;

/**
 * Class AssetsEnqueue.
 *
 * @author   Khan Mohammad R. <khan@quickassortments.com>
 *
 * @package  QuickAssortments\COG\Assets
 *
 * @since    1.0.0
 */
final class Assets
{
    /**
     * The assets of WooCommerce.
     *
     * @since    1.0.0
     * @access   private
     *
     * @var string $version The current version of this plugin.
     */
    private $wc_assets;

    /**
     * AssetsEnqueue constructor.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function __construct()
    {
    }

    /**
     * Enqueueing scripts and styles.
     *
     * @return object
     *
     * @since 1.0.0
     */
    public function init()
    {
        add_action('admin_enqueue_scripts', [$this, 'styles']);
        add_action('admin_enqueue_scripts', [$this, 'scripts']);

        global $pagenow, $wp_filter;
        if ('admin.php' === $pagenow && QA_COG_RI_SLUGSP === sanitize_key($_GET['page'])) {
            // Preventing 'woocommerce-admin' scripts from enqueue. It's important!
            unset($wp_filter['admin_enqueue_scripts']->callbacks[10]['Automattic\WooCommerce\Admin\Loader::register_scripts'] , $wp_filter['admin_enqueue_scripts']->callbacks[15]['Automattic\WooCommerce\Admin\Loader::load_scripts']);

            add_action('admin_print_scripts', [$this, 'print_script_wc_settings'], 1);
            add_action('admin_enqueue_scripts', [$this, 'ri_styles_scripts']);
        }

        return $this;
    }

    /**
     * Enqueueing styles.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function styles()
    {
        $this->wc_assets = new \WC_Admin_Assets();
        $this->wc_assets->admin_styles();

        wp_enqueue_style('qa-cog', QA_COG_BASE_URL . 'assets/dist/quickassortments/quickassortments.css', null, '1.0.3', 'all');
        wp_enqueue_style('woocommerce_admin_styles');
        wp_enqueue_style('woocommerce_admin_dashboard_styles');
    }

    /**
     * Enqueueing scripts.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function scripts()
    {
        $this->wc_assets = new \WC_Admin_Assets();
        $this->wc_assets->admin_scripts();
        // Registering the script.
        wp_register_script('qa-cog', QA_COG_BASE_URL . 'assets/dist/quickassortments/quickassortments.js', ['jquery'], '1.0.0', true);
        // Local JS data
        $local_js_data = ['ajax_url' => admin_url('admin-ajax.php'), 'currency' => get_woocommerce_currency(), ];
        // Pass data to myscript.js on page load
        wp_localize_script('qa-cog', 'QACOGAjaxObj', $local_js_data);
        // Enqueueing JS file.
        wp_enqueue_script('qa-cog');

        $params = [
            'strings' => [
                'import_products' => __('Import', 'woocommerce'),
                'export_products' => __('Export', 'woocommerce'),
            ],
            'urls'    => [
                'import_products' => current_user_can('import')
                    ? esc_url_raw(admin_url('edit.php?post_type=product&page=product_importer'))
                    : null,
                'export_products' => current_user_can('export')
                    ? esc_url_raw(admin_url('edit.php?post_type=product&page=product_exporter'))
                    : null,
            ],
        ];
        wp_localize_script('woocommerce_admin', 'woocommerce_admin', $params);

        // Style ans Scripts Including
        wp_enqueue_script('woocommerce_admin');
        wp_enqueue_script('jquery-blockui');
        wp_enqueue_script('jquery-tiptip');
        wp_enqueue_script('flot');
        wp_enqueue_script('flot-resize');
        wp_enqueue_script('flot-time');
        wp_enqueue_script('flot-pie');
        wp_enqueue_script('flot-stack');
        wp_enqueue_script('select2');
        wp_enqueue_script('wc-enhanced-select');
    }

    /**
     * These are used by @woocommerce/components & the block library to set up defaults
     * based on user-controlled settings from WordPress. Only use this in wp-admin.
     */
    public function print_script_wc_settings()
    {
        global $wp_locale;
        $code = get_woocommerce_currency();

        // NOTE: wcSettings is not used directly, it's only for @woocommerce/components
        //
        // Settings and variables can be passed here for access in the app.
        // Will need `wcAdminAssetUrl` if the ImageAsset component is used.
        // Will need `dataEndpoints.countries` if Search component is used with 'country' type.
        // Will need `orderStatuses` if the OrderStatus component is used.
        // Deliberately excluding: `embedBreadcrumbs`, `trackingEnabled`.
        $settings                    = [
            'adminUrl'      => admin_url(),
            'wcAssetUrl'    => plugins_url('assets/', WC_PLUGIN_FILE),
            'siteLocale'    => esc_attr(get_bloginfo('language')),
            'currency'      => [
                'code'              => $code,
                'precision'         => wc_get_price_decimals(),
                'symbol'            => html_entity_decode(get_woocommerce_currency_symbol($code)),
                'symbolPosition'    => get_option('woocommerce_currency_pos'),
                'decimalSeparator'  => wc_get_price_decimal_separator(),
                'thousandSeparator' => wc_get_price_thousand_separator(),
                'priceFormat'       => html_entity_decode(get_woocommerce_price_format()),
            ],
            'stockStatuses' => wc_get_product_stock_status_options(),
            'siteTitle'     => get_bloginfo('name'),
            'dataEndpoints' => [
                'leaderboards' => [
                    [
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
                        'rows'    => [],
                    ],
                    [
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
                        'rows'    => [],
                    ],
                ],
            ],
            'wcAdminSettings' => [
                'id'          => 'woocommerce_default_date_range',
                'option_key'  => 'woocommerce_default_date_range',
                'label'       => __('Default Date Range', 'qa-cost-of-goods-margins'),
                'description' => __('Default Date Range', 'qa-cost-of-goods-margins'),
                'default'     => 'period=month&compare=previous_year',
                'type'        => 'text',
            ],
            'l10n'            => [
                'userLocale'    => get_user_locale(),
                'weekdaysShort' => array_values($wp_locale->weekday_abbrev),
            ],
        ];
        // NOTE: wcSettings is not used directly, it's only for @woocommerce/components.
        // $settings = apply_filters('woocommerce_components_settings', $settings);?>
		<script type="text/javascript">
			var wcSettings = wcSettings || JSON.parse( decodeURIComponent( '<?php echo rawurlencode(wp_json_encode($settings)); ?>' ) );
		</script>
		<?php
    }

    /**
     * Enqueueing RI styles and scripts.
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function ri_styles_scripts()
    {
        if(isset($_GET['page']) && sanitize_text_field( $_GET['page'] ) != '_qa_cog_retail_insights') {
            wp_register_style(
                'wc-components',
                QA_COG_BASE_URL . 'assets/dist/components/style.css',
                ['wp-components'],
                null
            );
        }
        wp_style_add_data('wc-components', 'rtl', 'replace');

        wp_register_style(
            'wc-components-ie',
            QA_COG_BASE_URL . 'assets/dist/components/ie.css',
            ['wp-components'],
            null
        );
        wp_style_add_data('wc-components-ie', 'rtl', 'replace');

        wp_register_script(
            'qa-admin-app',
            QA_COG_BASE_URL . 'assets/dist/app/index.js?3',
            ['wc-components', 'wc-navigation', 'wp-date', 'wp-html-entities', 'wp-keycodes', 'wp-i18n'],
            null,
            true
        );

        wp_set_script_translations('qa-admin-app', 'qa-cost-of-goods-margins');

        if(isset($_GET['page']) && sanitize_text_field( $_GET['page'] ) != '_qa_cog_retail_insights') {
            wp_register_style(
                'qa-admin-app',
                QA_COG_BASE_URL . 'assets/dist/app/style.css?39',
                ['wc-components'],
                null
            );
        }
        wp_style_add_data('qa-admin-app', 'rtl', 'replace');

        wp_register_style(
            'wc-material-icons',
            'https://fonts.googleapis.com/icon?family=Material+Icons+Outlined',
            [],
            null
        );

        wp_enqueue_style('qa-admin-app');
        wp_enqueue_style('wc-material-icons');

        wp_register_script(
            'wc-csv',
            QA_COG_BASE_URL . 'assets/dist/csv-export/index.js?3',
            [],
            null,
            true
        );

        wp_register_script(
            'wc-currency',
            QA_COG_BASE_URL . 'assets/dist/currency/index.js?3',
            ['wc-number'],
            null,
            true
        );

        wp_set_script_translations('wc-currency', 'qa-cost-of-goods-margins');

        wp_register_script(
            'wc-navigation',
            QA_COG_BASE_URL . 'assets/dist/navigation/index.js?3',
            [],
            null,
            true
        );

        wp_register_script(
            'wc-number',
            QA_COG_BASE_URL . 'assets/dist/number/index.js?3',
            [],
            null,
            true
        );

        wp_register_script(
            'wc-date',
            QA_COG_BASE_URL . 'assets/dist/date/index.js?3',
            ['wp-date', 'wp-i18n'],
            null,
            true
        );

        wp_set_script_translations('wc-date', 'qa-cost-of-goods-margins');

        wp_register_script(
            'wc-components',
            QA_COG_BASE_URL . 'assets/dist/components/index.js?3',
            [
                'wp-api-fetch',
                'wp-components',
                'wp-data',
                'wp-element',
                'wp-hooks',
                'wp-i18n',
                'wp-keycodes',
                'wc-csv',
                'wc-currency',
                'wc-date',
                'wc-navigation',
                'wc-number',
            ],
            null,
            true
        );

        wp_set_script_translations('wc-components', 'qa-cost-of-goods-margins');

        wp_enqueue_script('qa-admin-app');
    }
}
