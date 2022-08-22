<?php
global $wpdb;

if ( ! empty( $_POST ) && isset( $_POST['po_do_save'] ) ) {
	header( "Location: ".get_admin_url()."admin.php?page=shelf_planner_po_orders" );
	exit;
}
if ( ! empty( $_POST ) && isset( $_POST['po_do_cancel'] ) ) {
	if ( isset( $_POST['order_id'] ) && is_numeric( $_POST['order_id'] ) && $_POST['order_id'] > 0 ) {
		$tmp_id = (int) $_POST['order_id'];
		$wpdb->query( "DELETE FROM `{$wpdb->purchase_orders}` WHERE `id` = {$tmp_id}" );
	}

	header( "Location: ".get_admin_url()."admin.php?page=shelf_planner_po_orders" );
	exit;
}

$opt_products  = array();
$supplier_name = isset( $_GET['supplier'] ) ? sanitize_text_field( trim( $_GET['supplier'] ) ) : '';
if ( strlen( $supplier_name ) > 0 ) {
	$opt_products['sup.`supplier_name`'] = $supplier_name;
}
$js_show_po_checkbox = isset( $supplier_name ) ? 'true' : 'false';

$warehouses = QAMain_Core::get_warehouses();

if ( ! $warehouses ) {
	$warehouses = [
		[
			'id'             => 1,
			'warehouse_name' => 'Default Warehouse',
		],
	];
}

$pre_pdf = false;

