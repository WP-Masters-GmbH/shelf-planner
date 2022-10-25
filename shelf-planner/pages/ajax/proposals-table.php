<?php
	$category_id = $category != '' ? $category : 0;
	$categories    = sp_get_categories();
	$products_data = sp_get_products_data( $category_id > 0 ? $category_id : implode( ',', array_keys( $categories ) ) );
?>

<table class="manage-tab" id="proposal-table" style="margin-top: 50px">
	<tr>
		<td class="manage-tab-title" style="width: 80px;">ID</td>
		<td class="manage-tab-title" style="width: 80px;">Image</td>
		<td class="manage-tab-title" style="width: 80px;">SKU</td>
		<td class="manage-tab-title" style="width: 170px;">Name</td>
		<td class="manage-tab-title" style="width: 115px">Supplier</td>
		<td class="manage-tab-title" style="width: 170px">Ideal Stock</td>
		<td class="manage-tab-title" style="width: 115px">Current Stock</td>
		<td class="manage-tab-title" style="width: 115px">Backorders</td>
		<td class="manage-tab-title" style="width: 115px">Incoming Stock</td>
		<td class="manage-tab-title" style="width: 115px">Order Proposal</td>
	</tr>
	<?php

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

	$count = 0;
	foreach($products_data as $item => $product) :

		if($suppliers != '' && $product['supplier_name'] != $suppliers) {
			continue;
		}

		if($search != '' && strpos(strtolower($product['name']), strtolower($search)) === false) {
			continue;
		}

		$product_cats_ids = wc_get_product_term_ids( $product['term_id'], 'product_cat' );

		if($category != '' && !in_array($category, $product_cats_ids)) {
			continue;
		}

		$product_item = wc_get_product($product['term_id']);
		?>
		<tr>
			<td class="manage-tab-title" style="width: 80px; color: #131313; font-weight: 400;">
				<?php echo esc_html($product['term_id']); ?>
			</td>
			<td class="manage-tab-title" style="width: 80px;">
				<?php if(wp_get_attachment_url( $product_item->get_image_id() )) { ?>
					<img src="<?php echo wp_get_attachment_url( $product_item->get_image_id() ); ?>" />
				<?php } else { ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="21.015" height="24.017" viewBox="0 0 21.015 24.017"><path d="M20.61,16.994c-.906-.974-2.6-2.439-2.6-7.237a7.407,7.407,0,0,0-6-7.278V1.5a1.5,1.5,0,1,0-3,0v.978a7.407,7.407,0,0,0-6,7.278c0,4.8-1.7,6.264-2.6,7.237A1.466,1.466,0,0,0,0,18.013a1.5,1.5,0,0,0,1.506,1.5h18a1.5,1.5,0,0,0,1.506-1.5,1.465,1.465,0,0,0-.4-1.018Zm-17.443.268c1-1.312,2.084-3.487,2.089-7.478,0-.009,0-.018,0-.027a5.254,5.254,0,1,1,10.507,0c0,.009,0,.018,0,.027.005,3.992,1.093,6.166,2.089,7.478Zm7.34,6.755a3,3,0,0,0,3-3h-6A3,3,0,0,0,10.507,24.017Z" transform="translate(0.001)" fill="rgba(0,0,0,0.6)"/></svg>
				<?php } ?>
			</td>
			<td class="manage-tab-title" style="width: 80px; color: #131313; font-weight: 400;">
				<?php echo esc_html(get_post_meta( $product['term_id'], '_sku', true )); ?>
			</td>
			<td class="manage-tab-title" style="width: 170px; color: #874C5F;">
				<?php echo esc_html($product['name']); ?>
			</td>
			<td class="manage-tab-title" style="width: 115px; color: #874C5F;">
				<?php echo esc_html($product['supplier_name']); ?>
			</td>
			<td class="manage-tab-title" style="width: 170px">
				<input class="manage-tab-num" type="number" value="<?php echo esc_attr($product['ideal_stock']); ?>" readonly>
			</td>
			<td class="manage-tab-title" style="width: 115px">
				<input class="manage-tab-num" type="number" value="<?php echo esc_attr($product['current_stock']); ?>">
			</td>
			<td class="manage-tab-title" style="width: 115px">
				<input readonly class="manage-tab-num" type="number" value="<?php if(isset($backorders_stats['products'][$product['term_id']])) { echo esc_attr($backorders_stats['products'][$product['term_id']]); } else { echo esc_attr(0); } ?>" style="color:#131313 !important; border-color: #A5A5A5 !important">
			</td>
			<td class="manage-tab-title" style="width: 115px">
				<input class="manage-tab-num" type="number" value="<?php echo esc_attr($product['inbound_stock']); ?>" style="color:#131313 !important; border-color: #A5A5A5 !important">
			</td>
			<td class="manage-tab-title" style="width: 115px">
				<input readonly class="manage-tab-num" type="number" value="<?php echo esc_attr($product['order_proposal_units']); ?>" style="margin-left: 10px;">
			</td>
		</tr>
	<?php $count++; if($count == $max_rows) { break; } endforeach; ?>
</table>
