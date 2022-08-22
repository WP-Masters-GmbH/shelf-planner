<?php

/**
 * @return array
 */
function sp_calc_stock_analyses() {
	global $wpdb;

	$category_id = isset( $_GET['category'] ) && is_numeric( $_GET['category'] ) ? (int) $_GET['category'] : false;

	$categories    = sp_get_categories();
	$products_data = sp_get_products_data( $category_id > 0 ? $category_id : implode( ',', array_keys( $categories ) ) );

	$category_fields = array(
		'count_of_products'    => 0,
		'ideal_stock'          => 0,
		'current_stock'        => 0,
		'inbound_stock'        => 0,
		'order_proposal_units' => 0,
		'order_value_cost'     => 0,
		'order_value_retail'   => 0,
		'weeks_to_stock_out'   => 0,
		'sales_l4w'            => 0,
		'sales_n4w'            => 0,
	);

	foreach ( $categories as $category_id => &$categories_item ) {
		$categories_item = array(
			'term_id' => $category_id,
			'name'    => htmlspecialchars_decode( $categories_item ),
			'cat_url' => get_term_link( (int) $category_id, 'product_cat' )
		);
		$categories_item = array_merge( $categories_item, $category_fields );
	}

	foreach ( $products_data as $product_id => $product_item ) {
		if ( $product_item['sp_primary_category'] == 0 ) {
			$category_id = \QAMain_Core::get_product_primary_category_id( $product_id );
			$wpdb->update( $wpdb->product_settings, array( 'sp_primary_category' => $category_id ), array( 'product_id' => $product_item['term_id'] ) );
		} else {
			$category_id = $product_item['sp_primary_category'];
		}

		$group_by = &$categories[ $category_id ];

		$group_by['ideal_stock']          += intval( $product_item['ideal_stock'] );
		$group_by['current_stock']        += intval( $product_item['current_stock'] );
		$group_by['inbound_stock']        += intval( $product_item['inbound_stock'] );
		$group_by['order_proposal_units'] += intval( $product_item['order_proposal_units'] );
		$group_by['weeks_to_stock_out']   += intval( $product_item['weeks_to_stock_out'] );
		$group_by['sales_l4w']            += intval( $product_item['sales_l4w'] );
		$group_by['sales_n4w']            += floatval( $product_item['sales_n4w'] );

		$group_by['order_value_cost']   += floatval( $product_item['order_value_cost'] );
		$group_by['order_value_retail'] += floatval( $product_item['order_value_retail'] );

		$group_by['count_of_products'] ++;
	}

	foreach ( $categories as &$category_item ) {
		$category_item['order_value_cost']   = sp_get_price( $category_item['order_value_cost'] );
		$category_item['order_value_retail'] = sp_get_price( $category_item['order_value_retail'] );
		$category_item['weeks_to_stock_out'] = floor( $category_item['weeks_to_stock_out'] / max( $category_item['count_of_products'], 1 ) );
		$category_item['sales_n4w']          = round( $category_item['sales_n4w'], 1 );
	}

	return array(
		array_values( $products_data ),
		array_values( $categories )
	);
}

/**
 * Products table data
 */
list ( $products_data, $categories_data ) = sp_calc_stock_analyses();

