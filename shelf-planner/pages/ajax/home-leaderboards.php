<?php
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
?>

<div class="woocommerce-card woocommerce-analytics__card woocommerce-table has-action">
	<div class="woocommerce-card__header">
		<div class="woocommerce-card__title-wrapper">
			<h2 style="opacity: 0.9; color: #000;" class="woocommerce-card__title woocommerce-card__header-item fs-14 lh-30 fw-700">Top Categories - Lost Sales</h2>
		</div>
		<div class="woocommerce-card__action woocommerce-card__header-item"></div>
	</div>
    <div class="woocommerce-card__body">
        <div class="woocommerce-table__table" aria-hidden="false" aria-labelledby="caption-7" role="group">
            <table>
                <caption id="caption-7" class="woocommerce-table__caption screen-reader-text">Top Categories - Lost Sales</caption>
                <tbody>
                <tr>
                    <th role="columnheader" scope="col" class="woocommerce-table__header is-left-aligned"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Category</span></th>
                    <th role="columnheader" scope="col" class="woocommerce-table__header"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Ideal Stock</span></th>
                    <th role="columnheader" scope="col" class="woocommerce-table__header"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Lost Sales (Value)</span></th>
                </tr>
				<?php

				foreach($categories_data as &$item) {
					$item['order_value_retail'] = str_replace('.00', '', str_replace(',', '', $item['order_value_retail'])) * $item['order_proposal_units'];
				}

				usort($categories_data, function($a, $b) {
					return $b['order_value_retail'] - $a['order_value_retail'];
				});

				foreach($categories_data as $number => $item) { if($number == $max_count) { break; } ?>
                    <tr>
                        <th scope="row" class="woocommerce-table__item is-left-aligned">
                            <div><a title="To Category Page" href=<?php echo esc_attr($item['cat_url']) ?>><?php echo esc_html($item['name']); ?></a></div>
                        </th>
                        <td class="woocommerce-table__item">
                            <div><?php echo esc_html($item['ideal_stock']); ?></div>
                        </td>
                        <td class="woocommerce-table__item">
                            <div><?php echo esc_html(get_woocommerce_currency_symbol().$item['order_value_retail']); ?></div>
                        </td>
                    </tr>
				<?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="woocommerce-card woocommerce-analytics__card woocommerce-table has-action">
	<div class="woocommerce-card__header">
		<div class="woocommerce-card__title-wrapper">
			<h2 style="opacity: 0.9; color: #000;" class="woocommerce-card__title woocommerce-card__header-item fs-14 lh-30 fw-700">Top Products - Lost Sales</h2>
		</div>
		<div class="woocommerce-card__action woocommerce-card__header-item"></div>
	</div>
    <div class="woocommerce-card__body">
        <div class="woocommerce-table__table" aria-hidden="false" aria-labelledby="caption-7" role="group">
            <table>
                <caption id="caption-7" class="woocommerce-table__caption screen-reader-text">Top Products - Lost Sales</caption>
                <tbody>
                <tr>
                    <th role="columnheader" scope="col" class="woocommerce-table__header is-left-aligned"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Product</span></th>
                    <th role="columnheader" scope="col" class="woocommerce-table__header"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Lost Sales (Units)</span></th>
                    <th role="columnheader" scope="col" class="woocommerce-table__header"><span class="fs-14 lh-24 fw-900 op-80" aria-hidden="false">Lost Sales (Value)</span></th>
                </tr>
				<?php

				foreach($products_data as &$item) {
					$item['total_lost_price'] = $item['order_proposal_units'] * $item['order_value_price'];
				}

				usort($products_data, function($a, $b) {
					return $b['total_lost_price'] - $a['total_lost_price'];
				});

				foreach($products_data as $number => $item) { if($number == $max_count) { break; } ?>
                    <tr>
                        <th scope="row" class="woocommerce-table__item is-left-aligned">
                            <div><a title="To Product Page" href=<?php echo esc_attr($item['cat_url']); ?>><?php echo esc_html($item['name']) ?></a></div>
                        </th>
                        <td class="woocommerce-table__item">
                            <div><?php echo esc_html($item['order_proposal_units']); ?></div>
                        </td>
                        <td class="woocommerce-table__item">
                            <div><?php echo esc_html(get_woocommerce_currency_symbol().$item['total_lost_price']); ?></div>
                        </td>
                    </tr>
				<?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
