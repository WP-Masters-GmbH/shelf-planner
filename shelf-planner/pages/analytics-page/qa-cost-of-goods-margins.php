<?php // -*- coding: utf-8 -*-

/**
 * Plugin Name: QA Cost of Goods & Margins
 * Description: Manage cost prices for your products and variations and instantly see the impact on margin, markup and value on hand in your store.
 * Plugin URI:  https://quickassortments.com/products/
 * Author:      Quick Assortments AB
 * Author URI:  https://quickassortments.com/
 * Version:     2.6.0
 * License:     GPL-2.0
 * Text Domain: qa-cost-of-goods-margins.
 */

namespace QuickAssortmentsSP\COG;

/**
 * Defining base constant.
 */
defined('ABSPATH') || die;

if (! defined('QA_COG_VERSIONSP')) {
    define('QA_COG_VERSIONSP', __('2', 'qa-cost-of-goods-margins'));
}
if (! defined('QA_COG_BASE_PATHSP')) {
    define('QA_COG_BASE_PATHSP', plugin_dir_path(__FILE__));
}
if (! defined('QA_COG_BASE_URLSP')) {
    define('QA_COG_BASE_URLSP', plugin_dir_url(__FILE__));
}
if (! defined('QA_COG_BASENAMESP')) {
    define('QA_COG_BASENAMESP', plugin_basename(__FILE__));
}
if (! defined('QA_COG_DEBUGSP')) {
    define('QA_COG_DEBUGSP', false);
}
if (! defined('QA_COG_PREFIXSP')) {
    define('QA_COG_PREFIXSP', '_qa_cog_');
}
if (! defined('QA_COG_RI_SLUGSP')) {
    define('QA_COG_RI_SLUGSP', 'shelf_planner_retail_insights');
}
if (! defined('QA_COG_SETTINGS_SLUGSP')) {
    define('QA_COG_SETTINGS_SLUGSP', QA_COG_PREFIXSP . 'retail_insights_settings');
}

/**
 * Initialize a hook on plugin activation.
 *
 * @return void
 */
function activate()
{
    include_once QA_COG_BASE_PATHSP . '/src/Helpers/DBSP.php';
    Helpers\DBSP::create_db_tables();
}
register_activation_hook(__FILE__, __NAMESPACE__ . '\\activate');

/**
 * Initialize a hook on plugin deactivation.
 *
 * @return void
 */
function deactivate()
{
    wp_clear_scheduled_hook('qa_cog_update_category_lookup_table');
}
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\deactivate');

/**
 * Initialize all the plugin things.
 *
 * @throws \Throwable
 *
 * @return array | bool | void
 */
function initialize()
{
    try {
        // Translation directory updated !
        load_plugin_textdomain(
            'qa-cost-of-goods-margins',
            true,
            basename(dirname(__FILE__)) . '/languages'
        );

        /**
         * Check if WooCommerce is active.
         **/
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        if (! is_plugin_active('woocommerce/woocommerce.php')) {
            deactivate_plugins(plugin_basename(__FILE__));
            add_action(
                'admin_notices',
                function () {
                    $class	 = 'notice notice-error is-dismissible';
                    $message = __('Quick Assortments Error: <b>WooCommerce</b> isn\'t active.', 'qa-cost-of-goods-margins');
                    printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
                }
            );

            return false;
        }

        /**
         * Checking if vendor/autoload.php exists or not.
         */
        if (file_exists(__DIR__ . '/vendor/autoload.php')) {
            /** @noinspection PhpIncludeInspection */
            require_once __DIR__ . '/vendor/autoload.php';
        }

        //( new Helpers\TemplateSP(QA_COG_BASE_PATHSP . '/src/Templates/') );
        ( new Assets\AssetsSP() )->init();
        ( new Admin\PageSP() )->init();
        // Cost of goods
        ( new CoG\ColumnsSP() )->init();
        ( new CoG\FieldsSP() )->init();
        ( new CoG\SettingsSPSP() )->init();
        // Retail Insights
        ( new RI\DataSyncSP() )->init();
        ( new RI\RISP() )->init();
        ( new RI\SettingsSP() )->init();

        RI\CategoryLookupSP::instance()->init();

        // PWSP WooCommerce Bulk Edit plugin integration
        ( new Helpers\PWSP() )->init();
    } catch (\Throwable $throwable) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            throw $throwable;
        }
        do_action('qa_cog_error', $throwable);
    }
}

add_action('plugins_loaded', __NAMESPACE__ . '\\initialize');
