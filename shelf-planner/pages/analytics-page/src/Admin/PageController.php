<?php
/**
 * PageController.
 *
 * @package QuickAssortments Admin
 */

namespace QuickAssortments\COG\Admin;

defined('ABSPATH') || exit;

/**
 * PageController.
 */
final class PageController
{
    // JS-powered page root.
    const PAGE_ROOT = 'shelf_planner_retail_insights';

    /**
     * Singleton instance of self.
     *
     * @var PageController
     */
    private static $instance = false;

    /**
     * Current page ID (or false if not registered with this controller).
     *
     * @var string
     */
    private $current_page = null;

    /**
     * Registered pages
     * Contains information (breadcrumbs, menu info) about JS powered pages and classic QuickAssortments pages.
     *
     * @var array
     */
    private $pages = [];

    /**
     * Product settings.
     *
     * @since    1.0.0
     *
     * @access   private
     *
     * @var array $classes Including necessary classes.
     */
    private $prod_sett = [];

    /**
     * Page constructor.
     *
     * @since  1.0.0
     *
     * @retuen void
     */
    public function __construct()
    {
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'include_scripts_styles' ) );
    }

    /**
     * Plugin Deactivation Event
     */
    public static function include_scripts_styles() {

	    $remove_pages = ['shelf_planner_retail_insights', 'shelf_planner_product_management'];

	    if(isset($_GET['page']) && in_array($_GET['page'], $remove_pages)) {
		    wp_enqueue_script( 'sp-wp-deactivation-message', MAIN_SP_URL . 'assets/js/sp_deactivate.js', array(), time(), true );
		    wp_enqueue_script( 'sp-moment', MAIN_SP_URL . 'assets/js/moment.min.js', array( 'jquery' ), time(), false );
		    wp_enqueue_script( 'sp-tabulator', MAIN_SP_URL . 'assets/js/tabulator.min.js', array(
			    'jquery',
			    'sp-moment'
		    ), time(), false );
		    wp_enqueue_script( 'sp-xlsx', MAIN_SP_URL . 'assets/js/xlsx.full.min.js', array( 'jquery' ), time(), true );
		    wp_enqueue_script( 'sp-apexcharts', MAIN_SP_URL . 'assets/js/apexcharts.js', array( 'jquery' ), time(), false );
		    wp_enqueue_script( 'sp-custom', MAIN_SP_URL . 'assets/js/custom.js', array( 'jquery' ), time(), false );
		    wp_enqueue_script( 'sp-drag-n-drop-new', MAIN_SP_URL . 'assets/js/drag-n-drop-new.js', array( 'jquery' ), time(), false );
		    wp_enqueue_style( 'sp-tabulator-css', MAIN_SP_URL . 'assets/tabulator.min.css' );
		    wp_enqueue_style( 'sp-icons-css', MAIN_SP_URL . 'assets/css/icons.css' );
		    wp_enqueue_style( 'sp-sidebar-css', MAIN_SP_URL . 'assets/plugins/sidebar/sidebar.css' );
		    wp_enqueue_style( 'sp-style-css', MAIN_SP_URL . 'assets/css/style.css' );
		    wp_enqueue_style( 'sp-style-dark-css', MAIN_SP_URL . 'assets/css/style-dark.css' );
		    wp_enqueue_style( 'sp-skin-modes-css', MAIN_SP_URL . 'assets/css/skin-modes.css' );
		    wp_enqueue_style( 'sp-animate-css', MAIN_SP_URL . 'assets/css/animate.css' );
		    wp_enqueue_style( 'sp-closed-sidemenu-css', MAIN_SP_URL . 'assets/css/closed-sidemenu.css' );
	    }
    }

    /**
     * Connect an existing page to wc-admin.
     *
     * @param array $options {
     *                       Array describing the page.
     *
     *   @type string       id           Id to reference the page.
     *   @type string|array title        Page title. Used in menus and breadcrumbs.
     *   @type string|null  parent       Parent ID. Null for new top level page.
     *   @type string       path         Path for this page. E.g. admin.php?page=wc-settings&tab=checkout
     *   @type string       capability   Capability needed to access the page.
     *   @type string       icon         Icon. Dashicons helper class, base64-encoded SVG, or 'none'.
     *   @type int          position     Menu item position.
     *   @type bool      js_page      If this is a JS-powered page.
     * }
     */
    public function connect_page($options)
    {
        if (! is_array($options['title'])) {
            $options['title'] = [$options['title']];
        }

        /**
         * Filter the options when connecting or registering a page.
         *
         * Use the `js_page` option to determine if registering.
         *
         * @param array $options {
         *                       Array describing the page.
         *
         *   @type string       id           Id to reference the page.
         *   @type string|array title        Page title. Used in menus and breadcrumbs.
         *   @type string|null  parent       Parent ID. Null for new top level page.
         *   @type string       screen_id    The screen ID that represents the connected page. (Not required for registering).
         *   @type string       path         Path for this page. E.g. admin.php?page=wc-settings&tab=checkout
         *   @type string       capability   Capability needed to access the page.
         *   @type string       icon         Icon. Dashicons helper class, base64-encoded SVG, or 'none'.
         *   @type int          position     Menu item position.
         *   @type bool      js_page      If this is a JS-powered page.
         * }
         */
        $options = apply_filters('qa_navigation_connect_page_options', $options);

        // @todo check for null ID, or collision.
        $this->pages[$options['id']] = $options;
    }

    /**
     * Determine the current page ID, if it was registered with this controller.
     */
    public function determine_current_page()
    {
        $current_url       = '';
        $current_screen_id = $this->get_current_screen_id();

        if (isset($_SERVER['REQUEST_URI'])) {
            $current_url = esc_url_raw(wp_unslash($_SERVER['REQUEST_URI']));
        }

        $current_query = wp_parse_url($current_url, PHP_URL_QUERY);
        parse_str($current_query, $current_pieces);
        $current_path  = empty($current_pieces['page']) ? '' : $current_pieces['page'];
        $current_path .= empty($current_pieces['path']) ? '' : '&path=' . $current_pieces['path'];

        foreach ($this->pages as $page) {
            if (isset($page['js_page']) && $page['js_page']) {
                // Check registered admin pages.
                if (
                    $page['path'] === $current_path
                ) {
                    $this->current_page = $page;
                    return;
                }
            } else {
                // Check connected admin pages.
                if (
                    isset($page['screen_id']) &&
                    $page['screen_id'] === $current_screen_id
                ) {
                    $this->current_page = $page;
                    return;
                }
            }
        }

        $this->current_page = false;
    }

    /**
     * Get breadcrumbs for QuickAssortments Admin Page navigation.
     *
     * @return array Navigation pieces (breadcrumbs).
     */
    public function get_breadcrumbs()
    {
        $current_page = $this->get_current_page();

        // Bail if this isn't a page registered with this controller.
        if (false === $current_page) {
            // Filter documentation below.
            return apply_filters('qa_navigation_get_breadcrumbs', [''], $current_page);
        }

        if (1 === count($current_page['title'])) {
            $breadcrumbs = $current_page['title'];
        } else {
            // If this page has multiple title pieces, only link the first one.
            $breadcrumbs = array_merge(
                [
                    [$current_page['path'], reset($current_page['title'])],
                ],
                array_slice($current_page['title'], 1)
            );
        }

        if (isset($current_page['parent'])) {
            $parent_id = $current_page['parent'];

            while ($parent_id) {
                if (isset($this->pages[$parent_id])) {
                    $parent = $this->pages[$parent_id];
                    array_unshift($breadcrumbs, [$parent['path'], reset($parent['title'])]);
                    $parent_id = isset($parent['parent']) ? $parent['parent'] : false;
                } else {
                    $parent_id = false;
                }
            }
        }

        $qa_breadcrumb = ['admin.php?page=' . PAGE_ROOT, __('QuickAssortments', 'qa-cost-of-goods-margins')];

        array_unshift($breadcrumbs, $qa_breadcrumb);

        /**
         * The navigation breadcrumbs for the current page.
         *
         * @param array      $breadcrumbs  Navigation pieces (breadcrumbs).
         * @param array|bool $current_page The connected page data or false if not identified.
         */
        return apply_filters('qa_navigation_get_breadcrumbs', $breadcrumbs, $current_page);
    }

    /**
     * Get the current page.
     *
     * @return array|bool Current page or false if not registered with this controller.
     */
    public function get_current_page()
    {
        // If 'current_screen' hasn't fired yet, the current page calculation
        // will fail which causes `false` to be returned for all subsquent calls.
        if (! did_action('current_screen')) {
            _doing_it_wrong(__FUNCTION__, esc_html__('Current page retrieval should be called on or after the `current_screen` hook.', 'qa-cost-of-goods-margins'), '0.16.0');
        }

        if (is_null($this->current_page)) {
            $this->determine_current_page();
        }

        return $this->current_page;
    }

    /**
     * Returns the current screen ID.
     *
     * This is slightly different from WP's get_current_screen, in that it attaches an action,
     * so certain pages like 'add new' pages can have different breadcrumbs or handling.
     * It also catches some more unique dynamic pages like taxonomy/attribute management.
     *
     * Format:
     * - {$current_screen->action}-{$current_screen->action}-tab-section
     * - {$current_screen->action}-{$current_screen->action}-tab
     * - {$current_screen->action}-{$current_screen->action} if no tab is present
     * - {$current_screen->action} if no action or tab is present
     *
     * @return string Current screen ID.
     */
    public function get_current_screen_id()
    {
        $current_screen = get_current_screen();
        if (! $current_screen) {
            // Filter documentation below.
            return apply_filters('qa_navigation_current_screen_id', false, $current_screen);
        }

        $screen_pieces = [$current_screen->id];

        if ($current_screen->action) {
            $screen_pieces[] = $current_screen->action;
        }

        if (
            ! empty($current_screen->taxonomy) &&
            isset($current_screen->post_type) &&
            'product' === $current_screen->post_type
        ) {
            // Editing a product attribute.
            if (0 === strpos($current_screen->taxonomy, 'pa_')) {
                $screen_pieces = ['product_page_product_attribute-edit'];
            }

            // Editing a product taxonomy term.
            if (! empty(sanitize_key($_GET['tag_ID']))) {
                $screen_pieces = [$current_screen->taxonomy];
            }
        }

        // Pages with default tab values.
        $pages_with_tabs = apply_filters(
            'qa_navigation_pages_with_tabs',
            [
                'wc-reports'  => 'orders',
                'wc-settings' => 'general',
                'wc-status'   => 'status',
                'wc-addons'   => 'browse-extensions',
            ]
        );

        // Tabs that have sections as well.
        $wc_emails    = \WC_Emails::instance();
        $wc_email_ids = array_map('sanitize_title', array_keys($wc_emails->get_emails()));

        $tabs_with_sections = apply_filters(
            'qa_navigation_page_tab_sections',
            [
                'products'          => ['', 'inventory', 'downloadable'],
                'shipping'          => ['', 'options', 'classes'],
                'checkout'          => ['bacs', 'cheque', 'cod', 'paypal'],
                'email'             => $wc_email_ids,
                'advanced'          => [
                    '',
                    'keys',
                    'webhooks',
                    'legacy_api',
                    'woocommerce_com',
                ],
                'browse-extensions' => ['helper'],
            ]
        );

        if (! empty(sanitize_key($_GET['page']))) {
            if (in_array(sanitize_key($_GET['page']), array_keys($pages_with_tabs))) { // WPCS: sanitization ok.
                if (! empty(sanitize_key($_GET['tab']))) {
                    $tab = wc_clean(wp_unslash(sanitize_key($_GET['tab'])));
                } else {
                    $tab = $pages_with_tabs[sanitize_key($_GET['page'])]; // WPCS: sanitization ok.
                }

                $screen_pieces[] = $tab;

                if (! empty(sanitize_key($_GET['section']))) {
                    if (
                        isset($tabs_with_sections[$tab]) &&
                        in_array(sanitize_key($_GET['section']), array_keys($tabs_with_sections[$tab])) // WPCS: sanitization ok.
                    ) {
                        $screen_pieces[] = wc_clean(wp_unslash(sanitize_key($_GET['section'])));
                    }
                }

                // Editing a shipping zone.
                if (('shipping' === $tab) && isset($_GET['zone_id'])) {  // WPCS: sanitization ok.
                    $screen_pieces[] = 'edit_zone';
                }
            }
        }

        /**
         * The current screen id.
         *
         * Used for identifying pages to render the QuickAssortments Admin header.
         *
         * @param string|bool $screen_id      The screen id or false if not identified.
         * @param WP_Screen   $current_screen The current WP_Screen.
         */
        return apply_filters('qa_navigation_current_screen_id', implode('-', $screen_pieces), $current_screen);
    }

    /**
     * Returns the path from an ID.
     *
     * @param string $id ID to get path for.
     *
     * @return string Path for the given ID, or the ID on lookup miss.
     */
    public function get_path_from_id($id)
    {
        if (isset($this->pages[$id]) && isset($this->pages[$id]['path'])) {
            return $this->pages[$id]['path'];
        }
        return $id;
    }

    /**
     * Returns true if we are on a page connected to this controller.
     *
     * @return bool
     */
    public function is_connected_page()
    {
        $current_page = $this->get_current_page();

        if (false === $current_page) {
            $is_connected_page = false;
        } else {
            $is_connected_page = isset($current_page['js_page']) ? ! $current_page['js_page'] : true;
        }

        // Disable embed on the block editor.
        $current_screen = did_action('current_screen') ? get_current_screen() : false;
        if (method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor()) {
            $is_connected_page = false;
        }

        /**
         * Whether or not the current page is an existing page connected to this controller.
         *
         * Used to determine if the QuickAssortments Admin header should be rendered.
         *
         * @param bool       $is_connected_page True if the current page is connected.
         * @param array|bool $current_page      The connected page data or false if not identified.
         */
        return apply_filters('qa_navigation_is_connected_page', $is_connected_page, $current_page);
    }

    /**
     * Returns true if we are on a page registed with this controller.
     *
     * @return bool
     */
    public function is_registered_page()
    {
        $current_page = $this->get_current_page();

        if (false === $current_page) {
            $is_registered_page = false;
        } else {
            $is_registered_page = isset($current_page['js_page']) && $current_page['js_page'];
        }

        /**
         * Whether or not the current page was registered with this controller.
         *
         * Used to determine if this is a JS-powered QuickAssortments Admin page.
         *
         * @param bool       $is_registered_page True if the current page was registered with this controller.
         * @param array|bool $current_page       The registered page data or false if not identified.
         */
        return apply_filters('qa_navigation_is_registered_page', $is_registered_page, $current_page);
    }

    /**
     * Adds a JS powered page to wc-admin.
     *
     * @param array $options {
     *                       Array describing the page.
     *
     *   @type string      id           Id to reference the page.
     *   @type string      title        Page title. Used in menus and breadcrumbs.
     *   @type string|null parent       Parent ID. Null for new top level page.
     *   @type string      path         Path for this page, full path in app context; ex /analytics/report
     *   @type string      capability   Capability needed to access the page.
     *   @type string      icon         Icon. Dashicons helper class, base64-encoded SVG, or 'none'.
     *   @type int         position     Menu item position.
     * }
     */
    public function register_page($options)
    {
        $defaults = [
            'id'         => null,
            'parent'     => null,
            'title'      => '',
            'capability' => 'view_woocommerce_reports',
            'path'       => '',
            'callback'   => [$this, 'page'],
            'icon'       => '',
            'position'   => null,
            'js_page'    => true,
        ];

        $options = wp_parse_args($options, $defaults);

        if (is_null($options['parent'])) {
            add_menu_page(
                $options['title'],
                $options['title'],
                $options['capability'],
                $options['path'],
                $options['callback'],
                $options['icon'],
                $options['position']
            );
        } else {
            $parent_path = $this->get_path_from_id($options['parent']);
            // @todo check for null path.
            add_submenu_page(
                $parent_path,
                $options['title'],
                $options['title'],
                $options['capability'],
                $options['path'],
                $options['callback']
            );
        }

        $this->connect_page($options);
    }

    /**
     * Set up a div for the app to render into.
     */
    public function page()
    {
        require_once MAIN_SP_PATH . '/pages/retail_insights.php';
        ?>
		<!--<div class="wrap">
			<div id="root"></div>
		</div>-->
		<?php
    }
}
