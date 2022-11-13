<?php
if ( ! empty( $_POST ) ) {
	if ( isset( $_POST['save-backorder'] ) ) {
		update_option( 'sp.backorder', isset( $_POST['backorder'] ) ? 'enable' : '' );
	}
	if ( isset( $_POST['save-store-settings'] ) ) {
		update_option( 'sp.settings.business_model', sanitize_text_field( $_POST['business-model'] ) );
		update_option( 'sp.settings.assortment_size', sanitize_text_field( $_POST['assortment-size'] ) );

		unset( $_POST['save-store-settings'], $_POST['business-model'], $_POST['assortment-size'] );

		$post_data = array();

		foreach ( array_keys( $_POST ) as $v ) {
			$v = str_replace( 'industry-', '', $v );
			if ( is_numeric( $v ) && $v > 0 ) {
			    // Sanitize
				$post_data[] = (int) $v;
			}
		}

		if ( ! empty( $post_data ) ) {
			sort( $post_data );
			update_option( 'sp.settings.industry', implode( ',', $post_data ) );
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
                    <script>
                        function sp_switch_tabs(tab) {
                            const tabs_ids = [
                                "id-block-industry",
                                "id-block-business-model",
                                "id-block-assortment-size"
                            ];

                            for (let i = 1; i <= 3; i++) {
                                let e1 = jQuery("#sp-settings-tab-" + i);
                                let e2 = jQuery("#" + tabs_ids[i - 1]);
                                e1.removeClass("nav-tab-active");
                                e2.hide();
                                if (i === tab) {
                                    e1.addClass("nav-tab-active");
                                    e2.show();
                                }
                            }
                        }
                    </script>
                    <style>
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }
                    </style>
                    <h2><?php echo esc_html(__( 'Settings', QA_MAIN_DOMAIN )); ?></h2>
                    <div class="card">
                        <div class="card-body">
                            <h4><?php echo esc_html( __( 'Please specify which industry your company belongs to.', QA_MAIN_DOMAIN ) ); ?>
                            </h4>
                            <form method="post">
                                <table style="width: 60%; margin: 3% 0">
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Fashion & Apparel' ); ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Home & Kitchen' ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Sports & Outdoor' ); ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Consumer Electronics' ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Footwear' ); ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Health & Household' ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Beauty & Personal Care' ); ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Toys & Games' ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Jewellery & Watches' ); ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Books & Magazines' ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Baby Wear' ); ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'DIY & Gardening' ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Optical' ); ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'DIY & Gardening' ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Food & Drink' ); ?>
                                        </td>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Pet Care' ); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
											<?php echo  sp_settings_get_checkbox( 'Furniture & Decoration' ); ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>
                                <p class="mg-b-20"></p>
                                <h4 style="margin: 1em 0"><?php echo esc_html( __( 'Please specify the breath of your store.', QA_MAIN_DOMAIN ) ); ?></h4>
                                <p><?php echo  sp_settings_get_radio_2( 'A', 'my store has less than 250 products' ); ?></p>
                                <p><?php echo  sp_settings_get_radio_2( 'B', 'my store has between 250 and 1000 products' ); ?></p>
                                <p><?php echo  sp_settings_get_radio_2( 'C', 'my store has more than 1000 products' ); ?></p>
                                <input style="margin-top: 2em" type="submit" class="new-des-btn save-set-btn" value="<?php echo esc_attr( __( 'Save Settings', QA_MAIN_DOMAIN ) ); ?>" name="save-store-settings"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>