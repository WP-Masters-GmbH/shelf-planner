<?php
if ( ! empty( $_POST ) ) {
	if ( isset( $_POST['save-store-settings'] ) ) {
		update_option( 'sp.settings.business_model', sanitize_text_field( $_POST['business-model'] ) );
		update_option( 'sp.settings.assortment_size', sanitize_text_field( $_POST['assortment-size'] ) );
		unset( $_POST['save-store-settings'], $_POST['business-model'], $_POST['assortment-size'] );
		$post_data = array();
		foreach ( array_keys( $_POST ) as $v ) {
			$v = str_replace( 'industry-', '', $v );
			if ( is_numeric( $v ) && $v > 0 ) {
				$post_data[] = (int) $v;
			}
		}
		if ( ! empty( $post_data ) ) {
			sort( $post_data );
			update_option( 'sp.settings.industry', implode( ',', $post_data ) );
		} else {
			update_option( 'sp.settings.industry', '' );
		}
	}
}
require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';
?>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
		<?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">
                <!-- container opened -->
                <div class="container">
	                <?php include SP_PLUGIN_DIR_PATH ."pages/header_js.php"; ?>
                    <style>
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }

                        .sphd-p {
                            font-size: 16px;
                        }
                    </style>
                    <h2><?php echo esc_html(__( 'Settings', QA_MAIN_DOMAIN )); ?></h2>
                    <?php do_action( 'after_page_header' ); ?>
                    <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div class="card">
                        <div class="card-body">
                            <h4><?php echo esc_html( __( 'Store Settings', QA_MAIN_DOMAIN ) ); ?></h4>
                            <p class="mg-b-20"></p>
                            <h4><?php echo esc_html( __( 'Please specify which industry your company belongs to.', QA_MAIN_DOMAIN ) ); ?>
                            </h4>
                            <form method="post">
                                <table style="width: 60%; margin: 3% 0">
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Fashion & Apparel' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Equestrian' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Health' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Footwear' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Drinks & Beverages' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Toys & Games' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Bags & Suitcases' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Food' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Bookshop' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Jewellery & Watches' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Kitchen & Dining' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Gardening' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Babywear' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Beauty & Personal Care' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'DIY' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Optical' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Home & Household' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Pet Store' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Sportswear & Sporting goods' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Furniture & Decoration' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Car Parts & Car Care' ) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Outdoor Life' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Consumer Electronics' ) ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Other' ) ?>
                                        </td>
                                    </tr>
                                </table>
                                <p class="mg-b-20"></p>
                                <h4 style="margin-bottom: 1em"><?php echo esc_html( __( 'Please specify your business model and how you sell your products.', QA_MAIN_DOMAIN ) ); ?></h4>
                                <p><?php echo  sp_settings_get_radio_1( 'A', 'Retail - my site sells directly to consumers' ); ?></p>
                                <p><?php echo  sp_settings_get_radio_1( 'B', 'Wholesale – my site sells business to business' ); ?></p>
                                <p><?php echo  sp_settings_get_radio_1( 'C', 'Multichannel – my site sells to both end consumers as well as business to business' ); ?></p>
                                <p class="mg-b-20"></p>
                                <h4 style="margin: 1em 0"><?php echo esc_html( __( 'Please specify the breath of your store.', QA_MAIN_DOMAIN ) ); ?></h4>
                                <p><?php echo  sp_settings_get_radio_2( 'A', 'my store has less than 250 products' ); ?></p>
                                <p><?php echo  sp_settings_get_radio_2( 'B', 'my store has between 250 and 1000 products' ); ?></p>
                                <p><?php echo  sp_settings_get_radio_2( 'C', 'my store has more than 1000 products' ); ?></p>
                                <p class="mg-b-20"></p>
                                <input style="margin-top: 2em" type="submit" class="btn btn-sm btn-success" value="<?php echo esc_attr( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-store-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>