<?php
$filters = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( ! in_array( 'woocommerce/woocommerce.php', $filters ) && ! function_exists( 'wc_version' ) ) {
	echo '<h1 style="color:red;text-align:center">' . esc_html( __( 'WooCommerce still not installed yet!', QA_MAIN_DOMAIN ) ) . '</h1>';
	$step = - 1;
} else {
	$step = 1;
	?>
    <h2 class="sphd-category" style="margin-bottom: 15px"><?php echo esc_html( __( 'Company Information', QA_MAIN_DOMAIN ) ); ?></h2>
    <span><?php echo esc_html( __( 'Please specify the home base of your company', QA_MAIN_DOMAIN ) ); ?></span>
    <h4><?php echo esc_html( __( 'Country', QA_MAIN_DOMAIN ) ); ?></h4>

    <p>
		<?php

		global $woocommerce;
		if(function_exists('woocommerce_form_field')) {
			?>
            <select name="sp_countries_list">
                <option value="XX">Select Country</option>
                <?php foreach($sp_countries_normilized as $country_code => $country_name){  ?>
                <option value="<?php echo esc_attr($country_code); ?>"><?php echo esc_html($country_name); ?></option>
                <?php } ?>
            </select>
            <?php
		}
		?>
    </p>
<?php } ?>
