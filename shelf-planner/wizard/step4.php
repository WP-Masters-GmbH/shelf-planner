<?php
$step = 4;
?>
<h2 class="sphd-category"><?php echo esc_html( __( 'Assortment Size', QA_MAIN_DOMAIN ) ); ?></h2>
<h4><?php echo esc_html( __( 'Please specify the breath of your store.', QA_MAIN_DOMAIN ) ); ?></h4>

<p class="sphd-p"><?php echo esc_html( __( 'This information is used to define the current performance of your store and to allow us to create different scenarios.', QA_MAIN_DOMAIN ) ); ?></p>

<p><?php echo  SPHD_Wizard::get_radio( 3, 'assortment-size', 'A', 'my store has less than 250 products' ); ?></p>
<p><?php echo  SPHD_Wizard::get_radio( 3, 'assortment-size', 'B', 'my store has between 250 and 1000 products' ); ?></p>
<p><?php echo  SPHD_Wizard::get_radio( 3, 'assortment-size', 'C', 'my store has more than 1000 products' ); ?></p>
<p>
    <input type="checkbox" id="id-force-zero-price-products" name="force_zero_price_products"<?php $checked = isset( $_SESSION['wizard_answers'][ $step ]['force_zero_price_products'] ) ? ' checked="checked"' : ''; echo esc_attr( $checked ); ?>>
    <label for="id-force-zero-price-products" style="font-weight: normal"> <?php echo esc_html( __( 'Add Force include products with zero cost price?', QA_MAIN_DOMAIN ) ); ?></label>
</p>

