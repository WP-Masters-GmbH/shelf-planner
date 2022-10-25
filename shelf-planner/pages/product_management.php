<?php

/**
 * Product Management page, like /wp-admin/admin.php?page=shelf_planner_product_management
 * Here are debug, import, export functions are located
 */

if ( $_POST && isset( $_POST['action'] ) ) {
	if ( $_POST['action'] == 'download_sample' ) {
		/**
		 * Make sample file to prepare import
		 */
		$sample_data = [
			[ 'product_id' ] + array_keys( QAMain_Core::get_products_settings_list() ),
		];
		foreach ( QAMain_Core::get_all_product_ids() as $row ) {
			$product_id = $row['product_id'];
			$tmp        = [ 'product_id' => $product_id ];
			foreach ( QAMain_Core::get_products_settings_list() as $key => $description ) {
				$tmp[ $key ] = '';
				if ( $key == 'sp_primary_category' ) {
					$tmp[ $key ] = (string) QAMain_Core::get_product_primary_category_id( $product_id );
				}
			}
			$sample_data[] = $tmp;
		}

		$writer = new XLSXWriter();
		$writer->writeSheet( $sample_data );

		$file_name = sanitize_file_name($_SERVER['HTTP_HOST'] . '_Products_Settings_Sample_' . time() . '.xlsx');
		$file_path = SP_PLUGIN_DIR_PATH . $file_name;
		$writer->writeToFile( $file_path );
		header( "Location: " . str_replace('pages', '', plugin_dir_url( __FILE__ ) . $file_name) );
		exit;
	} elseif ( $_POST['action'] == 'export' ) {
		/**
		 * Make export of all the current settings
		 */
		$data = QAMain_Core::get_all_product_settings();

		foreach ( $data as $key => $item ) {
			unset( $data[ $key ]['setting_id'] );
		}

		/**
		 * If no settings yet
		 */
		if ( ! $data ) {
			$sample_data = [
				[ 'product_id' ] + array_keys( QAMain_Core::get_products_settings_list() ),
			];
		} else {
			$sample_data = [
				array_keys( $data[0] ),
			];
			foreach ( $data as $key => $item ) {
				$sample_data[] = $item;
			}
		}

		$writer = new XLSXWriter();
		$writer->writeSheet( $sample_data );

		$file_name = $_SERVER['HTTP_HOST'] . '_Products_Settings_Export_' . date( 'd.m.Y_H:i:s' ) . '.xlsx';
		$file_path = SP_ROOT_DIR . DIRECTORY_SEPARATOR . $file_name;
		$writer->writeToFile( $file_path );
		header( "Location: " . plugin_dir_url( SP_FILE_INDEX ) . $file_name );
		exit;
	}
}

require_once SP_ROOT_DIR . '/pages/admin_page_header.php';

