<?php
// TODO: move all this file to ShelfPlannerCore class

if ( ! defined( 'SP_META_KEY_PROCESSED' ) ) {
	define( 'SP_META_KEY_PROCESSED', 'imported_to_shelf_planner_2021_test14' );
}

/**
 * For API calls
 */
const AFFILIATE_ID = 'SHELFPLANNER';

/**
 * For logging
 */
define( 'LOG_FILE', dirname( __FILE__ ) . '/../' . 'api.log' );

/**
 * Disable non-auth users to upload
 */
function sp_deny_if_not_logged_in(){
	if ( ! ( current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) ) {
		wp_die( 'Authorization Error' );
	}
}

/**
 * @param $data
 * @param string $type
 */
function spApiLog( $data, $type = 'info' ) {
	spApiLogDb( $data, $type );

	$log_enabled = get_option( 'sp.log', 'checked' );
	if ( $log_enabled == 'checked' ) {
		$data = date( '[d.m.Y H:i:s]' ) . ' ' . $data . PHP_EOL;
		file_put_contents( LOG_FILE, $data, FILE_APPEND );
	}
}

/**
 * Stores logs into the database table
 *
 * @param $data
 * @param $type
 */
// info, error, success, warning, notice
function spApiLogDb( $data, $type = 'info' ) {
	global $wpdb;

	$type = mb_strtoupper( trim( $type ) );
	if ( ! in_array( $type, array( 'INFO', 'ERROR', 'SUCCESS', 'WARNING', 'NOTICE' ) ) ) {
		$type = 'INFO';
	}
	$message    = trim( $data );
	$date_added = date( 'Y-m-d H:i:s', time() );

	$sql = $wpdb->prepare( "INSERT INTO `{$wpdb->api_log}` (`message`, `type`, `date_added`) VALUES (%s, %s, %s)", $message, $type, $date_added );
	$wpdb->query( $sql );
}

/**
 * Clean up log file
 */
function purgeApiLog() {
    global $wpdb;
    $wpdb->query("TRUNCATE TABLE {$wpdb->api_log}");
	file_put_contents( LOG_FILE, '' );
}

class ShelfPlannerCore {

	/**
	 * @return int
	 */
	public static function getOrdersCount() {
		return count( wc_get_orders( array(
			'orderby'   => 'date',
			'order'     => 'DESC',
			'limit'     => - 1,
			'post_type' => 'shop_order',
			'return'    => 'ids',
		) ) );
	}

	/**
	 * @return int
	 */
	public static function getAnalyzedProgress() {
		$result = (int) ( min( 100, ( ShelfPlannerCore::getAnalyzedOrdersCount() / max( 1, ShelfPlannerCore::getOrdersCount() ) * 100 ) ) );
		if ( $result == 0 ) {
			$result = 1;
		}

		return $result;
	}

	/**
	 * @return int
	 */
	public static function getAnalyzedOrdersCount() {
		return count( wc_get_orders( array(
			'orderby'    => 'date',
			'order'      => 'DESC',
			'limit'      => - 1,
			'return'     => 'ids',
			'post_type'  => 'shop_order',
			'meta_query' => [
				[
					'key'     => SP_META_KEY_PROCESSED,
					'compare' => 'EXISTS',
				],
			],
		) ) );
	}

}