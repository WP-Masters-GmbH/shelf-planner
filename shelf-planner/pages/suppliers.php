<?php

require_once __DIR__ . '/admin_page_header.php';

if ( $_POST ) {

	$data = array();
	$data['supplier_name'] = isset($_POST['supplier_name']) ? sanitize_text_field( $_POST['supplier_name'] ) : '';
	$data['supplier_code'] = isset($_POST['supplier_code']) ? sanitize_text_field( $_POST['supplier_code'] ) : '';
	$data['tax_vat_number'] = isset($_POST['tax_vat_number']) ? sanitize_text_field( $_POST['tax_vat_number'] ) : '';
	$data['phone_number'] = isset($_POST['phone_number']) ? sanitize_text_field( $_POST['phone_number'] ) : '';
	$data['website'] = isset($_POST['website']) ? sanitize_text_field( $_POST['website'] ) : '';
	$data['email_for_ordering'] = isset($_POST['email_for_ordering']) ? sanitize_email( $_POST['email_for_ordering'] ) : '';
	$data['general_email_address'] = isset($_POST['general_email_address']) ? sanitize_email( $_POST['general_email_address'] ) : '';
	$data['description'] = isset($_POST['description']) ? sanitize_text_field( $_POST['description'] ) : '';
	$data['currency'] = isset($_POST['currency']) ? sanitize_text_field( $_POST['currency'] ) : '';
	$data['address'] = isset($_POST['address']) ? sanitize_text_field( $_POST['address'] ) : '';
	$data['city'] = isset($_POST['city']) ? sanitize_text_field( $_POST['city'] ) : '';
	$data['country'] = isset($_POST['country']) ? sanitize_text_field( $_POST['country'] ) : '';
	$data['state'] = isset($_POST['state']) ? sanitize_text_field( $_POST['state'] ) : '';
	$data['account_no'] = isset($_POST['account_no']) ? sanitize_text_field( $_POST['account_no'] ) : '';
	$data['account_id'] = isset($_POST['account_id']) ? sanitize_text_field( $_POST['account_id'] ) : '';
	$data['assigned_to'] = isset($_POST['assigned_to']) ? sanitize_text_field( $_POST['assigned_to'] ) : '';
	$data['ship_to_location'] = isset($_POST['ship_to_location']) ? sanitize_text_field( $_POST['ship_to_location'] ) : '';
	$data['discount'] = isset($_POST['discount']) ? sanitize_text_field( $_POST['discount'] ) : '';
	$data['tax_rate'] = isset($_POST['tax_rate']) ? sanitize_text_field( $_POST['tax_rate'] ) : '';
	$data['lead_times'] = isset($_POST['lead_times']) ? sanitize_text_field( $_POST['lead_times'] ) : '';
	$data['payment_terms'] = isset($_POST['payment_terms']) ? sanitize_text_field( $_POST['payment_terms'] ) : '';
	$data['delivery_terms'] = isset($_POST['delivery_terms']) ? sanitize_text_field( $_POST['delivery_terms'] ) : '';

	$data['dt_added'] = current_time( 'mysql', 1 );

	if ( isset( $_GET['supplier_id'] ) ) {
		$result = $wpdb->update( $wpdb->suppliers, $data, [ 'id' => (int) $_GET['supplier_id'] ] );
		$msg    = 'Supplier was updated successfully';
	} else {
		$result = $wpdb->insert( $wpdb->suppliers, $data );
		$msg    = 'Supplier was added successfully';
	}
	if ( $result ) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php echo  esc_html( $msg ) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><?php
	} else { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo esc_html( __( 'Error occurred, please try again', QA_MAIN_DOMAIN ) ); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button></div><?php }
}