?><?php require_once SP_ROOT_DIR . '/header.php'; ?>
    <style>
        @media (min-width: 1200px) {
            .container, .container-lg, .container-md, .container-sm, .container-xl {
                max-width: 95% !important;
            }
        }
    </style>
    <div class="sp-admin-overlay">
        <div class="sp-admin-container">
			<?php include SP_ROOT_DIR . "/left_sidebar.php"; ?>
            <!-- main-content opened -->
            <div class="main-content horizontal-content">
                <div class="page">
                <?php include __DIR__ . '/../' . "page_header.php"; ?>
                    <!-- container opened -->
                    <div class="ml-40 mr-40">
                        <h2 class="purchase-or-title"><?php echo esc_html(__( 'Product Management', QA_MAIN_DOMAIN )); ?></h2>
                        <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Here you can manage and update your product data, cost prices, lead-time, etc.', QA_MAIN_DOMAIN )); ?></span>
                        <?php do_action( 'after_page_header' ); ?>
                        <div style="margin-top: 40px;">
                            <div class="card-body-product-man card-body">
                                <div class="row">
                                    <div class="qa-bulk-edit-costs">
                                        <div class="qa-bulk-header">
                                            <div class="left-bulk-head">
                                                <h4>Bulk Edit Costs</h4>
                                                <p>Edit Product Params without single Edit Post</p>
                                            </div>
                                            <div class="right-bulk-head">
                                                <input type="text" id="bulk-search-sp" value="<?php echo esc_attr($search); ?>" placeholder="Fast Search">
                                            </div>
                                        </div>
                                        <div class="filters-enabled">
                                            <div class="filter-groupe">
                                                <input id="product_id_input" type="checkbox" value="product_id" <?php if(in_array('product_id', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="product_id_input">Product ID</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="product_sku_input" type="checkbox" value="product_sku" <?php if(in_array('product_sku', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="product_sku_input">SKU</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="product_title_input" type="checkbox" value="product_title" <?php if(in_array('product_title', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="product_title_input">Title</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_activate_replenishment_input" type="checkbox" value="sp_activate_replenishment" <?php if(in_array('sp_activate_replenishment', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_activate_replenishment_input">Activate Replenishment</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_supplier_id_input" type="checkbox" value="sp_supplier_id" <?php if(in_array('sp_supplier_id', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_supplier_id_input">Supplier</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_weeks_of_stock_input" type="checkbox" value="sp_weeks_of_stock" <?php if(in_array('sp_weeks_of_stock', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_weeks_of_stock_input">Weeks Of Stock</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_lead_time_input" type="checkbox" value="sp_lead_time" <?php if(in_array('sp_lead_time', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_lead_time_input">Lead Time</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_product_launch_date_input" type="checkbox" value="sp_product_launch_date" <?php if(in_array('sp_product_launch_date', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_product_launch_date_input">Product Launch Date</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_product_replenishment_date_input" type="checkbox" value="sp_product_replenishment_date" <?php if(in_array('sp_product_replenishment_date', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_product_replenishment_date_input">Replenishment Date</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_inbound_stock_limit_input" type="checkbox" value="sp_inbound_stock_limit" <?php if(in_array('sp_inbound_stock_limit', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_inbound_stock_limit_input">Inbound Stock Limit</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_on_hold_input" type="checkbox" value="sp_on_hold" <?php if(in_array('sp_on_hold', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_on_hold_input">On Hold</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_primary_category_input" type="checkbox" value="sp_primary_category" <?php if(in_array('sp_primary_category', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_primary_category_input">Primary Category</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_size_packs_input" type="checkbox" value="sp_size_packs" <?php if(in_array('sp_size_packs', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_size_packs_input">Size Packs</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_size_pack_threshold_input" type="checkbox" value="sp_size_pack_threshold" <?php if(in_array('sp_size_pack_threshold', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_size_pack_threshold_input">Size Pack Threshold</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_sku_pack_size_input" type="checkbox" value="sp_sku_pack_size" <?php if(in_array('sp_sku_pack_size', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_sku_pack_size_input">SKU Pack Size</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_supplier_product_id_input" type="checkbox" value="sp_supplier_product_id" <?php if(in_array('sp_supplier_product_id', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_supplier_product_id_input">Supplier Product ID</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_supplier_product_reference_input" type="checkbox" value="sp_supplier_product_reference" <?php if(in_array('sp_supplier_product_reference', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_supplier_product_reference_input">Supplier Product Reference</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_cost_input" type="checkbox" value="sp_cost" <?php if(in_array('sp_cost', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_cost_input">Unit Cost Price</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_stock_value_input" type="checkbox" value="sp_stock_value" <?php if(in_array('sp_stock_value', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_stock_value_input">Stock Value</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_mark_up_input" type="checkbox" value="sp_mark_up" <?php if(in_array('sp_mark_up', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_mark_up_input">Markup</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_margin_input" type="checkbox" value="sp_margin" <?php if(in_array('sp_margin', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_margin_input">Net Margin (Incl VAT)</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="sp_margin_tax_input" type="checkbox" value="sp_margin_tax" <?php if(in_array('sp_margin_tax', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="sp_margin_tax_input">Net Margin (excl VAT)</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="product_price_input" type="checkbox" value="product_price" <?php if(in_array('product_price', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="product_price_input">Price</label>
                                            </div>
                                            <div class="filter-groupe">
                                                <input id="product_stock_input" type="checkbox" value="product_stock" <?php if(in_array('product_stock', $columns)) {echo esc_attr('checked');}?>>
                                                <label for="product_stock_input">Stocks</label>
                                            </div>
                                        </div>
                                        <div class="bulk-data-table sp-styles">
                                            <table class="widefat fixed" cellspacing="0">
                                                <thead>
                                                <tr>
                                                    <th id="product_id" class="manage-column column-id" scope="col" style="<?php if(!in_array('product_id', $columns)) {echo esc_attr('display: none;');}?>">Product ID</th>
                                                    <th id="product_sku" class="manage-column column-sku" scope="col" style="<?php if(!in_array('product_sku', $columns)) {echo esc_attr('display: none;');}?>">SKU</th>
                                                    <th id="product_title" class="manage-column column-title" scope="col" style="<?php if(!in_array('product_title', $columns)) {echo esc_attr('display: none;');}?>">Title</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_activate_replenishment', $columns)) {echo esc_attr('display: none;');}?>">Activate Replenishment</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_id', $columns)) {echo esc_attr('display: none;');}?>">Supplier</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_weeks_of_stock', $columns)) {echo esc_attr('display: none;');}?>">Weeks Of Stock</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_lead_time', $columns)) {echo esc_attr('display: none;');}?>">Lead Time</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_product_launch_date', $columns)) {echo esc_attr('display: none;');}?>">Product Launch Date</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_product_replenishment_date', $columns)) {echo esc_attr('display: none;');}?>">Replenishment Date</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_inbound_stock_limit', $columns)) {echo esc_attr('display: none;');}?>">Inbound Stock Limit</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_on_hold', $columns)) {echo esc_attr('display: none;');}?>">On Hold</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_primary_category', $columns)) {echo esc_attr('display: none;');}?>">Primary Category</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_size_packs', $columns)) {echo esc_attr('display: none;');}?>">Size Packs</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_size_pack_threshold', $columns)) {echo esc_attr('display: none;');}?>">Size Pack Threshold</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_sku_pack_size', $columns)) {echo esc_attr('display: none;');}?>">SKU Pack Size</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_product_id', $columns)) {echo esc_attr('display: none;');}?>">Supplier Product ID</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_product_reference', $columns)) {echo esc_attr('display: none;');}?>">Supplier Ref</th>
                                                    <th id="product_cost" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_cost', $columns)) {echo esc_attr('display: none;');}?>">Unit Cost</th>
                                                    <th id="product_stock_value" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_stock_value', $columns)) {echo esc_attr('display: none;');}?>">Stock Value</th>
                                                    <th id="product_markup" class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_mark_up', $columns)) {echo esc_attr('display: none;');}?>">Markup</th>
                                                    <th id="product_margin_vat" class="manage-column column-margin-vat num" scope="col" style="<?php if(!in_array('sp_margin', $columns)) {echo esc_attr('display: none;');}?>">Net Margin (Incl VAT)</th>
                                                    <th id="product_margin_net" class="manage-column column-margin-net num" scope="col" style="<?php if(!in_array('sp_margin_tax', $columns)) {echo esc_attr('display: none;');}?>">Net Margin (excl VAT)</th>
                                                    <th id="product_price" class="manage-column column-price num" scope="col" style="<?php if(!in_array('product_price', $columns)) {echo esc_attr('display: none;');}?>">Price</th>
                                                    <th id="product_stock" class="manage-column column-stock num" scope="col" style="<?php if(!in_array('product_stock', $columns)) {echo esc_attr('display: none;');}?>">Stocks</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th class="manage-column column-id" scope="col" style="<?php if(!in_array('product_id', $columns)) {echo esc_attr('display: none;');}?>">Product ID</th>
                                                    <th class="manage-column column-sku" scope="col" style="<?php if(!in_array('product_sku', $columns)) {echo esc_attr('display: none;');}?>">SKU</th>
                                                    <th class="manage-column column-title" scope="col" style="<?php if(!in_array('product_title', $columns)) {echo esc_attr('display: none;');}?>">Title</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_activate_replenishment', $columns)) {echo esc_attr('display: none;');}?>">Activate Replenishment</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_id', $columns)) {echo esc_attr('display: none;');}?>">Supplier</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_weeks_of_stock', $columns)) {echo esc_attr('display: none;');}?>">Weeks Of Stock</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_lead_time', $columns)) {echo esc_attr('display: none;');}?>">Lead Time</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_product_launch_date', $columns)) {echo esc_attr('display: none;');}?>">Product Launch Date</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_product_replenishment_date', $columns)) {echo esc_attr('display: none;');}?>">Replenishment Date</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_inbound_stock_limit', $columns)) {echo esc_attr('display: none;');}?>">Inbound Stock Limit</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_on_hold', $columns)) {echo esc_attr('display: none;');}?>">On Hold</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_primary_category', $columns)) {echo esc_attr('display: none;');}?>">Primary Category</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_size_packs', $columns)) {echo esc_attr('display: none;');}?>">Size Packs</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_size_pack_threshold', $columns)) {echo esc_attr('display: none;');}?>">Size Pack Threshold</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_sku_pack_size', $columns)) {echo esc_attr('display: none;');}?>">SKU Pack Size</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_product_id', $columns)) {echo esc_attr('display: none;');}?>">Supplier Product ID</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_product_reference', $columns)) {echo esc_attr('display: none;');}?>">Supplier Ref</th>
                                                    <th class="manage-column column-cost num" scope="col" style="<?php if(!in_array('sp_cost', $columns)) {echo esc_attr('display: none;');}?>">Unit Cost</th>
                                                    <th class="manage-column column-stock-value num" scope="col" style="<?php if(!in_array('sp_stock_value', $columns)) {echo esc_attr('display: none;');}?>">Stock Value</th>
                                                    <th class="manage-column column-markup num" scope="col" style="<?php if(!in_array('sp_mark_up', $columns)) {echo esc_attr('display: none;');}?>">Markup</th>
                                                    <th class="manage-column column-margin num" scope="col" style="<?php if(!in_array('sp_margin', $columns)) {echo esc_attr('display: none;');}?>">Margin (VAT)</th>
                                                    <th class="manage-column column-net-margin num" scope="col" style="<?php if(!in_array('sp_margin_tax', $columns)) {echo esc_attr('display: none;');}?>">Net Margin</th>
                                                    <th class="manage-column column-price num" scope="col" style="<?php if(!in_array('product_price', $columns)) {echo esc_attr('display: none;');}?>">Price</th>
                                                    <th class="manage-column column-stock num" scope="col" style="<?php if(!in_array('product_stock', $columns)) {echo esc_attr('display: none;');}?>">Stocks</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                <?php


                                                $table = $wpdb->prefix.'sp_suppliers';
                                                $suppliers = $wpdb->get_results("SELECT * FROM {$table}");

                                                $taxonomy     = 'product_cat';
                                                $orderby      = 'name';
                                                $show_count   = 0;      // 1 for yes, 0 for no
                                                $pad_counts   = 0;      // 1 for yes, 0 for no
                                                $hierarchical = 1;      // 1 for yes, 0 for no
                                                $title        = '';
                                                $empty        = 0;

                                                $args = array(
                                                    'taxonomy'     => $taxonomy,
                                                    'orderby'      => $orderby,
                                                    'show_count'   => $show_count,
                                                    'pad_counts'   => $pad_counts,
                                                    'hierarchical' => $hierarchical,
                                                    'title_li'     => $title,
                                                    'hide_empty'   => $empty
                                                );

                                                $categories = get_categories($args);

                                                while($products->have_posts()) : $products->the_post();

                                                    // Prepare Variables
                                                    $product_id = get_the_ID();
                                                    $product = wc_get_product($product_id);

                                                    $table = $wpdb->prefix.'sp_product_settings';
                                                    $item_data = $wpdb->get_row("SELECT * FROM {$table} WHERE product_id='{$product_id}'");
                                                    ?>
                                                    <tr class="bulk-product-item" data-product-id="<?php echo esc_html($product_id); ?>">
                                                        <th class="column-id" scope="col" style="<?php if(!in_array('product_id', $columns)) {echo esc_attr('display: none;');}?>"><a title="Edit Product" href="<?php echo esc_html("/wp-admin/post.php?post={$product_id}&action=edit"); ?>"><?php echo esc_html($product_id); ?></a></th>
                                                        <th class="column-sku" scope="col" style="<?php if(!in_array('product_sku', $columns)) {echo esc_attr('display: none;');}?>"><?php echo esc_html($product->get_sku()); ?></th>
                                                        <th class="column-title" scope="col" style="<?php if(!in_array('product_title', $columns)) {echo esc_attr('display: none;');}?>"><a title="To Product Page" href="<?php echo esc_html(get_permalink($product_id)); ?>"><?php echo esc_html($product->get_name()); ?></a></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_activate_replenishment', $columns)) {echo esc_attr('display: none;');}?>"><select class="sp_activate_replenishment smaller-input" value="<?php echo esc_attr($item_data->sp_activate_replenishment); ?>"><option value="1" <?php if('value == 1') {echo 'selected';} ?>>No</option><option value="0" <?php if('value == 0') {echo 'selected';} ?> selected>Yes</option></select></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_id', $columns)) {echo esc_attr('display: none;');}?>">
                                                            <select class="sp_supplier_id">
                                                                <?php foreach($suppliers as $supplier) { ?>
                                                                    <option value="<?php echo esc_attr($supplier->id); ?>" <?php if($supplier->id == $item_data->sp_supplier_id) {echo esc_attr('selected');}?>><?php echo esc_html($supplier->supplier_name); ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_weeks_of_stock', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_weeks_of_stock smaller-input" value="<?php echo esc_attr($item_data->sp_weeks_of_stock); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_lead_time', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_lead_time smaller-input" value="<?php echo esc_attr($item_data->sp_lead_time); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_product_launch_date', $columns)) {echo esc_attr('display: none;');}?>"><input type="date" class="sp_product_launch_date date_inputs" value="<?php echo esc_attr((date('Y-m-d', strtotime($item_data->sp_product_launch_date)))); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_product_replenishment_date', $columns)) {echo esc_attr('display: none;');}?>"><input type="date" class="sp_product_replenishment_date date_inputs" value="<?php echo esc_attr((date('Y-m-d', strtotime($item_data->sp_product_replenishment_date)))); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_inbound_stock_limit', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_inbound_stock_limit smaller-input" value="<?php echo esc_attr($item_data->sp_inbound_stock_limit); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_on_hold', $columns)) {echo esc_attr('display: none;');}?>"><select class="sp_on_hold smaller-input" value="<?php echo esc_attr($item_data->sp_on_hold); ?>"><option value="1" <?php if('value == 1') {echo 'selected';} ?>>Yes</option><option value="0" <?php if('value == 0') {echo 'selected';} ?> selected>No</option></select></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_primary_category', $columns)) {echo esc_attr('display: none;');}?>">
                                                            <select class="sp_primary_category">
                                                                <?php foreach($categories as $category) { ?>
                                                                    <option value="<?php echo esc_attr($category->term_id); ?>" <?php if($category->term_id == $item_data->sp_primary_category) {echo esc_attr('selected');}?>><?php echo esc_html($category->name); ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_size_packs', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_size_packs smaller-input" value="<?php echo esc_attr($item_data->sp_size_packs); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_size_pack_threshold', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_size_pack_threshold smaller-input" value="<?php echo esc_attr($item_data->sp_size_pack_threshold); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_sku_pack_size', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_sku_pack_size smaller-input" value="<?php echo esc_attr($item_data->sp_sku_pack_size); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_product_id', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_supplier_product_id smaller-input" value="<?php echo esc_attr($item_data->sp_supplier_product_id); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_product_reference', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_supplier_product_reference smaller-input" value="<?php echo esc_attr($item_data->sp_supplier_product_reference); ?>"></th>
                                                        <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_cost', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_cost smaller-input" value="<?php echo esc_attr($item_data->sp_cost); ?>"></th>
                                                        <th class="column-stock-value num" scope="col" style="<?php if(!in_array('sp_stock_value', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="smaller-input" value="<?php echo esc_attr($item_data->sp_stock_value); ?>" readonly></th>
                                                        <th class="column-markup num" scope="col" style="<?php if(!in_array('sp_mark_up', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_mark_up smaller-input" value="<?php echo esc_attr($item_data->sp_mark_up); ?>" readonly></th>
                                                        <th class="column-markup num" scope="col" style="<?php if(!in_array('sp_margin', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_margin smaller-input" value="<?php echo esc_attr($item_data->sp_margin); ?>" readonly></th>
                                                        <th class="column-markup num" scope="col" style="<?php if(!in_array('sp_margin_tax', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_margin_tax smaller-input" value="<?php echo esc_attr($item_data->sp_margin_tax); ?>" readonly></th>
                                                        <th class="column-price num" scope="col" style="<?php if(!in_array('product_price', $columns)) {echo esc_attr('display: none;');}?>"><?php echo esc_html($product->get_price().get_woocommerce_currency_symbol()); ?></th>
                                                        <th class="column-stock num" scope="col" style="<?php if(!in_array('product_stock', $columns)) {echo esc_attr('display: none;');}?>"><?php echo esc_html($product->get_stock_quantity()); ?></th>
                                                    </tr>
                                                <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                            <div class="qa-settings-save-footer">
                                                <button id="save-qa-bulk-sp" class="button action">Save Page Products</button>
                                                <div class="qa-bulk-pagination">
                                                    <button id="prev-bulk-page-sp" data-page="<?php echo esc_attr($page-1); ?>" class="button action" <?php if($page == 1) {echo esc_attr('disabled');} ?>><</button>
                                                    <span><?php echo esc_html($page); ?> of <?php echo esc_html($products->max_num_pages); ?></span>
                                                    <button id="prev-bulk-page-sp" data-page="<?php echo esc_attr($page+1); ?>" class="button action" <?php if($page == $products->max_num_pages) {echo esc_attr('disabled');} ?>>></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div style="margin: 40px 0;">
                            <div class="card-body" style="padding-left: 0 !important;">
                                <div class="main-content-label mg-b-5">
									<?php echo  esc_html( __( 'Settings Import/Export', QA_MAIN_DOMAIN ) ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <div style="width: 100%;clear: both; float: left;">
                                            <form action="" method="POST" style="max-width: 200px; float: left; margin-right: 10px;">
                                                <p>
                                                    <input type="hidden" required name="action" value="download_sample"/> <input type="hidden" required name="redirect" value="<?php echo  esc_attr( $_SERVER['REQUEST_URI'] ); ?>"/> <input type="submit" class="button button-primary" value="<?php echo esc_attr( __( 'Download Sample', QA_MAIN_DOMAIN ) ); ?>"/>
                                                </p>
                                            </form>
                                            <div style="max-width: 200px; float: left; margin-right: 10px;">
                                                <p>
                                                    <a href="<?php echo esc_url( admin_url( 'admin-ajax.php?action=sp-ajax-xlsx&upload_settings_xlsx.php?TB_iframe=true&width=600&height=100' ) ); ?>" target="_blank"> <input type="button" class="button button-primary" value="<?php echo esc_attr( __( 'Import', QA_MAIN_DOMAIN ) ); ?>"/> </a>
                                                </p>
                                            </div>
                                            <form action="" method="POST" style="max-width: 200px; float: left; margin-right: 10px;">
                                                <p>
                                                    <input type="hidden" required name="action" value="export"/> <input type="hidden" required name="redirect" value="<?php echo  esc_attr( $_SERVER['REQUEST_URI'] ); ?>"/> <input type="submit" class="button button-primary" value="<?php echo esc_attr( __( 'Export', QA_MAIN_DOMAIN ) ); ?>"/>
                                                </p>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card-body" style="padding-left: 0 !important">
                                <div class="main-content-label mg-b-5">
									<?php echo esc_html( __( 'Get Product Info', QA_MAIN_DOMAIN ) ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <p><?php echo esc_html( __( 'Use this tool to view settings and real-time Forecast API data per product.', QA_MAIN_DOMAIN ) ); ?></p>
                                        <form action="" method="get">
                                            <p>
                                                <input type="hidden" required name="page" value="<?php echo  esc_attr( sanitize_text_field( $_GET['page'] ) ) ?>"/> <input type="text" required name="product_id" placeholder="Product ID/SKU" value="<?php echo  isset( $_GET['product_id'] ) ? (int) $_GET['product_id'] : ''; ?>"/> <input type="submit" class="button button-primary" value="<?php echo esc_attr( __( 'Search', QA_MAIN_DOMAIN ) ); ?>"/>
                                            </p>
                                        </form>
										<?php if ( isset( $_GET['product_id'] ) && $product_settings = QAMain_Core::get_product_settings( sanitize_text_field($_GET['product_id']) ) ) {
											$product_id = (int) $_GET['product_id'];

											$primary_category_id = QAMain_Core::get_product_primary_category_id( $product_id );
											$forecast            = QAMain_Core::get_sales_forecast_by_product_id( $product_id );
											$forecast_by_weeks   = $forecast['WeeklySlesArray'];

											?>
                                            <table class="wp-list-table widefat striped">
                                                <tr>
                                                    <th>
                                                        <b><?php echo esc_html( __( 'General Info', QA_MAIN_DOMAIN ) ); ?>
                                                        </b></th>
                                                    <th><b><?php echo esc_html( __( 'Value', QA_MAIN_DOMAIN ) ); ?></b>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?php echo esc_html( __( 'Product Title', QA_MAIN_DOMAIN ) ); ?></td>
                                                    <td>
                                                        <span><?php echo  esc_html( get_post( $product_id )->post_title ); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?php echo esc_html( __( 'Product Link', QA_MAIN_DOMAIN ) ); ?></td>
                                                    <td><a target="_blank" href="<?php echo  esc_url( get_post_permalink( $product_id ) ); ?>"><?php echo  esc_html( get_post_permalink( $product_id ) ); ?></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?php echo esc_html( __( 'Primary Category', QA_MAIN_DOMAIN ) ); ?></td>
                                                    <td>
                                                        <span><?php echo  esc_html( get_term_by( 'id', $primary_category_id, 'product_cat' )->name ); ?> (ID: <?php echo  (int) $primary_category_id; ?>)</span>
                                                    </td>
                                                </tr>
                                            </table><br>
                                            <table class="wp-list-table widefat striped">
                                                <tr>
                                                    <th>
                                                        <b><?php echo esc_html( __( 'Real Time Data', QA_MAIN_DOMAIN ) ); ?></b>
                                                    </th>
                                                    <th>
                                                        <b><?php echo esc_html( __( 'Value', QA_MAIN_DOMAIN ) ); ?></b>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;">
														<?php echo esc_html( __( 'Variation (Child) Product?', QA_MAIN_DOMAIN ) ); ?>
                                                    </td>
                                                    <td>
														<?php if ( $product_settings['parent_id'] ) { ?><?php echo esc_html( __( 'This is a variation (child) product.', QA_MAIN_DOMAIN ) ); ?>
                                                            <a href="<?php echo  esc_url( admin_url( 'admin.php?page=shelf_planner_product_management&product_id=' . $product_settings['parent_id'] ) ); ?>" target="_blank"><?php echo esc_html( __( 'Parent product info', QA_MAIN_DOMAIN ) ); ?></a>
														<?php } else { ?><?php echo esc_html( __( 'No.', QA_MAIN_DOMAIN ) ); ?><?php

															$product          = wc_get_product( $product_settings['product_id'] );
															$current_products = $product->get_children();

															if ( $current_products ) {
																echo esc_html( __( 'Found ' . count( $current_products ) . ' variations. Variations info: ', QA_MAIN_DOMAIN ) );
																foreach ( $current_products as $variation_id ) {
																	?>
                                                                    [ <a href="<?php echo  esc_url( admin_url( 'admin.php?page=shelf_planner_product_management&product_id=' . $variation_id ) ); ?>" target="_blank"><?php echo  (int) $variation_id ?></a> ]
																	<?php
																}
															}

															?><?php } ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;">
														<?php echo esc_html( __( 'Ideal Stock', QA_MAIN_DOMAIN ) ); ?>
                                                        <span style="color: red"><?php echo esc_html( __( '(DEBUG)', QA_MAIN_DOMAIN ) ); ?></span>
                                                    </td>
                                                    <td>
														<?php

														$stock_weeks_total = $product_settings['sp_weeks_of_stock'] + $product_settings['sp_lead_time'];
														$ideal_stock       = 0;
														foreach ( range( 0, $stock_weeks_total - 1 ) as $week_id ) {
															$ideal_stock += $forecast_by_weeks[ $week_id ];
														}

														?>
                                                        <code>Based on [Cover (<?php echo  (int) $product_settings['sp_weeks_of_stock'] ?>) + Leadtime (<?php echo  (int) $product_settings['sp_lead_time'] ?>) =
															<?php echo  (int) $stock_weeks_total; ?> Weeks] = SUM(<?php echo  esc_html( implode( ', ', array_slice( $forecast_by_weeks, 0, $stock_weeks_total ) ) ); ?>) = <?php echo  (int) $ideal_stock; ?> </code></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?php echo esc_html( __( 'Sales Forecast by Weeks', QA_MAIN_DOMAIN ) ); ?>
                                                    </td>
                                                    <td><textarea readonly style="width: 100%" rows="6"><?php foreach ( $forecast_by_weeks as $week => $week_value ) { ?>Week <?php echo  (int) $week + 1; ?>: <?php echo  (int) $week_value . PHP_EOL; ?><?php } ?></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 400px;"><?php echo esc_html( __( 'Raw Forecast Data', QA_MAIN_DOMAIN ) ); ?>
                                                        <span style="color: red"><?php echo esc_html( __( '(DEBUG)', QA_MAIN_DOMAIN ) ); ?></span></td>
                                                    <td><textarea readonly style="width: 100%" rows="6"><?php echo  json_encode( $forecast ); ?></textarea>
                                                    </td>
                                                </tr>
                                            </table><br>
                                            <table class="wp-list-table widefat striped">
                                                <tr>
                                                    <th><b><?php echo esc_html( __( 'Setting', QA_MAIN_DOMAIN ) ); ?></b></th>
                                                    <th><b><?php echo esc_html( __( 'Value', QA_MAIN_DOMAIN ) ); ?></b>
                                                    <th><b><?php echo esc_html( __( 'Setting', QA_MAIN_DOMAIN ) ); ?></b></th>
                                                    <th><b><?php echo esc_html( __( 'Value', QA_MAIN_DOMAIN ) ); ?></b>
                                                    </th>
                                                </tr>
												<?php $i = 0; ?>
												<?php foreach ( QAMain_Core::get_products_settings_list() as $key => $description ) { ?><?php if ( $i == 0 ) { ?><tr><?php } ?>
                                                    <td style="width: 400px;"><?php echo  esc_html( $description ); ?></td>
                                                    <td>
                                                        <span class="badge badge-success"><?php echo esc_html(( trim( $product_settings[ $key ] ) ? $product_settings[ $key ] : 'null' )); ?></span>
                                                    </td>
													<?php $i ++;
													if ( $i == 2 || $key == 'sp_margin_tax' ) { ?></tr><?php $i = 0;
													} ?><?php } ?>
                                            </table>
										<?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 40px">
                            <div class="card-body" style="padding-left: 0 !important;">
                                <div class="main-content-label mg-b-5">
									<?php echo esc_html( __( 'Settings Reference', QA_MAIN_DOMAIN ) ); ?>
                                </div>
                                <p class="mg-b-20"></p>
                                <div class="row">
                                    <div class="col-md-12 col">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mg-b-0 text-md-nowrap">
                                                <thead>
                                                <tr>
                                                    <th><b><?php echo esc_html( __( 'Setting', QA_MAIN_DOMAIN ) ); ?></b></th>
                                                    <th><b><?php echo esc_html( __( 'Description', QA_MAIN_DOMAIN ) ); ?></b></th>
                                                    <th><b><?php echo esc_html( __( 'Setting', QA_MAIN_DOMAIN ) ); ?></b></th>
                                                    <th><b><?php echo esc_html( __( 'Description', QA_MAIN_DOMAIN ) ); ?></b></th>
                                                </tr>
                                                </thead>
												<?php $i = 0; ?>
												<?php foreach ( QAMain_Core::get_products_settings_list() as $key => $description ) { ?><?php if ( $i == 0 ) { ?><tr><?php } ?>
                                                    <td style="width: 100px;"><span class="badge badge-primary"><?php echo  esc_html( $key ); ?></span>
                                                    </td>
                                                    <td><?php echo  esc_html( $description ); ?></td>
													<?php $i ++;
													if ( $i == 2 || $key == 'sp_margin_tax' ) { ?></tr><?php $i = 0;
													} ?><?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include __DIR__ . '/../' . "popups.php"; ?>
            </div>
        </div>
    </div>

<?php require_once SP_ROOT_DIR . '/footer.php';