<?php
$step = 3;
?>
<h2 class="sphd-category"><?php echo esc_html( __( 'Business Model', QA_MAIN_DOMAIN ) ); ?></h2>
<h4><?php echo esc_html( __( 'Please specify your business model and how you sell your products.', QA_MAIN_DOMAIN ) ); ?></h4>

<p><?php echo  SPHD_Wizard::get_radio( 2, 'business-model', 'A', 'Retail - my site sells directly to consumers' ); ?></p>
<p><?php echo  SPHD_Wizard::get_radio( 2, 'business-model', 'B', 'Wholesale – my site sells business to business' ); ?></p>
<p><?php echo  SPHD_Wizard::get_radio( 2, 'business-model', 'C', 'Multichannel – my site sells to both end consumers as well as business to business' ); ?></p>
