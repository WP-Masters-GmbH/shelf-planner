<?php
if ( $_POST && isset( $_POST['forecast_json'] ) ) {
	$forecast_json = (array) json_decode( $_POST['forecast_json'], true );
	$forecast_json = json_encode( $forecast_json );

	update_option( 'sp.last_forecast', $forecast_json );
	update_option( 'sp.last_forecast_success', time() );
	?>
    <script>
        alert('Forecast updated successfully');
    </script>
<?php
}

require_once SP_ROOT_DIR . '/header.php'; ?>
    <style>
        .card {
            max-width: 520px;
        }
        .header-menu {
            max-width: 100%;
        }
    </style>
    <div class="sp-admin-overlay">
        <div class="sp-admin-container">
			<?php include SP_ROOT_DIR . "/left_sidebar.php"; ?>
            <!-- main-content opened -->
            <div class="main-content horizontal-content">
                <div class="page">
                    <!-- container opened -->
                    <div class="container">
                        <div class="breadcrumb-header justify-content-between">
                            <div class="my-auto">
                                <div class="d-flex"><h4 class="content-title mb-0 my-auto"><?php echo esc_html(__( 'Integrations', QA_MAIN_DOMAIN )); ?></h4></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <?php do_action( 'after_page_header' ); ?>
                            </div>
                        </div>
                        <div class="row row-sm">
                            <div class="col-xl-12 col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="main-content-label mg-b-5">
											<?php echo esc_html(__( 'Settings', QA_MAIN_DOMAIN )); ?>
                                        </div>
                                        <p class="mg-b-20"></p>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label class="ckbox"><input checked="<?php echo esc_attr( get_option( 'sp.in_background', 'checked' ) ); ?>" type="checkbox"
                                                                            onchange='jQuery.get(ajaxurl, {"action": "sp-ajax", "bg": jQuery(this).prop("checked"));'><span style="display: inline-block;padding-top: 3px;"><?php echo esc_html(__( 'Work in Background', QA_MAIN_DOMAIN )); ?></span></label>
                                            </div>
                                            <div class="col-lg-6 mg-t-20 mg-lg-t-0" style="white-space: nowrap !important; text-wrap: avoid">
                                                <label class="ckbox"><input <?php echo esc_attr( get_option( 'sp.log', 'checked' ) ); ?> type="checkbox"
                                                  onchange='jQuery.get(ajaxurl, {"action": "sp-ajax", "log": jQuery(this).prop("checked"));'><span style="display: inline-block;padding-top: 3px;"><?php echo esc_html(__( 'Debug Log', QA_MAIN_DOMAIN )); ?></span></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h3 class="card-title mb-2"><?php echo esc_html(__( 'Orders Analytics', QA_MAIN_DOMAIN )); ?></h3>
                                        <p class="tx-12 mb-0 text-muted">
											<?php echo esc_html(__( 'Shelf Planner collects and analyze your store historical data in order to build forecasts. This diagram shows the orders import progress.', QA_MAIN_DOMAIN )); ?>
                                            <br><br>
                                        </p>
                                    </div>
                                    <div class="card-body sales-info ot-0 pt-0 pb-0">
                                        <div id="chart-sp" class="ht-150" style="margin-bottom: 2em !important;"></div>
                                        <div class="row sales-infomation pb-0 mb-0 mx-auto wd-100p">
                                            <div class="col-md-6 col">
                                                <p class="mb-0 d-flex"><span class="legend bg-primary brround"></span>Analyzed </p>
                                                <h3 class="mb-1" id="sp-analyzed-orders-count"><?php echo esc_html(ShelfPlannerCore::getAnalyzedOrdersCount()); ?></h3>
                                                <div class="d-flex">
                                                    <p class="text-muted "><?php echo esc_html(__( 'All Time Data', QA_MAIN_DOMAIN )); ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col">
                                                <p class="mb-0 d-flex"><span class="legend bg-info brround"></span><?php echo esc_html(__( 'Total', QA_MAIN_DOMAIN )); ?></p>
                                                <h3 class="mb-1" id="sp-total-orders-count"><?php echo esc_html( ShelfPlannerCore::getOrdersCount() ); ?></h3>
                                                <div class="d-flex">
                                                    <p class="text-muted"><?php echo esc_html(__( 'All Time Data', QA_MAIN_DOMAIN )); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="main-content-label mg-b-5">
											<?php echo esc_html(__( 'Debug Log', QA_MAIN_DOMAIN )); ?>
                                        </div>
                                        <p class="mg-b-20"></p>
                                        <div class="row">
                                            <div class="col-md-12 col">
                                                <div class="d-flex">
                                                    <p class="">
                                                        <a href="<?php echo esc_url(admin_url( 'admin.php?page=shelf_planner_api_logs' )); ?>" target="_blank" class="btn btn-info"><i class="fa fa-eye"></i> <?php echo esc_html(__( 'View Debug Log', QA_MAIN_DOMAIN )); ?></a>
                                                        <!--<a href="<?php /*echo  plugin_dir_url( SP_FILE_INDEX ) */?>api.log?<?php /*echo  time(); */?>" target="_blank" class="btn btn-info"><i class="fa fa-eye"></i> <?php /*echo  __( 'View Debug Log', QA_MAIN_DOMAIN ); */?></a>-->
                                                        <a href="javascript:void(0)"
                                                            onclick="sp_get_forecast_json(jQuery(this).data('json-url'))" class="btn btn-info">
                                                            <i class="fa fa-download"></i>
		                                                    <?php echo esc_html(__( 'Update Forecast Data', QA_MAIN_DOMAIN )); ?></a>
                                                        <br><br>
                                                    </p>
                                                </div>
                                                <div class="col-md-12 col" style="padding-left: 0px;">
                                                    <div class="">
                                                        <div id="json_textarea" style="display: none">
                                                            <strong>Manual Forecast Update:</strong><br>
                                                            1. Open <a href="<?php echo esc_attr( sp_get_forecast_json_url() ) ?>" target="_blank">this link</a> and copy all the contents to clipboard<br>
                                                            2. Paste contents at this field and click Save
                                                            <form method="post">
                                                                <textarea name="forecast_json" style="width:100%;rows:10px;"></textarea>
                                                                <input type="submit" class="btn btn-sm btn-info" value="Save" />
                                                            </form>
                                                        <br><br>
                                                        </div>
                                                        </div>
                                                </div>
                                                <div class="col-md-12 col" style="padding-left: 0px;">
                                                    <div class="d-flex">
                                                    <p>
                                                    <a href="javascript:void(0);" onclick="jQuery.get('<?php echo esc_url(get_admin_url( null, '?sp_purge_api_log' )); ?>');
                                                                alert('<?php echo esc_html(__( 'Log was successfully purged', QA_MAIN_DOMAIN )); ?>');" class="btn btn-danger"><i class="fa fa-trash"></i> <?php echo esc_html(__( 'Purge Debug Log', QA_MAIN_DOMAIN )); ?></a>
                                                        <a href="javascript:void(0);" onclick="jQuery.get('<?php echo esc_url(get_admin_url( null, '?sp_clear_api_sent_entries' )); ?>'); alert('<?php echo esc_html(__( 'Entries was successfully cleared', QA_MAIN_DOMAIN )); ?>');" class="btn btn-danger"><i class="fa fa-trash"></i>
															<?php echo esc_html(__( 'Clear API Sent Entries', QA_MAIN_DOMAIN )); ?></a>

                                                    </p>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    function sp_get_forecast_json(url){
        //var copied_test = prompt("Please copy text from new tab, insert here and submit");
        jQuery('#json_textarea').toggle();
    }
</script>
<?php require_once SP_ROOT_DIR . '/footer.php';