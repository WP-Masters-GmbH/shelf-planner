<?php
global $wpdb;

if ( isset( $_GET['status'] ) && in_array( $_GET['status'], sp_get_order_statuses() ) && isset( $_GET['id'] ) ) {
	foreach ( explode( ',', sanitize_text_field( $_GET['id'] ) ) as $id ) {
		// Sanitize
	    $order_id = (int) $id;
		if ( $order_id <= 0 ) {
			continue;
		}
		$sql = "SELECT `product_id`, `qty`
            FROM {$wpdb->purchase_orders_products}
            WHERE `order_id` = {$order_id}";

		$product_info_db = $wpdb->get_results( $sql, ARRAY_A );
		foreach ( $product_info_db as $each_product_info ) {
			try {
				$product = new WC_Product( $each_product_info['product_id'] );
			} catch ( Exception $e ) {
				$product = new WC_Product_Variation( $each_product_info['product_id'] );
			}
			if($_GET['status'] == 'Completed') {
				$stock_quantity = (int) $product->get_stock_quantity() + $each_product_info['qty'];
				$product->set_stock_quantity( $stock_quantity );
				$product->set_stock_status( $stock_quantity > 0 ? 'instock' : 'outofstock' );
				$product->save();
            }
		}

		$wpdb->update( $wpdb->purchase_orders, array( 'status' => sanitize_text_field( $_GET['status'] ) ), array( 'id' => $id ) );
	}

    header( "Location: ".get_admin_url()."admin.php?page=shelf_planner_po_orders" );
	exit;
}

require_once __DIR__ . '/admin_page_header.php';

$sql = "
SELECT o.*,
    CONCAT(o.`order_prefix`, '-', o.`order_number`) as purchase_order_num,
    s.`supplier_name`, pop.`qty`, pop.`price`, p.`post_title` AS product_name 
FROM `{$wpdb->purchase_orders}` o
LEFT JOIN `{$wpdb->suppliers}` s ON s.`id` = o.`supplier_id`
LEFT JOIN `{$wpdb->purchase_orders_products}` pop ON pop.`order_id` = o.`id`
LEFT JOIN `{$wpdb->prefix}posts` p ON p.`ID` = pop.`product_id`
";

$purchase_orders_db = $wpdb->get_results( $sql, ARRAY_A );
$statuses           = sp_get_order_statuses( true );

$purchase_orders = array();
foreach ( $purchase_orders_db as $purchase_order ) {
	if ( ! isset( $purchase_orders[ $purchase_order['id'] ] ) ) {
		$purchase_orders[ $purchase_order['id'] ] = array(
			'id'                     => $purchase_order['id'],
			'po'                     => stripslashes( $purchase_order['purchase_order_num'] ),
			'dt'                     => $purchase_order['order_date'],
			'status'                 => $purchase_order['status'],
			'supplier'               => stripslashes( $purchase_order['supplier_name'] ),
			'ship_to'                => $purchase_order['shipping_address'],
			'expected_delivery_days' => sp_days_left( $purchase_order['expected_delivery_date'] ),

			'p_name'      => array( stripslashes( $purchase_order['product_name'] ) ),
			'order_value' => $purchase_order['qty'] * $purchase_order['price'],
			'quantity'    => $purchase_order['qty'],
		);

		$statuses[ $purchase_order['status'] ] ++;
	} else {
		$purchase_orders[ $purchase_order['id'] ]['p_name'][]    = stripslashes( $purchase_order['product_name'] );
		$purchase_orders[ $purchase_order['id'] ]['order_value'] += ( $purchase_order['qty'] * $purchase_order['price'] );
		$purchase_orders[ $purchase_order['id'] ]['quantity']    += $purchase_order['qty'];
	}
}

foreach ( $purchase_orders as &$purchase_order ) {
	$purchase_order['p_name']      = '[' . count( $purchase_order['p_name'] ) . '] ' . implode( ', ', $purchase_order['p_name'] );
	$purchase_order['order_value'] = sp_get_price( $purchase_order['order_value'] );
}

$total_po_count = count( $purchase_orders );
$json_orders    = json_encode( array_values( $purchase_orders ) );