if ( ! empty( $_POST ) && isset( $_POST['do-create-purchase-order'] ) ) {
	$pre_pdf = true;

	$po_data_json = $po_data_products = array();
	foreach ( $_POST['product-qtys'] as $product_id => $qty ) {
		if ( isset( $_POST['product-prices'][ $product_id ] ) ) {
			$price        = (float) $_POST['product-prices'][ $product_id ];
			$product_name = sanitize_title( $_POST['product-names'][ $product_id ] );

			if ( is_numeric( $qty ) && is_numeric( $price ) && is_numeric( $product_id ) && $qty > 0 && $price > 0 && $product_id > 0 ) {
				$po_data_json[ $product_id ] = array(
					'qty'   => (int) $qty,
					'price' => sp_get_price( (float) $price ),
					'name'  => stripslashes( html_entity_decode( $product_name ) ),
				);
				$po_data_products[]          = array(
					'order_id'   => null,
					'product_id' => $product_id,
					'qty'        => $po_data_json[ $product_id ]['qty'],
					'price'      => $po_data_json[ $product_id ]['price']
				);
			}
		}
	}

	if(isset($_POST['do-create-purchase-order'])){
		unset( $_POST['do-create-purchase-order'] );
    }
	if(isset($_POST['product-qtys'])){
		unset( $_POST['product-qtys'] );
    }
	if(isset($_POST['product-prices'])){
		unset( $_POST['product-prices'] );
    }
	if(isset($_POST['product-names'])){
		unset( $_POST['product-names'] );
    }

	$po_data_post_2 = array();
	$po_data_post_2['supplier_id'] = isset( $_POST['purchase_order_num'] ) ? (int) $_POST['purchase_order_num'] : '';
	$po_data_post_2['warehouse_id'] = isset( $_POST['purchase_order_num'] ) ? (int) $_POST['warehouse_id'] : '';
	$po_data_post_2['purchase_order_num'] = isset( $_POST['purchase_order_num'] ) ? sanitize_title($_POST['purchase_order_num']) : '';
	$po_data_post_2['order_prefix'] = isset( $_POST['order_prefix'] ) ? sanitize_title($_POST['order_prefix']) : '';
	$po_data_post_2['order_number'] = isset( $_POST['order_number'] ) ? sanitize_title($_POST['order_number']) : '';
	$po_data_post_2['purchase_order_num'] = isset( $_POST['purchase_order_num'] ) ? sanitize_title($_POST['purchase_order_num']) : '';
	$po_data_post_2['payment_terms'] = isset( $_POST['payment_terms'] ) ? sanitize_title($_POST['payment_terms']) : '';
	$po_data_post_2['delivery_terms'] = isset( $_POST['delivery_terms'] ) ? sanitize_title($_POST['delivery_terms']) : '';
	$po_data_post_2['vendor_no'] = isset( $_POST['vendor_no'] ) ? sanitize_title($_POST['vendor_no']) : '';
	$po_data_post_2['vendor_vat'] = isset( $_POST['vendor_vat'] ) ? sanitize_title($_POST['vendor_vat']) : '';
	$po_data_post_2['account_no'] = isset( $_POST['account_no'] ) ? sanitize_title($_POST['account_no']) : '';
	$po_data_post_2['reference_number'] = isset( $_POST['reference_number'] ) ? sanitize_title($_POST['reference_number']) : '';
	$po_data_post_2['order_date'] = isset( $_POST['order_date'] ) ? sanitize_title($_POST['order_date']) : '';
	$po_data_post_2['expected_delivery_date'] = isset( $_POST['expected_delivery_date'] ) ? sanitize_title($_POST['expected_delivery_date']) : '';
	$po_data_post_2['description'] = isset( $_POST['description'] ) ? sanitize_title($_POST['description']) : '';
	$po_data_post_2['account_id'] = isset( $_POST['account_id'] ) ? sanitize_title($_POST['account_id']) : '';
	$po_data_post_2['assigned_to'] = isset( $_POST['assigned_to'] ) ? sanitize_title($_POST['assigned_to']) : '';
	$po_data_post_2['deliver_to'] = isset( $_POST['deliver_to'] ) ? sanitize_title($_POST['deliver_to']) : '';
	$po_data_post_2['supplier_name'] = isset( $_POST['supplier_name'] ) ? sanitize_title($_POST['supplier_name']) : '';
	$po_data_post_2['supplier_address'] = isset( $_POST['supplier_address'] ) ? sanitize_title($_POST['supplier_address']) : '';

	$po_data_session = $po_data_post_2;
	$po_data_session['product_data'] = json_encode( $po_data_json );

	list( $po_num_prefix, $po_num_number ) = explode( '-', $po_data_post_2['purchase_order_num'], 2 );
	$po_data_post_2['order_prefix'] = $po_num_prefix;

	if ( 'auto' == get_option( 'sp.settings.po_auto-generate_orders', 'auto' ) ) {
		$po_data_post_2['order_number'] = sp_get_next_po( $po_num_number );
	} else {
		$po_data_post_2['order_number'] = str_pad( strval( (int) $po_num_number ), 8, '0', STR_PAD_LEFT );
	}
	update_option( 'sp.settings.po_next_number', $po_data_post_2['order_number'] );
	update_option( 'sp.last_reference_number', sp_get_next_rn() );

	unset( $po_data_post_2['purchase_order_num'], $po_data_post_2['payment_terms'], $po_data_post_2['delivery_terms'],
        $po_data_post_2['vendor_no'], $po_data_post_2['vendor_vat'], $po_data_post_2['account_no'], $po_data_post_2['account_id'], $po_data_post_2['assigned_to'], $po_data_post_2['supplier_name'], $po_data_post_2['supplier_address'] );

	$wpdb->insert( $wpdb->purchase_orders, $po_data_post_2 );
	$order_id = $wpdb->insert_id;

	if ( $order_id ) {
		foreach ( $po_data_products as $each_po_product ) {
			$each_po_product['order_id'] = $order_id;
			$wpdb->insert( $wpdb->purchase_orders_products, $each_po_product );
		}
	}
}

$show_form = false;

if ( ! empty( $_POST ) && isset( $_POST['po-data'] ) ) {
	$show_form         = true;
	$po_data_post      = json_decode( stripslashes( $_POST['po-data'] ), true );
	$selected_products = array_keys( $po_data_post );
	// Sanitize
	foreach ( $selected_products as &$selected_product ) {
		$selected_product = (int) $selected_product;
	}
}

if ( isset( $selected_products ) ) {
	$opt_products['p.`ID`'] = $selected_products;
}
$products_data = sp_get_products_data( implode( ',', array_keys( sp_get_categories() ) ), $opt_products );

