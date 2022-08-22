<?php
global $wpdb;

require_once __DIR__ . '/admin_page_header.php';

// Get API Logs
$table = $wpdb->prefix.'sp_api_log';
$api_logs = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC LIMIT 25");
$count_logs = $wpdb->get_results("SELECT COUNT(*) as count FROM $table");
$pages_count = ceil($count_logs[0]->count / 25);

$api_logs = json_encode( array_values($api_logs) );

?><?php require_once __DIR__ . '/../' . 'header.php'; ?>
    <div class="sp-admin-overlay">
        <div class="sp-admin-container">
			<?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
            <!-- main-content opened -->
            <div class="main-content horizontal-content">
                <div class="page">
                    <!-- container opened -->
                    <div class="container">
                        <h2><?php echo esc_html(__( 'All API Logs', QA_MAIN_DOMAIN )); ?></h2>
                        <?php do_action( 'after_page_header' ); ?>
	                    <?php include SP_PLUGIN_DIR_PATH ."pages/header_js.php"; ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
									<?php echo esc_html(__( 'API Logs', QA_MAIN_DOMAIN )); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <div id="table-data"></div>
                                        <div class="qa-bulk-pagination logs-table">
                                            <button id="prev-logs-page" data-page="0" class="button action" disabled><</button>
                                            <span id="page-current">1 of <?php echo esc_html($pages_count); ?></span>
                                            <button id="next-logs-page" data-page="2" class="button action">></button>
                                        </div>
                                        <script>
                                            var table_data = <?php echo $api_logs;?>;

                                            // Build Tabulator
                                            // region tabulator columns
                                            let columns = [
                                                {
                                                    formatter: "rowSelection",
                                                    titleFormatter: "rowSelection",
                                                    hozAlign: "left",
                                                    pagination:"local",
                                                    headerSort: false,
                                                    cellClick: function (e, cell) {
                                                        cell.getRow().toggleSelect();
                                                    },
                                                },
                                                {
                                                    title: "ID",
                                                    field: "id",
                                                    width: 50,
                                                    hozAlign: "left"
                                                },
                                                {
                                                    title: "<?php echo __( 'Created', QA_MAIN_DOMAIN );?>",
                                                    field: "date_added",
                                                    hozAlign: "left",
                                                    sorter: "date",
                                                    formatter: "datetime",
                                                    formatterParams: {
                                                        inputFormat: "YYYY-MM-DD H:m:s",
                                                        outputFormat: "YYYY-MM-DD H:m:s",
                                                        invalidPlaceholder: "(invalid date)",
                                                    }
                                                },
                                                {
                                                    title: "<?php echo __( 'Type', QA_MAIN_DOMAIN );?>",
                                                    field: "type",
                                                    width: 100
                                                },
                                                {
                                                    title: "<?php echo __( 'Message', QA_MAIN_DOMAIN );?>",
                                                    field: "message",
                                                    hozAlign: "left",
                                                    width: 100
                                                }
                                            ];
                                            //endregion

                                            let table = new Tabulator("#table-data", {
                                                layout: "fitDataStretch",
                                                data: table_data,
                                                placeholder: "No Data",
                                                columns: columns,
                                                width: 800
                                            });

                                            jQuery(document).ready(function($) {

                                                var page = 1;
                                                var all_count = <?php echo esc_html($pages_count); ?>;

                                                $("body").on("click","#prev-logs-page, #next-logs-page",function() {
                                                    page = $(this).data('page');
                                                    $('#prev-logs-page').data()
                                                    get_logs();
                                                });

                                                function get_logs()
                                                {
                                                    $.ajax({
                                                        url: ajaxurl,
                                                        data: {
                                                            'action': 'get_logs_table',
                                                            'page': page
                                                        },
                                                        type:'POST',
                                                        dataType: 'json',
                                                        success:function(response) {
                                                            if(response.status === 'true') {
                                                                table.replaceData(response.table);
                                                                $('#page-current').text(page+' of '+all_count);
                                                                $('#prev-logs-page').data('page', page-1);
                                                                $('#next-logs-page').data('page', page+1);

                                                                if($('#prev-logs-page').data('page') < 1) {
                                                                    $('#prev-logs-page').prop('disabled', true);
                                                                } else {
                                                                    $('#prev-logs-page').prop('disabled', false);
                                                                }

                                                                if($('#next-logs-page').data('page') > all_count) {
                                                                    $('#next-logs-page').prop('disabled', true);
                                                                } else {
                                                                    $('#next-logs-page').prop('disabled', false);
                                                                }
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        </script>
                                        <style>
                                            #table-data {
                                                max-width: 100%;
                                            }
                                        </style>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require_once __DIR__ . '/../' . 'footer.php';