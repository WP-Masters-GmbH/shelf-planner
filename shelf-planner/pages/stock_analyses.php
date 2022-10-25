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
		'sales_l26w'           => 0,
		'sales_l8w'            => 0,
		'sales_l4w'            => 0,
		'sales_n4w'            => 0,
		'sales_n8w'            => 0,
		'sales_n26w'           => 0,
        'backorders'           => 0,
        'stock_value' => 0
	);

	// Calculate Backorders count
	$backorders = wc_get_orders(array(
			'limit' => -1,
			'type' => 'shop_order',
			'status' => array('wc-backordered'),
		)
	);

	$backorders_stats = [];
	foreach($backorders as $backorder) {
	    $back_order = wc_get_order($backorder->ID);

		// Get and Loop Over Order Items
		foreach ( $back_order->get_items() as $item ) {

		    // Add Stats to Products
			$product_id = $item->get_product_id();
			$quantity = $item->get_quantity();
			$backorders_stats['products'][$product_id] = isset($backorders_stats['products'][$product_id]) ? $backorders_stats['products'][$product_id] + $quantity : $quantity;

			// Add Stats tp Categories
			$terms = get_the_terms ( $product_id, 'product_cat' );
			if(!empty($terms)) {
				foreach ( $terms as $term ) {
					$backorders_stats['categories'][$term->id] = isset($backorders_stats['categories'][$term->id]) ? $backorders_stats['categories'][$term->id] + $quantity : $quantity;;
				}
            }
        }
    }

	foreach ( $categories as $category_id => &$categories_item ) {
		$categories_item = array(
			'term_id' => $category_id,
			'name'    => htmlspecialchars_decode( $categories_item ),
			'cat_url' => get_term_link( (int) $category_id, 'product_cat' ),
            'backorders' => isset($backorders_stats['categories'][$category_id]) ? $backorders_stats['categories'][$category_id] : 0
		);
		$categories_item = array_merge( $categories_item, $category_fields );
	}

	foreach ( $products_data as $product_id => &$product_item ) {
		if ( $product_item['sp_primary_category'] == 0 ) {
			$category_id = \QAMain_Core::get_product_primary_category_id( $product_id );
			$wpdb->update( $wpdb->product_settings, array( 'sp_primary_category' => $category_id ), array( 'product_id' => $product_item['term_id'] ) );
		} else {
			$category_id = $product_item['sp_primary_category'];
		}

		$group_by = &$categories[ $category_id ];

		$product_item['backorders'] = isset($backorders_stats['products'][$product_item['term_id']]) ? $backorders_stats['products'][$product_item['term_id']] : 0;
		$product_item['sales_l26w'] = get_last_orders_by_week(26, $product_item['term_id']);
		$product_item['sales_l8w'] = get_last_orders_by_week(8, $product_item['term_id']);
		$product_item['sales_n8w'] = $product_item['next_8_weeks'];
		$product_item['sales_n26w'] = $product_item['next_26_weeks'];
		$product_item['stock_value'] = intval(get_post_meta( $product_item['term_id'], '_stock', true )) * intval($product_item['cost_price']);

        $group_by['stock_value']          += intval( $product_item['stock_value'] );
		$group_by['ideal_stock']          += intval( $product_item['ideal_stock'] );
		$group_by['current_stock']        += intval( $product_item['current_stock'] );
		$group_by['inbound_stock']        += intval( $product_item['inbound_stock'] );
		$group_by['order_proposal_units'] += intval( $product_item['order_proposal_units'] );
		$group_by['weeks_to_stock_out']   += intval( $product_item['weeks_to_stock_out'] );
		$group_by['sales_l26w']           += intval($product_item['sales_l26w']);
		$group_by['sales_l8w']            += intval($product_item['sales_l8w']);
		$group_by['sales_l4w']            += intval( $product_item['sales_l4w'] );
		$group_by['sales_n4w']            += floatval( $product_item['sales_n4w'] );
		$group_by['sales_n8w']            += floatval( $product_item['next_8_weeks'] );
		$group_by['sales_n26w']           += floatval( $product_item['next_26_weeks'] );

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
                <?php include __DIR__ . '/../' . "page_header.php"; ?>
                    <!-- container opened -->
                    <div class="ml-40 mr-40">
                        <?php include SP_PLUGIN_DIR_PATH ."pages/header_js.php"; ?>
                        <style>
                            .purchase-or-subtitle {
    color: #000000;
    font-size: 16px;
    line-height: 22px;
    opacity: 0.7;
    font-family: "Lato";
    font-weight: 400;
  }

  .mt-20 {
    margin-top: 20px;
  }

  .mb-20 {
    margin-bottom: 20px;
  }

  .create-btn:hover, .create-btn:focus {
  color: #FFF !important;
  outline: none;
  box-shadow: none;
}
                            @media (min-width: 1200px) {
                                .container, .container-lg, .container-md, .container-sm, .container-xl {
                                    max-width: 95% !important;
                                }
                            }
                        </style>
                        <h2 class="purchase-or-title"><?php echo esc_html(__( 'Inventory', QA_MAIN_DOMAIN )); ?></h2>
                        <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Manage, analyse and control your current and incoming stock, backorders and safety stock.', QA_MAIN_DOMAIN )); ?></span>
                        <div class="d-flex nav-link-line" style="margin-top: 40px;">
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_inventory' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_inventory')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Stock Analyses', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_manage_store' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_manage_store')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Manage Inventory', QA_MAIN_DOMAIN)); ?></span></a>
                          <!-- <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Stock Detail', QA_MAIN_DOMAIN)); ?></span></a> -->
                        </div>
                        <h2 class="purchase-or-title" style="margin-top: 50px;"><?php echo esc_html(__( 'Stock Analyses', QA_MAIN_DOMAIN )); ?></h2>
                        <?php
                            $first_columns = ['term_id', 'name', 'ideal_stock', 'current_stock', 'inbound_stock', 'order_proposal_units', 'sales_l4w', 'sales_n4w', 'weeks_to_stock_out'];
                        ?>
                        <div class="filters-enabled mt-20 mb-20">
                            <div class="filter-groupe first-table-select-column">
                                <input id="product_id_input" type="checkbox" value="term_id" <?php if(in_array('term_id', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="product_id_input">Product ID</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="product_product_name_input" type="checkbox" value="name" <?php if(in_array('name', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="product_product_name_input">Category</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="product_stock_value_input" type="checkbox" value="stock_value" <?php if(in_array('stock_value', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="product_stock_value_input">Stock Value</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_ideal_stock_input" type="checkbox" value="ideal_stock" <?php if(in_array('ideal_stock', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_ideal_stock_input">Ideal Stock</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_current_stock_input" type="checkbox" value="current_stock" <?php if(in_array('current_stock', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_current_stock_input">Current Stock</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_inbound_stock_input" type="checkbox" value="inbound_stock" <?php if(in_array('inbound_stock', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_inbound_stock_input">Inbound Stock</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_backorders_input" type="checkbox" value="backorders" <?php if(in_array('backorders', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_backorders_input">Backorders</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_order_proposal_units_input" type="checkbox" value="order_proposal_units" <?php if(in_array('order_proposal_units', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_order_proposal_units_input">Order Proposal Units</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_sales_l26w_input" type="checkbox" value="sales_l26w" <?php if(in_array('sales_l26w', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_sales_l26w_input">Sales L26W</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_sales_l8w_input" type="checkbox" value="sales_l8w" <?php if(in_array('sales_l8w', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_sales_l8w_input">Sales L8W</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_sales_l4w_input" type="checkbox" value="sales_l4w" <?php if(in_array('sales_l4w', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_sales_l4w_input">Sales L4W</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_forecast_26w_input" type="checkbox" value="sales_n26w" <?php if(in_array('sales_n26w', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_forecast_26w_input">Forecast N26W</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_forecast_8w_input" type="checkbox" value="sales_n8w" <?php if(in_array('sales_n8w', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_forecast_8w_input">Forecast N8W</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_forecast_4w_input" type="checkbox" value="sales_n4w" <?php if(in_array('sales_n4w', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_forecast_4w_input">Forecast N4W</label>
                            </div>
                            <div class="filter-groupe first-table-select-column">
                                <input id="sp_weeks_out_of_stock_input" type="checkbox" value="weeks_to_stock_out" <?php if(in_array('weeks_to_stock_out', $first_columns)) {echo esc_attr('checked');}?>>
                                <label for="sp_weeks_out_of_stock_input">Weeks To Stock Out</label>
                            </div>
                        </div>
                        <?php do_action( 'after_page_header' ); ?>
						<?php

						require_once __DIR__ . '/admin_page_header.php';

						?>
                        <div>
                            <div class="main-content-label mg-b-5">
                                <div style="float:left; height:2.5em; text-align: left;margin-bottom: 5px;width:30%">
                                    <?php echo esc_html( __( 'Stock Review by Category', QA_MAIN_DOMAIN ) ); ?>
                                </div>
                                <div style="float:right;height:2.5em; margin-bottom: 5px;width:50%; position: relative; right: -270px;">
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
                            <div class="row" style="width: 100% !important">
                                <div class="col-md-12 col">
                                    <div id="table_1" style="width:100%;"></div>
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
                                                    title: "Backorders",
                                                    field: "backorders",
                                                    hozAlign: "left",
                                                    headerFilter: "input",
                                                    visible: false
                                                },
                                                {
                                                    title: "<?php echo __( 'Stock Value', QA_MAIN_DOMAIN );?>",
                                                    field: "stock_value",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
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
                                                    title: "<?php echo __( 'Sales L26W', QA_MAIN_DOMAIN );?>",
                                                    // field: "order_value_cost",
                                                    field: "sales_l26w",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
                                                },
                                                {
                                                    title: "<?php echo __( 'Sales L8W', QA_MAIN_DOMAIN );?>",
                                                    // field: "order_value_cost",
                                                    field: "sales_l8w",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
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
                                                    title: "<?php echo __( 'Forecast N8W', QA_MAIN_DOMAIN );?>",
                                                    // field: "order_value_retail",
                                                    field: "sales_n8w",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
                                                },
                                                {
                                                    title: "<?php echo __( 'Forecast N26W', QA_MAIN_DOMAIN );?>",
                                                    // field: "order_value_retail",
                                                    field: "sales_n26w",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
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
                        <div style="margin-top: 100px">
	                        <?php
	                            $two_columns = ['term_id', 'name', 'ideal_stock', 'current_stock', 'inbound_stock', 'order_proposal_units', 'sales_l4w', 'sales_n4w', 'weeks_to_stock_out'];
	                        ?>
                        <div class="filters-enabled mt-20 mb-20">
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="product_id_input_two" type="checkbox" value="term_id" <?php if(in_array('term_id', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="product_id_input_two">Product ID</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="product_product_name_input_two" type="checkbox" value="name" <?php if(in_array('name', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="product_product_name_input_two">Product Name</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_supplier_id_input_two" type="checkbox" value="supplier_name" <?php if(in_array('supplier_name', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_supplier_id_input_two">Supplier</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="product_stock_value_input_two" type="checkbox" value="stock_value" <?php if(in_array('stock_value', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="product_stock_value_input_two">Stock Value</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_ideal_stock_input_two" type="checkbox" value="ideal_stock" <?php if(in_array('ideal_stock', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_ideal_stock_input_two">Ideal Stock</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_current_stock_input_two" type="checkbox" value="current_stock" <?php if(in_array('current_stock', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_current_stock_input_two">Current Stock</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_inbound_stock_input_two" type="checkbox" value="inbound_stock" <?php if(in_array('inbound_stock', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_inbound_stock_input_two">Inbound Stock</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_backorders_input_two" type="checkbox" value="backorders" <?php if(in_array('backorders', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_backorders_input_two">Backorders</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_order_proposal_units_input_two" type="checkbox" value="order_proposal_units" <?php if(in_array('order_proposal_units', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_order_proposal_units_input_two">Order Proposal Units</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_sales_l26w_input_two" type="checkbox" value="sales_l26w" <?php if(in_array('sales_l26w', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_sales_l26w_input_two">Sales L26W</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_sales_l8w_input_two" type="checkbox" value="sales_l8w" <?php if(in_array('sales_l8w', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_sales_l8w_input_two">Sales L8W</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_sales_l4w_input_two" type="checkbox" value="sales_l4w" <?php if(in_array('sales_l4w', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_sales_l4w_input_two">Sales L4W</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_forecast_26w_input_two" type="checkbox" value="sales_n26w" <?php if(in_array('sales_n26w', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_forecast_26w_input_two">Forecast N26W</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_forecast_8w_input_two" type="checkbox" value="sales_n8w" <?php if(in_array('sales_n8w', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_forecast_8w_input_two">Forecast N8W</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_forecast_4w_input_two" type="checkbox" value="sales_n4w" <?php if(in_array('sales_n4w', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_forecast_4w_input_two">Forecast N4W</label>
                                        </div>
                                        <div class="filter-groupe two-table-select-column">
                                            <input id="sp_weeks_out_of_stock_input_two" type="checkbox" value="weeks_to_stock_out" <?php if(in_array('weeks_to_stock_out', $two_columns)) {echo esc_attr('checked');}?>>
                                            <label for="sp_weeks_out_of_stock_input_two">Weeks To Stock Out</label>
                                        </div>
                                    </div>
                            <div class="main-content-label mg-b-5">
                                <div style="float:left; height:2.5em; text-align: left;margin-bottom: 5px;width:50%">
                                    <?php echo esc_html( __( 'Stock Review by Product', QA_MAIN_DOMAIN ) ); ?>
                                </div>
                                <div style="float:right;height:2.5em; text-align: right;margin-bottom: 5px;width:50%; position: relative; right: 80px;">
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
                            <div class="row" style="width: 100% !important">
                                <div class="col-md-12 col">
                                    <div id="table_2" style="width: 100% !important"></div>
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
                                                    title: "Supplier",
                                                    field: "supplier_name",
                                                    hozAlign: "left",
                                                    headerFilter: "input",
                                                    visible: false
                                                },
                                                {
                                                    title: "Backorders",
                                                    field: "backorders",
                                                    hozAlign: "left",
                                                    headerFilter: "input",
                                                    visible: false
                                                },
                                                {
                                                    title: "<?php echo __( 'Stock Value', QA_MAIN_DOMAIN );?>",
                                                    field: "stock_value",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
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
                                                    title: "<?php echo __( 'Sales L26W', QA_MAIN_DOMAIN );?>",
                                                    // field: "order_value_cost",
                                                    field: "sales_l26w",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
                                                },
                                                {
                                                    title: "<?php echo __( 'Sales L8W', QA_MAIN_DOMAIN );?>",
                                                    // field: "order_value_cost",
                                                    field: "sales_l8w",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
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
                                                    title: "<?php echo __( 'Forecast N8W', QA_MAIN_DOMAIN );?>",
                                                    // field: "order_value_retail",
                                                    field: "sales_n8w",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
                                                },
                                                {
                                                    title: "<?php echo __( 'Forecast N26W', QA_MAIN_DOMAIN );?>",
                                                    // field: "order_value_retail",
                                                    field: "sales_n26w",
                                                    hozAlign: "center",
                                                    sorter: "number",
                                                    headerFilter: minMaxFilterEditor,
                                                    headerFilterFunc: minMaxFilterFunction,
                                                    headerFilterLiveFilter: false,
                                                    visible: false
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
                        <a href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_po_create_po')); ?>" class="create-btn" style="margin-left: 0; margin-top: 30px;">
                          Create PO
                        </a>
                    </div>
                </div>
                <?php include __DIR__ . '/../' . "popups.php"; ?>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../' . 'footer.php';