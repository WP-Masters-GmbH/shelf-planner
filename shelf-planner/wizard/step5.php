<?php
$step = 5;
?>
<h1 class="sphd-category"><?php echo esc_html( __( 'Success!', QA_MAIN_DOMAIN ) ); ?></h1>
<div style="width: 48%; text-align: left; position: relative; display: inline-block;
vertical-align: top; margin-top: 45px">
    <img style="position: relative; width: 90%" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>assets/shelf_planner_by_quick_assortments.png">
</div>
<div style="width: 51%; text-align: left; position: relative; display: inline-block"
     class="sphd-final-text">
    <p style="color: #486A82; font-weight: bold;"><?php echo esc_html( __( 'That’s it!', QA_MAIN_DOMAIN ) ); ?></p>
    <p><?php echo ( __( 'Shelf Planner will now create advanced scenarios for your business, based on your performance, stock, and hundreds of other data points.', QA_MAIN_DOMAIN ) ); ?></p>
    <p><?php echo ( __( 'For tutorials or instructions, have a look at our dedicated <a href="https://www.youtube.com/channel/UCZxCYDp2ToyWcAxaqnNDBPg" target="_blank">Youtube channel</a> for Shelf Planner.', QA_MAIN_DOMAIN ) ); ?></p>
    <p><?php echo ( __( 'You will find tips and tricks on our user guides on <a href="https://shelfplanner.com/merchants" target="_blank">shelfplanner.com/merchants</a>', QA_MAIN_DOMAIN ) ); ?></p>

    <p><?php echo ( __( 'All you need to do now is complete the installation by pressing the button below. This will run a few processes in the background to perform an initial analyses.', QA_MAIN_DOMAIN ) ); ?></p>
    <p><?php echo ( __( 'Once that’s done, you’re good to go!', QA_MAIN_DOMAIN ) ); ?></p>
    <button class="button-primary sphd-finish-button" onclick="complete_wizard(); return false"><?php echo esc_html( __( 'Complete Installation', QA_MAIN_DOMAIN ) ); ?></button>
</div>