if ( $show_form ) {
	// Sanitize
	$form_lead_time   = (int) get_option( 'sp.settings.default_lead_time', 1 );
	$form_p_lead_time = $form_s_lead_time = false;

	$po_data = $js_po_data = array();
	foreach ( $products_data as $each_row ) {
		$post_id = $each_row['term_id'];
		if ( isset( $po_data_post[ $post_id ] ) ) {
			$po_data[ $post_id ]        = $each_row;
			$po_data[ $post_id ]['qty'] = (int) $js_po_data[ $post_id ] = $po_data_post[ $post_id ];

			if ( $each_row['supplier_lead_time'] > 0 ) {
				if ( false === $form_s_lead_time || $each_row['supplier_lead_time'] > $form_s_lead_time ) {
					$form_s_lead_time = $each_row['supplier_lead_time'];
				}
			}

			if ( $each_row['product_lead_time'] > 0 ) {
				if ( false === $form_p_lead_time || $each_row['product_lead_time'] > $form_p_lead_time ) {
					$form_p_lead_time = $each_row['product_lead_time'];
				}
			}
		}
	}

	$form_lead_time = strtotime( "NOW + {$form_lead_time} WEEK" . ( $form_lead_time > 1 ? 'S' : '' ) );
	if ( false !== $form_s_lead_time ) {
		$form_lead_time = strtotime( "NOW + {$form_s_lead_time} WEEK" . ( $form_s_lead_time > 1 ? 'S' : '' ) );
	}
	if ( false !== $form_p_lead_time ) {
		$form_lead_time = strtotime( "NOW + {$form_p_lead_time} WEEK" . ( $form_p_lead_time > 1 ? 'S' : '' ) );
	}
}