// TODO: replace using Methods class
$suppliers = [];
$admin_url = get_admin_url();
$tmp       = $wpdb->get_results( "
    select a.*,
        count(distinct po1.id) as orders,
        count(distinct po2.id) as total_orders,
        concat('{$admin_url}admin.php?page=shelf_planner_suppliers&supplier_id=', a.id)
            as supplier_edit_link
    from {$wpdb->suppliers} a
    
    left join `{$wpdb->purchase_orders}` po1 on po1.supplier_id = a.id and po1.status != 'Completed'
    left join `{$wpdb->purchase_orders}` po2 on po2.supplier_id = a.id and po2.status = 'Completed'
    
    group by a.id, a.supplier_name, a.supplier_code, a.tax_vat_number, a.phone_number, a.website, a.email_for_ordering, a.general_email_address, a.`description`, a.currency, a.address, a.city, a.country, a.state, a.zip_code, a.assigned_to, a.ship_to_location, a.discount, a.tax_rate, a.lead_times, a.dt_added, supplier_edit_link
", ARRAY_A );


if ( $tmp ) {
	foreach ( $tmp as $row ) {
		$suppliers[ $row['id'] ] = $row;
	}
}

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
                        <h2 class="purchase-or-title"><?php echo esc_html(__( 'A breadcrumb is used to show hierarchy between content', QA_MAIN_DOMAIN )); ?></h2>
                        <span class='purchase-or-subtitle'><?php echo esc_html(__( 'A breadcrumb is used to show hierarchy between content', QA_MAIN_DOMAIN )); ?></span>
                        <div class="d-flex nav-link-line" style="margin-top: 40px;">
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_suppliers' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_suppliers')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Suppliers', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_suppliers_add_new' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_suppliers_add_new')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Create New Supplier', QA_MAIN_DOMAIN)); ?></span></a>
                        </div>
                        <?php do_action( 'after_page_header' ); ?>
                        <div>
                            <div class="mt-30" style="margin-top: 60px !important;">
                                <div class="main-content-label mg-b-5">
									<?php echo esc_html( __( 'Add New', QA_MAIN_DOMAIN ) ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <div style="float:left;text-align: left;margin-bottom: 5px; width:30%">
                                            <button id="js-add-new" onclick="window.location = '<?php echo esc_url( get_admin_url() ); ?>admin.php?page=shelf_planner_suppliers_add_new';" class="btn btn-sm btn-success">Add New
                                            </button>
                                        </div>
                                        <div style="float:right;margin-bottom: 5px;width:29%;text-align: right;">
                                            <button id="download-csv" class="btn btn-sm btn-info"><?php echo esc_html( __( 'Download CSV', QA_MAIN_DOMAIN ) ); ?>
                                            </button>
                                            <button id="download-json" class="btn btn-sm btn-info"><?php echo esc_html( __( 'Download JSON', QA_MAIN_DOMAIN ) ); ?>
                                            </button>
                                            <button id="download-xlsx" class="btn btn-sm btn-info"><?php echo esc_html( __( 'Download XLSX', QA_MAIN_DOMAIN ) ); ?>
                                            </button>
                                            <button id="download-html" class="btn btn-sm btn-info"><?php echo esc_html( __( 'Download HTML', QA_MAIN_DOMAIN ) ); ?>
                                            </button>
                                        </div>
                                        <div id="table_1" style="width: 100%"></div>
                                        <script>
                                            //custom max min header filter
                                            var minMaxFilterEditor = function (cell, onRendered, success, cancel, editorParams) {

                                                var end;
                                                var container = document.createElement("span");
                                                //create and style inputs
                                                var start = document.createElement("input");
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
                                                    }

                                                    if (e.keyCode == 27) {
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

                                            var tabledata = <?php echo json_encode( $tmp );?>;
                                            var table = new Tabulator("#table_1", {
                                                // height:"311px",
                                                layout: "fitColumns",
                                                responsiveLayout: "collapse",
                                                data: tabledata,
                                                columns: [
                                                    {
                                                        title: "<?php echo __( 'Name', QA_MAIN_DOMAIN );?>",
                                                        field: "supplier_edit_link",
                                                        formatter: "link", /*headerFilter: "input", headerFilterLiveFilter: true,*/
                                                        formatterParams: {
                                                            labelField: "supplier_name",
                                                            urlPrefix: "",
                                                            target: "",
                                                        }
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Created', QA_MAIN_DOMAIN );?>",
                                                        field: "dt_added",
                                                        hozAlign: "left",
                                                        sorter: "date",
                                                        headerFilter: "input",
                                                        formatter: "datetime",
                                                        formatterParams: {
                                                            inputFormat: "YYYY-MM-DD H:m:s",
                                                            outputFormat: "LL",
                                                            invalidPlaceholder: "(invalid date)",
                                                        }
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Email', QA_MAIN_DOMAIN );?>",
                                                        field: "email_for_ordering",
                                                        headerFilter: "input",
                                                        formatter: "link",
                                                        formatterParams: {
                                                            labelField: "email_for_ordering",
                                                            urlPrefix: "mailto://",
                                                            target: "_blank",
                                                        }
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Orders', QA_MAIN_DOMAIN );?>",
                                                        field: "orders",
                                                        hozAlign: "left",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Completed Orders', QA_MAIN_DOMAIN );?>",
                                                        field: "total_orders",
                                                        hozAlign: "left",
                                                        sorter: "number",
                                                        headerFilter: minMaxFilterEditor,
                                                        headerFilterFunc: minMaxFilterFunction,
                                                        headerFilterLiveFilter: false
                                                    },
                                                    {
                                                        title: "<?php echo __( 'Country / Region', QA_MAIN_DOMAIN );?>",
                                                        field: "country",
                                                        headerFilter: "input",
                                                        headerFilterLiveFilter: true
                                                    },
                                                    {
                                                        title: "<?php echo __( 'City', QA_MAIN_DOMAIN );?>",
                                                        field: "city",
                                                        headerFilter: "input",
                                                        headerFilterLiveFilter: true
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
                        <div>
                            <div class="card-body" style="padding-left: 0;">
                                <div class="main-content-label mg-b-5">
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
										<?php
										if ( isset( $_GET['supplier_id'] ) || isset( $_GET['new'] ) ) {
											if ( isset( $_GET['supplier_id'] ) ) {
												$supplier = $suppliers[ sanitize_text_field( $_GET['supplier_id'] ) ];
											} else {
												$supplier = [
													'supplier_name'         => '',
													'supplier_code'         => '',
													'tax_vat_number'        => '',
													'phone_number'          => '',
													'website'               => '',
													'email_for_ordering'    => '',
													'general_email_address' => '',
													'supplier_id'           => '',
													'currency'              => '',
													'address'               => '',
													'city'                  => '',
													'country'               => '',
													'state'                 => '',
													'zip_code'              => '',
													'account_no'            => '',
													'assigned_to'           => '',
													'ship_to_location'      => '',
													'discount'              => '',
													'tax_rate'              => '',
													'lead_times'            => '',
													'weeks_of_stock'        => '',
													'description'           => '',
													'account_id'            => '',
													'payment_terms'         => '',
													'delivery_terms'        => '',
												];
											}
											?>
                                            <form method="post" action="">
                                                <div class="row" id="js-add-new-supplier">
                                                    <div class="col-md-4 ">
                                                        <label><?php echo esc_html( __( 'Supplier Name', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="supplier_name" required="required" value="<?php echo  esc_attr( $supplier['supplier_name'] ); ?>" placeholder="Supplier Name*"/> <label><?php echo esc_html( __( 'Supplier Code', QA_MAIN_DOMAIN ) ); ?>*</label>
                                                        <input type="text" class="form-control" name="supplier_code" required="required" value="<?php echo  esc_attr( $supplier['supplier_code'] ); ?>" placeholder="Supplier Code*"/>
                                                        <label><?php echo esc_html( __( 'TAX / VAT Number', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="tax_vat_number" value="<?php echo  esc_attr( $supplier['tax_vat_number'] ); ?>" placeholder="TAX / VAT Number"/>
                                                        <label><?php echo esc_html( __( 'Website', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="website" value="<?php echo  esc_attr( $supplier['website'] ); ?>" placeholder="Website"/>
                                                        <label><?php echo esc_html( __( 'Email for Ordering', QA_MAIN_DOMAIN ) ); ?>*</label>
                                                        <input type="text" class="form-control" name="email_for_ordering" value="<?php echo  esc_attr( $supplier['email_for_ordering'] ); ?>" placeholder="Email for Ordering*"/>
                                                        <label><?php echo esc_html( __( 'General Email Address', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="general_email_address" value="<?php echo  esc_attr( $supplier['general_email_address'] ); ?>" placeholder="General Email Address"/> <label><?php echo esc_html( __( 'Description', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <textarea class="form-control" name="description" placeholder="Description"><?php echo  esc_textarea( $supplier['description'] ); ?></textarea> <br>
                                                        <input type="submit" class="btn btn-success new-des-btn" value="<?php if ( ! isset( $_GET['supplier_id'] ) ) { ?>Add New Supplier<?php } else { ?>Save<?php } ?>"/>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label><?php echo esc_html( __( 'Currency', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="currency" required="required" value="<?php echo  esc_attr( $supplier['currency'] ); ?>" placeholder="Currency"/>
                                                        <label><?php echo esc_html( __( 'Address', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="address" value="<?php echo  esc_attr( $supplier['address'] ); ?>" placeholder="Address"/>
                                                        <label><?php echo esc_html( __( 'City', QA_MAIN_DOMAIN ) ); ?>*</label> <input type="text" class="form-control" name="city" value="<?php echo  esc_attr( $supplier['city'] ); ?>" placeholder="City*"/>
                                                        <label><?php echo esc_html( __( 'Country', QA_MAIN_DOMAIN ) ); ?>*</label>
                                                        <input type="text" class="form-control" name="country" value="<?php echo  esc_attr( $supplier['country'] ); ?>" placeholder="Country*"/>
                                                        <label>State</label> <input type="text" class="form-control" name="state" value="<?php echo  esc_attr( $supplier['state'] ); ?>" placeholder="State"/>
                                                        <label><?php echo esc_html( __( 'Zip Code', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="zip_code" value="<?php echo  esc_attr( $supplier['zip_code'] ); ?>" placeholder="Zip Code"/>
                                                        <label><?php echo esc_html( __( 'Account Number', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="account_no" value="<?php echo  esc_attr( $supplier['account_no'] ); ?>" placeholder="Account Number"/>
                                                        <label><?php echo esc_html( __( 'Account ID', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="account_id" value="<?php echo  esc_attr( $supplier['account_id'] ); ?>" placeholder="Account ID"/>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <label><?php echo esc_html( __( 'Phone', QA_MAIN_DOMAIN ) ); ?></label>
                                                    <input type="text" class="form-control" name="phone_number" value="<?php echo  esc_attr( $supplier['phone_number'] ); ?>" placeholder="Phone"/>
                                                        <label><?php echo esc_html( __( 'Assigned To', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="assigned_to" required="required" value="<?php echo  esc_attr( $supplier['assigned_to'] ); ?>" placeholder="Assigned To"/>
                                                        <label><?php echo esc_html( __( 'Ship To Location', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="ship_to_location" value="<?php echo  esc_attr( $supplier['ship_to_location'] ); ?>" placeholder="Ship To Location"/>
                                                        <label><?php echo esc_html( __( 'Discount', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="discount" value="<?php echo  esc_attr( $supplier['discount'] ); ?>" placeholder="Discount"/>
                                                        <label><?php echo esc_html( __( 'Tax Rate', QA_MAIN_DOMAIN ) ); ?>
                                                        </label> <input type="text" class="form-control" name="tax_rate" value="<?php echo  esc_attr( $supplier['tax_rate'] ); ?>" placeholder="Tax Rate"/>
                                                        <label><?php echo esc_html( __( 'Lead Times (in weeks)', QA_MAIN_DOMAIN ) ); ?>*</label>
                                                        <input type="text" class="form-control" name="lead_times" required="required" value="<?php echo  esc_attr( $supplier['lead_times'] ); ?>" placeholder="Lead Times (in weeks) *"/> <label><?php echo esc_html( __( 'Payment Terms', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="payment_terms" value="<?php echo  esc_attr( $supplier['payment_terms'] ); ?>" placeholder="Payment Terms"/>
                                                        <label><?php echo esc_html( __( 'Delivery Terms', QA_MAIN_DOMAIN ) ); ?></label>
                                                        <input type="text" class="form-control" name="delivery_terms" value="<?php echo  esc_attr( $supplier['delivery_terms'] ); ?>" placeholder="Delivery Terms"/>
                                                    </div>
                                                </div>
                                            </form>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php include __DIR__ . '/../' . "popups.php"; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/../' . 'footer.php';