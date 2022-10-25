<div class="bulk-data-table sp-styles">
    <table class="widefat fixed" cellspacing="0">
        <thead>
        <tr>
            <th id="product_id" class="manage-column column-id" scope="col" style="<?php if(!in_array('product_id', $columns)) {echo esc_attr('display: none;' );}?>">Product ID</th>
            <th id="product_sku" class="manage-column column-sku" scope="col" style="<?php if(!in_array('product_sku', $columns)) {echo esc_attr('display: none;');} ?>">SKU</th>
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
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_activate_replenishment', $columns)) {echo esc_attr('display: none;');}?>"><select class="sp_activate_replenishment smaller-input" value="<?php echo esc_html($item_data->sp_activate_replenishment); ?>"><option value="1" <?php if('value == 1') {echo 'selected';} ?>>No</option><option value="0" <?php if('value == 0') {echo 'selected';} ?> selected>Yes</option></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_id', $columns)) {echo esc_attr('display: none;');}?>">
                    <select class="sp_supplier_id">
                        <?php foreach($suppliers as $supplier) { ?>
                            <option value="<?php echo esc_html($supplier->id); ?>" <?php if($supplier->id == $item_data->sp_supplier_id) {echo 'selected';}?>><?php echo esc_html($supplier->supplier_name); ?></option>
                        <?php } ?>
                    </select>
                </th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_weeks_of_stock', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_weeks_of_stock smaller-input" value="<?php echo esc_html($item_data->sp_weeks_of_stock); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_lead_time', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_lead_time smaller-input" value="<?php echo esc_html($item_data->sp_lead_time); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_product_launch_date', $columns)) {echo esc_attr('display: none;');}?>"><input type="date" class="sp_product_launch_date date_inputs" value="<?php echo esc_html(date('Y-m-d', strtotime($item_data->sp_product_launch_date))); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_product_replenishment_date', $columns)) {echo esc_attr('display: none;');}?>"><input type="date" class="sp_product_replenishment_date date_inputs" value="<?php echo esc_html(date('Y-m-d', strtotime($item_data->sp_product_replenishment_date))); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_inbound_stock_limit', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_inbound_stock_limit smaller-input" value="<?php echo esc_html($item_data->sp_inbound_stock_limit); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_on_hold', $columns)) {echo esc_attr('display: none;');}?>"><select class="sp_on_hold smaller-input" value="<?php echo esc_html($item_data->sp_on_hold); ?>"><option value="1" <?php if('value == 1') {echo 'selected';} ?>>Yes</option><option value="0" <?php if('value == 0') {echo 'selected';} ?> selected>No</option></select></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_primary_category', $columns)) {echo esc_attr('display: none;');}?>">
                    <select class="sp_primary_category">
                        <?php foreach($categories as $category) { ?>
                            <option value="<?php echo esc_html($category->term_id); ?>" <?php if($category->term_id == $item_data->sp_primary_category) {echo 'selected';}?>><?php echo esc_html($category->name); ?></option>
                        <?php } ?>
                    </select>
                </th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_size_packs', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_size_packs smaller-input" value="<?php echo esc_html($item_data->sp_size_packs); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_size_pack_threshold', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_size_pack_threshold smaller-input" value="<?php echo esc_html($item_data->sp_size_pack_threshold); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_sku_pack_size', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_sku_pack_size smaller-input" value="<?php echo esc_html($item_data->sp_sku_pack_size); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_product_id', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_supplier_product_id smaller-input" value="<?php echo esc_html($item_data->sp_supplier_product_id); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_supplier_product_reference', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_supplier_product_reference smaller-input" value="<?php echo esc_html($item_data->sp_supplier_product_reference); ?>"></th>
                <th class="column-cost num" scope="col" style="<?php if(!in_array('sp_cost', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_cost smaller-input" value="<?php echo esc_html($item_data->sp_cost); ?>"></th>
                <th class="column-stock-value num" scope="col" style="<?php if(!in_array('sp_stock_value', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="smaller-input" value="<?php echo esc_html($item_data->sp_stock_value); ?>" readonly></th>
                <th class="column-markup num" scope="col" style="<?php if(!in_array('sp_mark_up', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_mark_up smaller-input" value="<?php echo esc_html($item_data->sp_mark_up); ?>" readonly></th>
                <th class="column-markup num" scope="col" style="<?php if(!in_array('sp_margin', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_margin smaller-input" value="<?php echo esc_html($item_data->sp_margin); ?>" readonly></th>
                <th class="column-markup num" scope="col" style="<?php if(!in_array('sp_margin_tax', $columns)) {echo esc_attr('display: none;');}?>"><input type="number" class="sp_margin_tax smaller-input" value="<?php echo esc_html($item_data->sp_margin_tax); ?>" readonly></th>
                <th class="column-price num" scope="col" style="<?php if(!in_array('product_price', $columns)) {echo esc_attr('display: none;');}?>"><?php echo esc_html($product->get_price().get_woocommerce_currency_symbol()); ?></th>
                <th class="column-stock num" scope="col" style="<?php if(!in_array('product_stock', $columns)) {echo esc_attr('display: none;');}?>"><?php echo esc_html($product->get_stock_quantity()); ?></th>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <div class="qa-settings-save-footer">
        <button id="save-qa-bulk-sp" class="button action">Save Page Products</button>
        <div class="qa-bulk-pagination">
            <button id="prev-bulk-page-sp" data-page="<?php echo esc_html($page-1); ?>" class="button action" <?php if($page == 1) {echo esc_html('disabled');} ?>><</button>
            <span><?php echo esc_html($page); ?> of <?php echo esc_html($products->max_num_pages); ?></span>
            <button id="prev-bulk-page-sp" data-page="<?php echo esc_html($page+1); ?>" class="button action" <?php if($page == $products->max_num_pages) {echo esc_html('disabled');} ?>>></button>
        </div>
    </div>
</div>