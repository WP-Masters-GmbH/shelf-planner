<style>
  .wp-core-ui select {
    border: 1px solid #DEDEDF;
    border-radius: 4px;
    height: 38px;
    padding-left: 20px;
  }

  .wp-core-ui select:active, .wp-core-ui select:hover, .wp-core-ui select:focus {
    border-color: #DEDEDF;
    box-shadow: none;
    color: #000000;
    box-shadow: none;
    outline: none;
  }

  .wp-core-ui select {
    font-weight: 500;
    font-size: 16px;
    line-height: 19px;
    background: #fff url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E') no-repeat right 15px top 55%;
  }

  hr {
    border: none;
  }

</style>

<?php
$filters = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( ! in_array( 'woocommerce/woocommerce.php', $filters ) && ! function_exists( 'wc_version' ) ) {
	echo '<h1 style="color:red;text-align:center">' . esc_html( __( 'WooCommerce still not installed yet!', QA_MAIN_DOMAIN ) ) . '</h1>';
	$step = - 1;
} else {
	$step = 1;
	?>
    <h2 class="sphd-category" style="margin-bottom: 15px"><?php echo esc_html( __( 'Company Information', QA_MAIN_DOMAIN ) ); ?></h2>
    <span class="steps-span"><?php echo esc_html( __( 'Please specify the home base of your company', QA_MAIN_DOMAIN ) ); ?></span>
    <h4 class="steps-span"><?php echo esc_html( __( 'Country', QA_MAIN_DOMAIN ) ); ?></h4>

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
