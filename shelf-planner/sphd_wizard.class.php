<?php

/**
 * Class SPHD_Wizard
 */
class SPHD_Wizard {

	/** @var int */
	const MAX_STEP = 5;
	/** @var string */
	public static $title;
	/** @var string */
	protected static $a_tmpl;
	/** @var mixed */
	protected static $categories;

	/**
	 * Wizard Init
	 */
	public static function init() {
		global $categories_industry;

		$is_session_started = false;
		if ( php_sapi_name() !== 'cli' ) {
			$is_session_started = version_compare( phpversion(), '5.4.0', '>=' ) ? session_status() === PHP_SESSION_ACTIVE : session_id() !== '';
		}
		if ( ! $is_session_started ) {
			session_start();
		}

		self::$a_tmpl = '
            <div style="padding-bottom: 10px; border-bottom: 2px solid #486A82; width: 100px; float: %s; text-align: center">
            <a href="#" style="text-decoration: none; color: #486A82" onclick="save_wizard_data(%d, %d); return false">%s</a>
            </div>
        ';

		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );

		add_action( 'wp_ajax_sphd_save_wizard_data', array(
			__CLASS__,
			'ajax_sphd_save_wizard_data',
		) );
		add_action( 'wp_ajax_sphd_complete_wizard', array(
			__CLASS__,
			'ajax_sphd_complete_wizard',
		) );

		self::$categories = array(
			'industry' => $categories_industry,
		);