?><?php require_once __DIR__ . '/../' . 'header.php'; ?>
    <div class="sp-admin-overlay">
        <div class="sp-admin-container">
			<?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
            <!-- main-content opened -->
            <div class="main-content horizontal-content">
                <div class="page">
                    <!-- container opened -->
                    <div class="container">
                        <h2><?php echo esc_html(__( 'Purchase Orders', QA_MAIN_DOMAIN )); ?></h2>
                        <?php do_action( 'after_page_header' ); ?>
                        <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/po/tabs.php" ?>
	                    <?php include SP_PLUGIN_DIR_PATH ."pages/header_js.php"; ?>
                        <script>
                            function productSelected() {
                                var obj = jQuery('[name="product_name"] option:selected');
                                if (jQuery(obj).is('[data-id]')) {
                                    jQuery('[name="product_id"]').val(jQuery(obj).data('id'));
                                    jQuery('[name="variation_name"]').val(jQuery(obj).data('variation-name'));
                                    jQuery('[name="variation_id"]').val(jQuery(obj).data('variation-id'));
                                    jQuery('[name="unit_cost_price"]').val(jQuery(obj).data('qacogcost'));
                                    jQuery('[name="supplier"]').val(jQuery(obj).data('qacogsupplierid'));
                                    calcTotal();
                                }
                            }

                            function productSelectedByPID(pid) {
                                var tmp_val = jQuery('select option[data-id="' + pid + '"]').attr('value');
                                jQuery('[name="product_name"]').val(tmp_val).trigger('change');
                            }

                            function calcTotal() {
                                var res = parseFloat(jQuery('[name="quantity"]').val()) * parseFloat(jQuery('[name="unit_cost_price"]').val());
                                if (res >= 0) {
                                    jQuery('[name="order_value"]').val(res);
                                } else {
                                    jQuery('[name="order_value"]').val('');
                                }

                            }
                        </script>
                        <div class="card">
                            <div class="card-body">
                                <div class="main-content-label mg-b-5">
									<?php echo esc_html(__( 'Orders History', QA_MAIN_DOMAIN )); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <nav class="nav nav-pills" style="margin-bottom: 1em">
                                            <a class="nav-link active" id="group-0" href="#" onclick="return setOrders(0)"><?php echo esc_html(__( 'All', QA_MAIN_DOMAIN )); ?>
                                                (<?php echo  esc_html( $total_po_count ); ?>)</a> <a class="nav-link " id="group-1" href="#" onclick="return setOrders(<?php echo esc_js( 1 || $statuses['On Order'] > 0 ? 1 : 'false' ); ?>, 'On Order')"><?php echo esc_html(__( 'On Order', QA_MAIN_DOMAIN )); ?>
                                                (<?php echo  esc_html( $statuses['On Order'] ); ?>) </a> <a class="nav-link " id="group-2" href="#" onclick="return setOrders(<?php echo esc_js( 1 || $statuses['On Hold'] > 0 ? 2 : 'false' ); ?>, 'On Hold')"><?php echo  esc_html(__( 'On Hold', QA_MAIN_DOMAIN )); ?>
                                                (<?php echo  esc_html( $statuses['On Hold'] ); ?>) </a> <a class="nav-link " id="group-3" href="#" onclick="return setOrders(<?php echo esc_js( 1 || $statuses['Completed'] > 0 ? 3 : 'false' ); ?>, 'Completed')"><?php echo esc_html(__( 'Completed', QA_MAIN_DOMAIN )); ?>
                                                (<?php echo  esc_html( $statuses['Completed'] ); ?>)</a> <a class="nav-link " id="group-4" href="#" onclick="return setOrders(<?php echo esc_js( 1 || $statuses['Cancelled'] > 0 ? 4 : 'false' ); ?>, 'Cancelled')"><?php echo esc_html(__( 'Cancelled', QA_MAIN_DOMAIN )); ?>
                                                (<?php echo  esc_html( $statuses['Cancelled'] ); ?>)</a> <a class="nav-link " id="group-5" href="#" onclick="return setOrders(<?php echo esc_js( 1 || $statuses['Failed'] > 0 ? 5 : 'false' ); ?>, 'Failed')"><?php echo esc_html(__( 'Failed', QA_MAIN_DOMAIN )); ?>
                                                (<?php echo  esc_html( $statuses['Failed'] ); ?>)</a>
                                        </nav>
                                        <div id="table-data"></div>
                                        <script>
                                            let table_data = <?php echo $json_orders;?>;

                                            //Build Tabulator
                                            // region tabulator columns
                                            let columns = [
                                                {
                                                    formatter: "rowSelection",
                                                    titleFormatter: "rowSelection",
                                                    hozAlign: "left",
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
                                                    title: "<?php echo __( 'Purchase Order', QA_MAIN_DOMAIN );?>",
                                                    field: "po",
                                                    width: 150
                                                },
                                                {
                                                    title: "<?php echo __( 'Created', QA_MAIN_DOMAIN );?>",
                                                    field: "dt",
                                                    hozAlign: "left",
                                                    sorter: "date",
                                                    formatter: "datetime",
                                                    formatterParams: {
                                                        inputFormat: "YYYY-MM-DD H:m:s",
                                                        outputFormat: "LL",
                                                        invalidPlaceholder: "(invalid date)",
                                                    }
                                                },
                                                {
                                                    title: "<?php echo __( 'Status', QA_MAIN_DOMAIN );?>",
                                                    field: "status",
                                                    width: 100
                                                },
                                                {
                                                    title: "<?php echo __( 'Supplier', QA_MAIN_DOMAIN );?>",
                                                    field: "supplier",
                                                    hozAlign: "left",
                                                    width: 100
                                                },
                                                {
                                                    title: "<?php echo __( 'Product Name', QA_MAIN_DOMAIN );?>",
                                                    field: "p_name",
                                                    hozAlign: "left",
                                                    width: 100
                                                },
                                                {
                                                    title: "<?php echo __( 'Quantity', QA_MAIN_DOMAIN );?>",
                                                    field: "quantity"
                                                },
                                                {
                                                    title: "<?php echo esc_html(__( 'Order Value', QA_MAIN_DOMAIN ));?>", 
                                                    field: "order_value"
                                                },
                                                {
                                                    title: "<?php echo __( 'Ship To', QA_MAIN_DOMAIN );?>",
                                                    field: "ship_to",
                                                    hozAlign: "center",
                                                    sorter: "date"
                                                },
                                                {
                                                    title: "<?php echo __( 'Days, Expected Delivery', QA_MAIN_DOMAIN );?>",
                                                    field: "expected_delivery_days",
                                                    hozAlign: "center",
                                                    width: 100
                                                },
                                            ];
                                            //endregion

                                            let table = new Tabulator("#table-data", {
                                                layout: "fitDataTable",
                                                data: table_data,
                                                placeholder: "No Data",
                                                columns: columns,
                                                width: 800
                                            });

                                            let curr_status = '';

                                            function setOrders(curr, new_status = '') {
                                                if (curr === false) {
                                                    return false;
                                                }
                                                if (new_status.length === 0) {
                                                    table.removeFilter('status', '=', curr_status);
                                                } else {
                                                    table.setFilter([{
                                                        field: 'status',
                                                        type: '=',
                                                        value: new_status
                                                    }]);
                                                }
                                                curr_status = new_status;
                                                for (let i = 0; i < 6; i++) {
                                                    let element = jQuery('#group-' + i);
                                                    if (curr === i) {
                                                        element.addClass('active');
                                                    } else {
                                                        element.removeClass('active');
                                                    }
                                                }
                                                return false;
                                            }

                                            function bulkStatusUpdate() {
                                                window.rows_for_update = [];
                                                jQuery('.tabulator-selected [tabulator-field="id"]').each(function () {
                                                    window.rows_for_update.push(jQuery(this).text());
                                                });

                                                window.location = '<?php echo esc_url(get_admin_url()); ?>admin.php?page=shelf_planner_po_orders&status=' + jQuery('#bulk_status').val() + '&id=' + window.rows_for_update.join(',');
                                            }

                                            jQuery('[name="product_name"]').trigger('change');
                                        </script>
                                        <style>
                                            #table-data {
                                                max-width: 100%;
                                            }
                                        </style>
                                        <div>
                                            <select id="bulk_status" name="bulk_status">
                                                <option value="On Order"><?php echo esc_html(__( 'On Order', QA_MAIN_DOMAIN )); ?></option>
                                                <option value="On Hold"><?php echo esc_html(__( 'On Hold', QA_MAIN_DOMAIN )); ?></option>
                                                <option value="Completed"><?php echo esc_html(__( 'Completed', QA_MAIN_DOMAIN )); ?></option>
                                                <option value="Cancelled"><?php echo esc_html(__( 'Cancelled', QA_MAIN_DOMAIN )); ?></option>
                                                <option value="Failed"><?php echo esc_html(__( 'Failed', QA_MAIN_DOMAIN )); ?></option>
                                            </select> <input type="button" onclick="bulkStatusUpdate();" value="<?php echo esc_attr(__( 'Update Status', QA_MAIN_DOMAIN )); ?>" class="btn btn-sm btn-success"/>
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
<?php require_once __DIR__ . '/../' . 'footer.php';