require_once __DIR__ . '/../' . 'header.php'; ?>
    <div class="sp-admin-overlay">
        <div class="sp-admin-container">
			<?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
            <!-- main-content opened -->
            <div class="main-content horizontal-content">
                <div class="page">
                    <!-- container opened -->
                    <div class="container">
	                    <?php include SP_PLUGIN_DIR_PATH ."pages/header_js.php"; ?>
						<?php
						if ( $pre_pdf ) {
							sp_session_start();
							$_SESSION['order_info']              = $po_data_session;
							$_SESSION['order_info']['order_id']  = $order_id;
							$_SESSION['order_info']['order_num'] = $po_data_post_2['order_number'];


							list($html, $order_info) = sp_make_order_pdf();
                            ?>
                            <style>
                                #wpfooter { display: none }
                                @media print {
                                    .card {
                                        background-color: white;
                                        height: 100%;
                                        width: 100%;
                                        position: fixed;
                                        top: 0;
                                        left: 0;
                                        margin: 0;
                                        padding: 15px;
                                    }

                                    #po-final-stage, .print-hide {
                                        display: none !important;
                                    }
                                }
                                .po-template table,
                                .po-template div {
                                    font-family: 'Courier New' !important;
                                }
                            </style>
                        <?php
							echo wp_kses_post($html);
							?>
                            <div style="padding: 50px">
                                <form action="" method="post" id="po-final-stage">
                                    <input type="hidden" name="order_id" value="<?php echo esc_html( $order_info['order_id'] ) ?>" >
                                    <input type="hidden" name="order_num" value="<?php echo esc_html( $order_info['order_num'] ) ?>" >
                                    <input type="submit" class="btn btn-success" name="po_do_save" value="Back to Orders">
                                    <button class="btn btn-sm btn-info" onclick="window.print(); return false;"> <?php echo esc_html(__( 'Print', QA_MAIN_DOMAIN )); ?> </button>
                                    <input type="submit" class="btn btn-sm btn-info" name="po_do_cancel" value="Delete">
                                    <script>jQuery('#sp-po-logo').attr('src','data:'+jQuery('#sp-po-logo').attr('src'));</script>
                                </form>
                            </div>
                        </div></div>
                        <?php
						} else {
						?>
                        <h2><?php echo esc_html(__( 'Purchase Orders', QA_MAIN_DOMAIN )); ?></h2>
                        <?php do_action( 'after_page_header' ); ?>
                        <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/po/tabs.php" ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5" style="margin-left: -15px;">
									<?php echo esc_html(__( 'Create Purchase Order', QA_MAIN_DOMAIN )); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div>
                                        <p><?php echo esc_html(__( 'Please select supplier to create Purchase Order with multiple items.', QA_MAIN_DOMAIN )); ?></p>
                                        <select id="id-po-search-input" style="max-height: 31px;">
											<?php foreach ( QAMain_Core::get_suppliers() as $tmp_supplier ) { ?>
                                                <option value="<?php echo  esc_attr( $tmp_supplier['supplier_name'] ); ?>" <?php echo esc_attr( ( isset( $_GET['supplier'] ) && sanitize_text_field($_GET['supplier']) == $tmp_supplier['supplier_name'] ) ? 'selected' : '' ); ?>><?php echo  esc_html( $tmp_supplier['supplier_name'] ); ?></option>
											<?php } ?>
                                        </select>
                                        <button class="btn btn-sm btn-info" style="margin-top: 1px;max-height: 30px;" onclick="search_supplier(); return false"><?php echo esc_html(__( 'Search', QA_MAIN_DOMAIN )); ?></button>
                                    </div>
                                    <p class="mg-b-20"></p>
                                    <div class="row">
                                        <div class="col-md-12 col">
                                            <p class="mg-b-20"></p><br/>
                                            <div id="table_2" style="width: 1050px"></div>
                                            <div style="margin-top: 24px">
												<?php if ( ! $show_form ): ?>
                                                    <form id="id-create-po-form" method="post">
                                                        <input type="hidden" name="po-data" id="id-po-data" value=""> <input type="button" onclick="create_po(); return false;" class="btn btn-success" value="<?php echo esc_html(__( 'Create PO', QA_MAIN_DOMAIN )); ?>"/>
                                                    </form>
												<?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<?php if ( $show_form ): ?>
                                    <form style="max-width: 900px" method="post">
										<?php
										foreach ( $po_data as $product_id => $each_item ): ?>
                                            <input type="hidden" name="product-qtys[<?php echo esc_attr((int) $product_id); ?>]" value="<?php echo esc_attr((int) $each_item['qty']); ?>"><input type="hidden" name="product-prices[<?php echo esc_attr((int) $product_id); ?>]" value="<?php echo  esc_attr( $each_item['cost_price'] ) ?>"><input type="hidden" name="product-names[<?php echo esc_attr((int) $product_id); ?>]" value="<?php echo  esc_attr( $each_item['name'] ) ?>">
										<?php endforeach ?>
                                        <input type="hidden" name="supplier_id" value="<?php echo esc_attr((int) $each_item['supplier_id']); ?>"> <input type="hidden" name="supplier_name" value="<?php echo  esc_attr( $each_item['supplier_name'] ) ?>"> <input type="hidden" name="supplier_address" value="<?php echo  esc_attr( $each_item['supplier_address'] ) ?>"> <input type="hidden" name="payment_terms" value="<?php echo  esc_attr( $each_item['payment_terms'] ) ?>"> <input type="hidden" name="delivery_terms" value="<?php echo  esc_attr( $each_item['delivery_terms'] ) ?>"> <input type="hidden" name="vendor_no" value="<?php echo  esc_attr( $each_item['vendor_no'] ) ?>"> <input type="hidden" name="vendor_vat" value="<?php echo  esc_attr( $each_item['vendor_vat'] ) ?>"> <input type="hidden" name="account_no" value="<?php echo  esc_attr( $each_item['account_no'] ) ?>"> <input type="hidden" name="account_id" value="<?php echo  esc_attr( $each_item['account_id'] ) ?>"> <input type="hidden" name="assigned_to" value="<?php echo  esc_attr( $each_item['assigned_to'] ) ?>">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label><?php echo esc_html(__( 'Deliver To:', QA_MAIN_DOMAIN )); ?></label>&nbsp;&nbsp;&nbsp;
                                            </div>
                                            <div class="col-md-2">
                                                <label for="r1"><?php echo esc_html(__( 'Warehouse', QA_MAIN_DOMAIN )); ?></label> <input type="radio" name="deliver_to" id="r1" value="warehouse" class="form-control" checked/>
                                            </div>
                                            <div class="col-md-2">
                                                <label disabled="disabled" for="r2"><?php echo esc_html(__( 'Customer', QA_MAIN_DOMAIN )); ?></label> <input disabled="disabled" type="radio" name="deliver_to" id="r2" value="customer" class="form-control"/>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="warehouse_id" class="form-control">
													<?php foreach ( $warehouses as $warehouse ) { ?>
                                                        <option value="<?php echo esc_attr((int) $warehouse['id']); ?>"><?php echo  esc_html( $warehouse['warehouse_name'] ) ?></option>
													<?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label><?php echo esc_html(__( 'Purchase Order #', QA_MAIN_DOMAIN )); ?></label> <input required="required" type="text" class="form-control" placeholder="Purchase Order #" name="purchase_order_num" value="<?php echo  esc_attr( get_option( 'sp.settings.po_prefix', 'PO-' ) ) ?><?php echo esc_html(sp_get_next_po()); ?>"/>
                                            </div>
                                            <div class="col-md-3">
                                                <label><?php echo esc_html(__( 'Reference Number #', QA_MAIN_DOMAIN )); ?></label> <input required="required" type="text" class="form-control" name="reference_number" readonly="readonly" placeholder="Reference Number #" value="RN-<?php echo  esc_attr( sp_get_next_rn() ) ?>"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label><?php echo esc_html(__( 'Order Date', QA_MAIN_DOMAIN )); ?>
                                                </label> <input required="required" type="date" class="form-control" name="order_date" value="<?php echo esc_attr(date( 'Y-m-d' )); ?>"/>
                                            </div>
                                            <div class="col-md-3">
                                                <label><?php echo esc_html(__( 'Expected Delivery Date', QA_MAIN_DOMAIN )); ?></label> <input required="required" type="date" class="form-control" name="expected_delivery_date" value="<?php echo esc_attr(date( 'Y-m-d', $form_lead_time )); ?>"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label><?php echo  __( 'Description', QA_MAIN_DOMAIN ); ?>
                                                </label> <textarea name="description" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 24px">
                                            <div class="col-md-3">
                                                <input type="submit" class="btn btn-success" name="do-create-purchase-order" value="<?php echo esc_attr(__( 'Create Purchase Order', QA_MAIN_DOMAIN )); ?>"/>
                                            </div>
                                        </div>
                                    </form>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
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

		<?php if ($show_form): ?>
        const qtydata2 = <?php echo json_encode( $js_po_data )?>;
		<?php endif;?>
        const tabledata2 = <?php echo json_encode( array_values( $products_data ) )?>;
        const po_checkbox_show = <?php echo  esc_js( $js_show_po_checkbox ) ?>;

        let table2 = new Tabulator("#table_2", {
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "<?php echo __( 'No products associated with selected supplier or no Unit Cost Price is set for products', QA_MAIN_DOMAIN ); ?>",
            data: tabledata2,
            pagination: "local",
            paginationSize: 50,
            paginationSizeSelector: [50, 100, 500],
            initialSort: [{column: "ideal_stock", dir: "desc"}],
            columns: [
				<?php if ($js_show_po_checkbox === 'true'): ?>
                {
                    title: "PO",
                    field: "product_checked",
                    hozAlign: "center",
                    vertAlign: "middle",
                    sorter: false,
                    width: 30,
                    formatter: function (cell, formatterParams, onRendered) {
                        return po_checkbox_show
                            ? '<input type="checkbox" class="select-row" style="vertical-align: middle" <?php echo esc_html($show_form ? 'checked="checked" disabled="disabled" ' : ''); ?>'
                            + 'onchange="product_selected(this,' + cell.getData().term_id
                            + '); return false" >' : '';
                    },
                },
				<?php endif;?>
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
                    title: "<?php echo __( 'Supplier Name', QA_MAIN_DOMAIN );?>",
                    field: "supplier_name",
                    headerFilter: "input",
                    formatter: function (cell, formatterParams, onRendered) {
                        const supplier_name = cell.getData()['supplier_name']
                        return supplier_name ? '<a href="#" ' +
                            'onclick="jQuery(\'#id-po-search-input\').val(\''
                            + supplier_name + '\'); return false;">'
                            + supplier_name + '</a>' : '';
                    },
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
				<?php if ($js_show_po_checkbox === 'true'): ?>
                {
                    title: "<?php echo __( 'Order QTY', QA_MAIN_DOMAIN );?>",
                    field: "order_qty",
                    hozAlign: "center",
                    sorter: false,
                    width: 80,
                    formatter: function (cell, formatterParams, onRendered) {

                        if (!po_checkbox_show) {
                            return '';
                        }
                        let qty = 0;
						<?php if ($show_form): ?>
                        qty = qtydata2[cell.getData().term_id];
						<?php else: ?>
                        qty = cell.getData()['order_proposal_units'];
						<?php endif;?>
                        if (!qty) {
                            qty = 0;
                        }
                        return <?php if ($show_form): ?>qty<?php else: ?>
                        '<input type="number" '
                        + 'id="id-po-qty-' + cell.getData().term_id
                        + '" value="' + qty + '" '
                        + 'size="3" style="width:100%;padding:0px;padding-left:20%;'
                        + 'text-align:center" min="0" onChange="update_po_price(this,'
                        + cell.getData().term_id + ')" />'<?php endif ?>;
                    },
                },
                {
                    title: "<?php echo __( 'Cost Price', QA_MAIN_DOMAIN );?>",
                    field: "cost_price",
                    hozAlign: "center",
                    sorter: "number",
                    headerFilter: minMaxFilterEditor,
                    headerFilterFunc: minMaxFilterFunction,
                    headerFilterLiveFilter: false
                },
				<?php endif;?>
                {
                    title: "<?php echo __( 'Order Value @ Cost', QA_MAIN_DOMAIN );?>",
                    field: "order_value_cost",
                    hozAlign: "center",
                    sorter: "number",
                    headerFilter: minMaxFilterEditor,
                    headerFilterFunc: minMaxFilterFunction,
                    headerFilterLiveFilter: false,
                    formatter: function (cell, formatterParams, onRendered) {
                        let qty = 0;
						<?php if ($show_form): ?>
                        qty = qtydata2[cell.getData().term_id];
						<?php else: ?>
                        qty = cell.getData()['order_proposal_units'];
						<?php endif;?>

                        let price = (qty > 0 ? qty : 1) * cell.getData()['order_value_cost'];
                        price = Math.round((price + Number.EPSILON) * 100) / 100;

                        return '<input type="hidden" id="id-po-value_cost_one-'
                            + cell.getData().term_id + '" value="'
                            + cell.getData()['order_value_cost'] + '">'
                            + '<span id="id-po-value_cost-' + cell.getData().term_id
                            + '">' + price.toFixed(2) + '</span>';
                    },
                },
                {
                    title: "<?php echo __( 'Order Value @ Retail', QA_MAIN_DOMAIN );?>",
                    field: "order_value_retail",
                    hozAlign: "center",
                    sorter: "number",
                    headerFilter: minMaxFilterEditor,
                    headerFilterFunc: minMaxFilterFunction,
                    headerFilterLiveFilter: false,
                    formatter: function (cell, formatterParams, onRendered) {
                        let qty = 0;
						<?php if ($show_form): ?>
                        qty = qtydata2[cell.getData().term_id];
						<?php else: ?>
                        qty = cell.getData()['order_proposal_units'];
						<?php endif;?>

                        let price = (qty > 0 ? qty : 1) * cell.getData()['order_value_retail'];
                        price = Math.round((price + Number.EPSILON) * 100) / 100;

                        return '<input type="hidden" id="id-po-value_retail_one-'
                            + cell.getData().term_id + '" value="'
                            + cell.getData()['order_value_retail'] + '">'
                            + '<span id="id-po-value_retail-' + cell.getData().term_id
                            + '">' + price.toFixed(2) + '</span>';
                    },
                },
            ],
        });

        let po_qty = {};

        function product_selected(e, product_id) {
            if (e.checked) {
                const qty = jQuery('#id-po-qty-' + product_id);
                let val = qty.val();
                if (val == 0) {
                    qty.val(val = 1)
                }
                po_qty[product_id] = val;
            } else {
                delete po_qty[product_id];
            }
        }

        function create_po() {
            let arr_ids = [];
            let val = '';

            for (let i in po_qty) {
                if (po_qty[i] > 0) {
                    //'{"1":2,"3":4}'
                    arr_ids.push('"' + i + '"' + ':' + po_qty[i]);
                }
            }

            if (arr_ids.length > 0) {
                val = '{' + arr_ids.join(',') + '}';
                jQuery('#id-po-data').val(val);
                jQuery('#id-create-po-form').submit();
            } else {
                alert('Please select at least one product with quantity specified'); // TODO: translate
            }
        }

        function search_supplier() {
            const q = jQuery('#id-po-search-input').val();
            let href = '<?php echo esc_url(get_admin_url() . 'admin.php?page=shelf_planner_po_create_po' ); ?>';
            if (q) {
                href = href + '&supplier=' + q;
            }
            window.location.href = href;
        }

        function update_po_price(e, id) {
            const qty = parseInt(e.value);
            if (qty > 0) {
                po_qty[id] = qty;
            } else {
                delete po_qty[id];
            }
            let num = qty * jQuery('#id-po-value_cost_one-' + id).val();
            jQuery('#id-po-value_cost-' + id).html(!num ? '0'
                : parseFloat(num).toFixed(2));
            num = qty * jQuery('#id-po-value_retail_one-' + id).val();
            jQuery('#id-po-value_retail-' + id).html(!num ? '0'
                : parseFloat(num).toFixed(2));
        }
    </script>
<?php } ?>