<?php
require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';

$categories    = sp_get_categories();

global $wpdb;

$category_id = isset( $_GET['category'] ) && is_numeric( $_GET['category'] ) ? (int) $_GET['category'] : false;

$categories    = sp_get_categories();
$products_data = sp_get_products_data_home( $category_id > 0 ? $category_id : implode( ',', array_keys( $categories ) ) );


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

if ( $_POST && isset( $_POST['action'] ) ) {
	if ( $_POST['action'] == 'download_xlsx' ) {
		/**
		 * Make sample file to prepare import
		 */

		$sample_data[] = [
			'product_id',
			'name',
			'supplier_name',
			'backorders',
			'ideal_stock',
			'current_stock',
			'inbound_stock',
			'order_proposal_units',
		];

		foreach ( $products_data as $product ) {
			$sample_data[] = [
				'product_id' => $product['term_id'],
				'name' => $product['name'],
				'supplier_name' => $product['supplier_name'],
				'backorders' => isset($backorders_stats['products'][$product['term_id']]) ? $backorders_stats['products'][$product['term_id']] : 0,
				'ideal_stock' => $product['ideal_stock'],
				'current_stock' => $product['current_stock'],
				'inbound_stock' => $product['inbound_stock'],
				'order_proposal_units' => $product['order_proposal_units'],
			];
		}

		$writer = new XLSXWriter();
		$writer->writeSheet( $sample_data );

		$file_name = sanitize_file_name( $_SERVER['HTTP_HOST'] . '_Products_Proposals_Data_' . time() . '.xlsx' );
		$file_path = SP_PLUGIN_DIR_PATH . $file_name;
		$writer->writeToFile( $file_path );
		header( "Location: " . str_replace( 'pages', '', plugin_dir_url( __FILE__ ) . $file_name ) );
		exit;
	}
}
?>
<style>

    .wp-core-ui select {
        max-width: 100%;
        background-position: right 15px top 55%;
        padding-left: 15px;
        font-weight: 500;
    }

    .label-show {
        margin-bottom: 0;
    }

    .entires-select select:hover {
        color: #000 !important;
    }

    .all-warehouses:focus, .all-warehouses:focus-within {
      color: #000 !important;
      opacity: 1 !important;
    }

    input[type=number] {
          color: #131313 !important;
    border-color: #A5A5A5 !important;
    opacity: 1 !important;
    font-size: 14px !important;
    font-family: 'Lato' !important;
    font-weight: 400 !important;
        }