		add_action( 'admin_enqueue_scripts', array(
			__CLASS__,
			'include_scripts_styles',
		) );
	}

	/**
	 * Scripts for pages in menu
	 */
	public static function include_scripts_styles() {
		wp_enqueue_script( 'sp-wizard', plugins_url( '/wizard/assets/spdh_wizard_index.js', SPDH_ROOT ), array( 'jquery' ), time(), true );
	}

	/**
	 * Admin Menu Item
	 */
	public static function register_menu() {
		add_menu_page( 'Shelf Planner', 'Shelf Planner Wizard', 'edit_others_posts', 'shelf_planner', array( __CLASS__, 'manage_pages' ), plugin_dir_url( __FILE__ ) . 'assets/img/menu-icon.png', 2 );
	}

	/**
	 * @return int
	 */
	protected static function get_step() {
		if ( array_key_exists( 'wizard', $_GET ) ) {
			$value = (int) sanitize_text_field( $_GET['wizard'] );
			if ( $value > 0 ) {
				return $value;
			}
		}

		return 1;
	}

	/**
	 * Load Wizard Steps Templates
	 */
	public static function manage_pages() {
		global $sp_countries_normilized;

		require_once __DIR__ . '/wizard/_header.php';

		$step = self::get_step();
		switch ( $step ) {
			case 2:
				require_once __DIR__ . '/wizard/step2.php';
				break;
			case 3:
				require_once __DIR__ . '/wizard/step3.php';
				break;
			case 4:
				require_once __DIR__ . '/wizard/step4.php';
				break;
			case 5:
				require_once __DIR__ . '/wizard/step5.php';
				break;
			default:
				require_once __DIR__ . '/wizard/step1.php';
		}

		require_once __DIR__ . '/wizard/_footer.php';
	}

	public static function get_prev( $step ) {
		if ( - 1 === $step ) {
			return '<div style="padding-bottom: 10px; border-bottom: 2px solid #486A82;
	width: 100px; float: left; text-align: center"
	><a href="' . admin_url( '' ) . '" style="text-decoration: none; color: #486A82"
	><span style="font-size: 24px;">&laquo;</span>' . __( 'Back to Admin', QA_MAIN_DOMAIN ) . '</a></div>';
		}

		if ( 1 === $step ) {
			return '';
		}

		return sprintf( self::$a_tmpl, 'left', esc_attr( $step ), esc_attr( $step - 1 ), '<span style="font-size: 24px;">&laquo;</span> Previous' );
	}

	/**
	 * @param $step
	 *
	 * @return string
	 */
	public static function get_next( $step ) {
		if ( - 1 === $step || self::MAX_STEP == $step ) {
			return '';
		}

		return sprintf( self::$a_tmpl, 'right', esc_attr( $step ), esc_attr( $step + 1 ), 'Next <span style="font-size: 24px;">&raquo;</span>' );
	}

	/**
	 * @param $step
	 * @param $text
	 * @param string $category
	 *
	 * @return string
	 */
	public static function get_checkbox( $step, $text, $category = '' ) {
		$id = array_search( $text, self::$categories[ $category ] );

		$name    = "{$category}-{$id}";
		$checked = isset( $_SESSION['wizard_answers'][ $step ][ $name ] ) ? 'checked="checked"' : '';

		return '<input type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' data-id="' . esc_attr( $id ) . '" /> ' . esc_html( $text );
	}

	/**
	 * @param $step
	 * @param $name
	 * @param $option
	 * @param $text
	 *
	 * @return string
	 */
	public static function get_radio( $step, $name, $option, $text ) {
		static $tmpl = '<input type="radio" name="%s" %s value="%s" /> <span class="sphd-p">Option %3$s: %s.</span>';

		$checked = isset( $_SESSION['wizard_answers'][ $step ][ $name ] ) && $_SESSION['wizard_answers'][ $step ][ $name ] == $option ? 'checked="checked"' : '';

		return sprintf( $tmpl, esc_attr( $name ), $checked, esc_attr( $option ), esc_html( $text ) );
	}

	/**
	 * Save Wizard Step
	 */
	public static function ajax_sphd_save_wizard_data() {
		$curr_step = (int) sanitize_text_field( $_POST['curr_step'] );
		$next_step = (int) sanitize_text_field( $_POST['next_step'] );
		unset( $_POST['curr_step'], $_POST['next_step'], $_POST['action'] );

		$redirect_url = admin_url( "admin.php?page=shelf_planner&wizard={$next_step}" );

		$post = array();
		foreach ( $_POST as $k => $v ) {
			$post[ $k ] = sanitize_text_field( $v );
		}

		if ( ! isset( $_SESSION['wizard_answers'] ) ) {
			$_SESSION['wizard_answers'] = array();
		}
		if ( $curr_step < self::MAX_STEP ) {
			$_SESSION['wizard_answers'][ $curr_step - 1 ] = $post;
		}

		wp_die( json_encode( array(
			'redirect_url' => $redirect_url,
		) ) );
	}

	/**
	 * Finish Wizard process
	 */
	public static function ajax_sphd_complete_wizard() {
		$total_steps = self::MAX_STEP - 1;
		$response    = array();
		$is_ok       = false;

		if ( isset( $_SESSION['wizard_answers'] ) && count( $_SESSION['wizard_answers'] ) == $total_steps ) {
			$is_ok = true;
			foreach ( $_SESSION['wizard_answers'] as $each_step => $each_block ) {
				if ( empty( $each_block ) ) {
					$error = "Can't get answers for Step: {$each_step}!";
					$is_ok = false;
					break;
				}
			}
		} else {
			$error = "Setup process not completed.";
		}

		if ( $is_ok ) {
			update_option( 'sp.settings.country', sanitize_title( $_SESSION['wizard_answers'][0]['sp_countries_list'] ) );
			update_option( 'sp.settings.industry', sanitize_title( implode( ',', $_SESSION['wizard_answers'][1] ) ) );
			update_option( 'sp.settings.business_model', sanitize_title( $_SESSION['wizard_answers'][2]['business-model'] ) );
			update_option( 'sp.settings.assortment_size', sanitize_title( $_SESSION['wizard_answers'][3]['assortment-size'] ) );
			update_option( 'sp.settings.force_zero_price_products', sanitize_title( $_SESSION['wizard_answers'][3]['force_zero_price_products'] ) );
			unset( $_SESSION['wizard_answers'] );

			update_option( 'sp.wizard_in_progress', 0 );
			$response['redirect_url'] = admin_url( "admin.php?page=shelf_planner" );
		} else {
			$response['error'] = $error;
		}

		$response['isOk'] = $is_ok;
		wp_die( json_encode( $response ) );
	}

	/**
	 * @return string
	 */
	public static function show_steps() {
		$step = self::get_step();
		$html = '<table style="margin: 24px auto;">
        <tr>
            <td rowspan="3" class="t1"><div class="sphd-circle' . ( 1 == $step ? ' sphd-step' : '' ) . '">1</div></td>
            <td class="t2"></td>
            <td rowspan="3" class="t1"><div class="sphd-circle' . ( 2 == $step ? ' sphd-step' : '' ) . '">2</div></td>
            <td class="t2"></td>
            <td rowspan="3" class="t1"><div class="sphd-circle' . ( 3 == $step ? ' sphd-step' : '' ) . '">3</div></td>
            <td class="t2"></td>
            <td rowspan="4" class="t1"><div class="sphd-circle' . ( 4 == $step ? ' sphd-step' : '' ) . '">4</div></td>
            <td class="t2"></td>
            <td rowspan="5" class="t1"><div class="sphd-circle' . ( 5 == $step ? ' sphd-step' : '' ) . '">5</div></td>
        </tr>
        <tr>
            <td class="t2"><div class="t3"></div></td>
            <td class="t2"><div class="t3"></div></td>
            <td class="t2"><div class="t3"></div></td>
            <td class="t2"><div class="t3"></div></td>
        </tr>
        <tr>
            <td class="t2"></td>
            <td class="t2"></td>
            <td class="t2"></td>
        </tr>
    </table>';

		return $html;
	}

}