?><?php require_once __DIR__ . '/../' . 'header.php'; ?>
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
                            @media (min-width: 1200px) {
                                .container, .container-lg, .container-md, .container-sm, .container-xl {
                                    max-width: 95% !important;
                                }
                            }
                        </style>
                        <h2><?php echo esc_html(__( 'Stock Analyses', QA_MAIN_DOMAIN )); ?></h2>
                        <?php do_action( 'after_page_header' ); ?>
						<?php

						require_once __DIR__ . '/admin_page_header.php';

						?>
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
                                    <div style="float:left; height:2.5em; text-align: left;margin-bottom: 5px;width:30%">
										<?php echo esc_html( __( 'Stock Review by Category', QA_MAIN_DOMAIN ) ); ?>
                                    </div>
                                    <div style="float:right;height:2.5em; text-align: right;margin-bottom: 5px;width:70%">
                                        <button id="download-csv" class="btn btn-sm btn-info">
											<?php echo esc_html( __( 'Download CSV', QA_MAIN_DOMAIN ) ); ?>
                                        </button>
                                        <button id="download-json" class="btn btn-sm btn-info">
											<?php echo esc_html( __( 'Download JSON', QA_MAIN_DOMAIN ) ); ?>
                                        </button>
                                        <button id="download-xlsx" class="btn btn-sm btn-info">
											<?php echo esc_html( __( 'Download XLSX', QA_MAIN_DOMAIN ) ); ?>
                                        </button>
                                        <button id="download-html" class="btn btn-sm btn-info">
											<?php echo esc_html( __( 'Download HTML', QA_MAIN_DOMAIN ) ); ?>
                                        </button>
                                    </div>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row" style="width: 105% !important">
                                    <div class="col-md-12 col">
                                        <div id="table_1" style="width:99%;"></div>
                                        <script>
                                            //custom max min header filter
                                            let minMaxFilterEditor = function (cell, onRendered, success, cancel, editorParams) {

                                                let end;
                                                let container = document.createElement("span");

                                                //create and style inputs
                                                let start = document.createElement("input");
                                                start.setAttribute("type", "number");
                                                start.setAttribute("placeholder", "Min");
                                                start.style.padding = "4px";
                                                start.style.width = "50%";
                                                start.style.boxSizing = "border-box";

                                                start.value = cell.getValue();

                                                function buildValues() {
                                                    success({
                                                        start: start.value,
                                                        end: end.value,
                                                    });
                                                }

                                                function keypress(e) {
                                                    if (e.keyCode == 13) {
                                                        buildValues();
                                                    } else if (e.keyCode == 27) {
                                                        cancel();
                                                    }
                                                }

                                                end = start.cloneNode();
                                                end.setAttribute("placeholder", "Max");

                                                start.addEventListener("change", buildValues);
                                                start.addEventListener("blur", buildValues);
                                                start.addEventListener("keydown", keypress);

                                                end.addEventListener("change", buildValues);
                                                end.addEventListener("blur", buildValues);
                                                end.addEventListener("keydown", keypress);


                                                container.appendChild(start);
                                                container.appendChild(end);

                                                return container;
                                            }

                                            //custom max min filter function
                                            function minMaxFilterFunction(headerValue, rowValue, rowData, filterParams) {
                                                //headerValue - the value of the header filter element
                                                //rowValue - the value of the column in this row
                                                //rowData - the data for the row being filtered
                                                //filterParams - params object passed to the headerFilterFuncParams property

                                                if (rowValue) {
                                                    if (headerValue.start != "") {
                                                        if (headerValue.end != "") {
                                                            return rowValue >= headerValue.start && rowValue <= headerValue.end;
                                                        } else {
                                                            return rowValue >= headerValue.start;
                                                        }
                                                    } else {
                                                        if (headerValue.end != "") {
                                                            return rowValue <= headerValue.end;
                                                        }
                                                    }
                                                }

                                                return true; //must return a boolean, true if it passes the filter.
                                            }


                                            var tabledata = <?php echo json_encode( $categories_data );?>;

                                            var table = new Tabulator("#table_1", {
                                                // height:"311px",
                                                layout: "fitColumns",
                                                responsiveLayout: "collapse",
                                                data: tabledata,
                                                pagination: "local",
                                                paginationSize: 20,
                                                paginationSizeSelector: [20, 50, 100],
                                                initialSort: [
                                                    {column: "ideal_stock", dir: "desc"},
                                                ],
                                                columns: [
                                                    {
                                                        title: "ID",
                                                        field: "term_id",
                                                        hozAlign: "left",
                                                        sorter: "number",
                                                        headerFilter: "input",
                                                        headerFilterLiveFilter: false,
                                                        width: 50
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Category', QA_MAIN_DOMAIN );?>",
                                                        field: "name",
                                                        headerFilter: "input",
                                                        formatter: "link",
                                                        formatterParams: {
                                                            labelField: "name",
                                                            urlPrefix: "",
                                                            target: "_blank",
                                                            urlField: "cat_url"
                                                        }
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Ideal Stock', QA_MAIN_DOMAIN );?>",
                                                        field: "ideal_stock",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Current Stock', QA_MAIN_DOMAIN );?>",
                                                        field: "current_stock",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Inbound Stock', QA_MAIN_DOMAIN );?>",
                                                        field: "inbound_stock",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Order Proposal Units', QA_MAIN_DOMAIN );?>",
                                                        field: "order_proposal_units",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false,
                                                        /*
														formatter: "link",
														formatterParams: {
															labelField: "order_proposal_units",
															urlPrefix: "",
															target: "_blank",
															urlField: "cat_url_purchase_order"
														}
														*/
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Sales L4W', QA_MAIN_DOMAIN );?>",
                                                        // field: "order_value_cost",
                                                        field: "sales_l4w",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Forecast N4W', QA_MAIN_DOMAIN );?>",
                                                        // field: "order_value_retail",
                                                        field: "sales_n4w",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Weeks to Stock Out', QA_MAIN_DOMAIN );?>",
                                                        field: "weeks_to_stock_out",
                                                        hozAlign: "left",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false,
                                                        formatter: "progress",
                                                        formatterParams: {
                                                            min: -1,
                                                            max: 20,
                                                            legend: true,
                                                            color: ["red", "orange", "green"],
                                                            legendColor: "#000000",
                                                            legendAlign: "center",
                                                        }
                                                    },

                                                ],
                                            });

                                            //trigger download of data.csv file
                                            document.getElementById("download-csv").addEventListener("click", function () {
                                                table.download("csv", "data.csv");
                                            });

                                            //trigger download of data.json file
                                            document.getElementById("download-json").addEventListener("click", function () {
                                                table.download("json", "data.json");
                                            });

                                            //trigger download of data.xlsx file
                                            document.getElementById("download-xlsx").addEventListener("click", function () {
                                                table.download("xlsx", "data.xlsx", {sheetName: "My Data"});
                                            });

                                            //trigger download of data.html file
                                            document.getElementById("download-html").addEventListener("click", function () {
                                                table.download("html", "data.html", {style: true});
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
                                    <div style="float:left; height:2.5em; text-align: left;margin-bottom: 5px;width:50%">
										<?php echo esc_html( __( 'Stock Review by Product', QA_MAIN_DOMAIN ) ); ?>
                                    </div>
                                    <div style="float:right;height:2.5em; text-align: right;margin-bottom: 5px;width:50%">
                                        <button id="download-csv2" class="btn btn-sm btn-info">
											<?php echo esc_html( __( 'Download CSV', QA_MAIN_DOMAIN ) ); ?>
                                        </button>
                                        <button id="download-json2" class="btn btn-sm btn-info">
											<?php echo esc_html( __( 'Download JSON', QA_MAIN_DOMAIN ) ); ?>
                                        </button>
                                        <button id="download-xlsx2" class="btn btn-sm btn-info">
											<?php echo esc_html( __( 'Download XLSX', QA_MAIN_DOMAIN ) ); ?>
                                        </button>
                                        <button id="download-html2" class="btn btn-sm btn-info">
											<?php echo esc_html( __( 'Download HTML', QA_MAIN_DOMAIN ) ); ?>
                                        </button>
                                    </div>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row" style="width: 105% !important">
                                    <div class="col-md-12 col">
                                        <div id="table_2"></div>
                                        <script>
                                            var tabledata2 = <?php echo json_encode( $products_data );?>;

                                            var table2 = new Tabulator("#table_2", {
                                                // height:"311px",
                                                layout: "fitColumns",
                                                responsiveLayout: "collapse",
                                                data: tabledata2,
                                                pagination: "local",
                                                paginationSize: 50,
                                                paginationSizeSelector: [50, 100, 500],
                                                initialSort: [
                                                    {column: "ideal_stock", dir: "desc"},
                                                ],
                                                columns: [
                                                    {
                                                        title: "ID",
                                                        field: "term_id",
                                                        hozAlign: "left",
                                                        sorter: "number",
                                                        headerFilter: "input",
                                                        headerFilterLiveFilter: false,
                                                        width: 50
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Product Name', QA_MAIN_DOMAIN );?>",
                                                        field: "name",
                                                        headerFilter: "input",
                                                        formatter: "link",
                                                        formatterParams: {
                                                            labelField: "name",
                                                            urlPrefix: "",
                                                            target: "_blank",
                                                            urlField: "cat_url"
                                                        }
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Ideal Stock', QA_MAIN_DOMAIN );?>",
                                                        field: "ideal_stock",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Current Stock', QA_MAIN_DOMAIN );?>",
                                                        field: "current_stock",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Inbound Stock', QA_MAIN_DOMAIN );?>",
                                                        field: "inbound_stock",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Order Proposal Units', QA_MAIN_DOMAIN );?>",
                                                        field: "order_proposal_units",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false,
                                                        formatter: "link",
                                                        formatterParams: {
                                                            labelField: "order_proposal_units",
                                                            urlPrefix: "",
                                                            target: "_blank",
                                                            urlField: "cat_url_purchase_order"
                                                        }
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Sales L4W', QA_MAIN_DOMAIN );?>",
                                                        // field: "order_value_cost",
                                                        field: "sales_l4w",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Forecast N4W', QA_MAIN_DOMAIN );?>",
                                                        // field: "order_value_retail",
                                                        field: "sales_n4w",
                                                        hozAlign: "center",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Weeks to Stock Out', QA_MAIN_DOMAIN );?>",
                                                        field: "weeks_to_stock_out",
                                                        hozAlign: "left",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false,
                                                        formatter: "progress",
                                                        formatterParams: {
                                                            min: -1,
                                                            max: 20,
                                                            legend: true,
                                                            color: ["red", "orange", "green"],
                                                            legendColor: "#000000",
                                                            legendAlign: "center",
                                                        }
                                                    },

                                                ],
                                            });

                                            //trigger download of data.csv file
                                            document.getElementById("download-csv2").addEventListener("click", function () {
                                                table2.download("csv", "data.csv");
                                            });

                                            //trigger download of data.json file
                                            document.getElementById("download-json2").addEventListener("click", function () {
                                                table2.download("json", "data.json");
                                            });

                                            //trigger download of data.xlsx file
                                            document.getElementById("download-xlsx2").addEventListener("click", function () {
                                                table2.download("xlsx", "data.xlsx", {sheetName: "My Data"});
                                            });

                                            //trigger download of data.html file
                                            document.getElementById("download-html2").addEventListener("click", function () {
                                                table2.download("html", "data.html", {style: true});
                                            });
                                        </script>
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