</style>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
        <?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">
            <?php include __DIR__ . '/../' . "page_header.php"; ?>

                <?php include SP_PLUGIN_DIR_PATH . "pages/header_js.php"; ?>
                <!-- container opened -->
                <div class="ml-40 mr-40">
                <h2 class="purchase-or-title"><?php echo esc_html(__( 'Inventory', QA_MAIN_DOMAIN )); ?></h2>
                        <span class='purchase-or-subtitle'><?php echo esc_html(__( 'Manage, analyse and control your current and incoming stock, backorders and safety stock.', QA_MAIN_DOMAIN )); ?></span>
                        <div class="d-flex nav-link-line" style="margin-top: 40px;">
                        <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_inventory' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_inventory')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Stock Perfomance', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_manage_store' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_manage_store')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Manage Inventory', QA_MAIN_DOMAIN)); ?></span></a>
                          <!-- <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Stock Detail', QA_MAIN_DOMAIN)); ?></span></a> -->

                        </div>
                    <?php do_action('after_page_header'); ?>
                    <p class="mg-b-20"></p>
                    <div>
                        <div class="card-body" style="padding-left: 0 !important">
                            <div class="d-flex justify-content-between ml-2 mb-5">
                                <div class="d-flex align-items-center">
                                    <span style="margin-right: 5px">Show</span>
                                    <select class="entires-select" id="select-proposals-rows">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                    <span style="margin-left: 5px;">entries</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="mr-2">Search:</span>
                                    <input type="search" class="orders-search" id="search-name-proposal">
                                </div>
                            </div>
                            <div class="d-flex" style="gap: 10%">
                                <div class="w-21">
                                    <label class="label-show d-flex flex-column">
                                        Show:
                                        <select class="all-warehouses" id="select-proposals-suppliers">
                                            <option value="">All Suppliers</option>
							                <?php foreach ( QAMain_Core::get_suppliers() as $tmp_supplier ) { ?>
                                                <option value="<?php echo  esc_attr( $tmp_supplier['supplier_name'] ); ?>" <?php echo esc_attr( ( isset( $_GET['supplier'] ) && sanitize_text_field($_GET['supplier']) == $tmp_supplier['supplier_name'] ) ? 'selected' : '' ); ?>><?php echo  esc_html( $tmp_supplier['supplier_name'] ); ?></option>
							                <?php } ?>
                                        </select>
                                    </label>
                                </div>
                                <div class="w-21">
                                    <label class="label-show d-flex flex-column">
                                        Show:
                                        <select class="all-warehouses" id="select-proposal-category">
                                            <option value="">All Categories</option>
							                <?php
							                foreach($categories as $cat_id => $category) {
								                ?>
                                                <option value="<?php echo esc_attr($cat_id); ?>"><?php echo esc_html($category); ?></option>
							                <?php } ?>
                                        </select>
                                    </label>
                                </div>
                                <div class="download-btn-block">
                                <a onclick="window.location.reload()" class="refresh-link">
                                  <img src="<?php echo esc_url(plugin_dir_url(__FILE__)); ?>../assets/img/refresh.svg" class="refresh" alt="refresh">
                                </a>
                                    <form action="" method="POST" style="width: 100%;">
                                        <p>
                                            <input type="hidden" required name="action" value="download_xlsx"/>
                                            <input type="hidden" required name="redirect" value="<?php echo  esc_attr( $_SERVER['REQUEST_URI'] ); ?>"/>
                                            <button type="submit" class="old-des-btn">Download xls</button>
                                        </p>
                                    </form>
                                </div>
                            </div>
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
                                    <td class="manage-tab-title" style="width: 115px">Override Inc.Stocks</td>
                                    <td class="manage-tab-title" style="width: 115px">Order Proposal</td>
                                </tr>
				                <?php

				                foreach(array_slice($products_data, 0, 10) as $item => $product) :

					                $product_item = wc_get_product($product['term_id']);
					                ?>
                                    <tr class="proposal-item" data-product-id="<?php echo esc_attr($product['term_id']); ?>">
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
                                            <input class="manage-tab-num ideal-stock-num" type="number" value="<?php echo esc_attr($product['ideal_stock']); ?>" readonly>
                                        </td>
                                        <td class="manage-tab-title" style="width: 115px">
                                            <input class="manage-tab-num proposal-current-stock" type="number" value="<?php echo esc_attr($product['current_stock']); ?>">
                                        </td>
                                        <td class="manage-tab-title" style="width: 115px">
                                            <input class="manage-tab-num" type="number" value="<?php if(isset($backorders_stats['products'][$product['term_id']])) { echo esc_attr($backorders_stats['products'][$product['term_id']]); } else { echo esc_attr(0); } ?>" readonly>
                                        </td>
                                        <td class="manage-tab-title" style="width: 115px">
                                            <input class="manage-tab-num proposal-inbound-stock" type="number" value="<?php echo esc_attr($product['inbound_stock']); ?>">
                                        </td>
                                        <td class="manage-tab-title" style="width: 115px">
                                            <input class="manage-tab-num proposal-inbound-stock-override" type="checkbox" value="yes" <?php if(get_post_meta( $product['term_id'], 'inbound_stock_override', true ) == 'yes') { echo esc_attr('checked'); } ?>>
                                        </td>
                                        <td class="manage-tab-title" style="width: 115px">
                                            <input readonly class="manage-tab-num proposal-order-proposal-units" type="number" value="<?php echo esc_attr($product['order_proposal_units']); ?>">
                                        </td>
                                    </tr>
				                <?php endforeach; ?>
                            </table>
                            <button type="button" class="save-btn" id="save-proposal-table">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php include __DIR__ . '/../' . "popups.php"; ?>
          </div>
    </div>
</div>
</div>
</div>

<?php require_once __DIR__ . '/../' . 'footer.php';
