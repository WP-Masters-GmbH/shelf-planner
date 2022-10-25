<?php
if ( ! empty( $_POST ) && isset( $_POST['save-store-settings'] ) ) {
	// Sanitized with json_decode
	$js_category_mapping = stripslashes( $_POST['category_mapping'] );
	$category_mapping    = @json_decode( $js_category_mapping, true );
	if ( is_array( $category_mapping ) && ! empty( $category_mapping ) ) {
		update_option( 'sp.category.mapping', $js_category_mapping );
	}
}

// Sanitized with json_decode
$js_category_mapping = get_option( 'sp.category.mapping', '{}' );
$category_mapping    = json_decode( $js_category_mapping, true );

$tmpl_industry_column = '<div class="column column-done" style="" ondrop="drop(event)" ondragover="allowDrop(event)" data-id="%s"><h5>%s</h5>%s</div>';
$tmpl_industry_card   = '<article class="js-card" draggable="true" style="text-align: center; border-radius: 5px;" ondragstart="drag(event)" data-id="%s">%s</article>';
$industry_columns     = $industry_cards = array();

$industry_list = \QAMain_Core::get_industry_categories();
array_pop( $industry_list );
foreach ( $industry_list as $each_id => $each_industry ) {
	$industry_columns[ $each_id ] = sprintf( $tmpl_industry_column, esc_attr( $each_id ), esc_html( $each_industry ), '%s' );
	$industry_cards[ $each_id ]   = array();
}

$tmp_cats = \QAMain_Core::get_all_categories();
foreach ( $tmp_cats as $each_id => $each_category ) {
	if ( array_key_exists( $each_id, $category_mapping ) ) {
		$industry_cards[ $category_mapping[ $each_id ] ][] = sprintf( $tmpl_industry_card, esc_attr( $each_id ), esc_html( $each_category ) );
		unset( $tmp_cats[ $each_id ] );
	}
}

foreach ( $industry_columns as $each_id => &$each_column ) {
	$each_industry = empty( $industry_cards[ $each_id ] ) ? '' : implode( "\n", $industry_cards[ $each_id ] ); // Escaped in previous foreach
	$each_column   = sprintf( $each_column, $each_industry );
}

//var_dump($tmpl_industry_column); die;

require_once __DIR__ . '/admin_page_header.php';
require_once __DIR__ . '/../' . 'header.php';

?>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
		<?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">
                <!-- container opened -->
                <div class="container" style="max-width: 100%;">
                    <style>
                        .sp-settings-form p {
                            margin-top: 3%;
                            font-size: inherit;
                        }

                        .sphd-p {
                            font-size: 16px;
                        }

                        .board {
                            font-size: 12px;

                            display: flex;
                            flex-wrap: wrap;

                            /*flex-basis: available;*/

                            align-items: stretch;
                            align-content: flex-start;

                            overflow-y: hidden;
                            width: 1200px;

                            margin-top: 50px;
                            margin-left: -40px;

                            height: 100%;
                        }

                        .column {
                            padding: 10px;
                            background: #ebebeb;
                            border: 1px dotted #bbb;
                            border-collapse: collapse !important;
                            min-width: 221.6px;

                            display: inline;
                            position: relative;

                            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
                            margin: 5px;
                            -webkit-box-shadow: 3px 3px 10px 3px #eee;
                            box-shadow: 3px 3px 10px 3px #eee;
                        }

                        .js-card {
                            background: #f7f7f7;
                            padding: 10px;
                            margin-bottom: 10px;
                            border-radius: 3px;
                            cursor: pointer;
                            width: 200px;
                            /*height: 60px;*/
                            cursor: grab;
                            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
                        }

                        .js-card:active {
                            cursor: grabbing;
                        }

                        .js-card.dragging {
                            opacity: .5;
                            transform: scale(.8);
                        }

                        .column h5 {
                            font-size: 12px;
                            font-weight: bold;
                            text-align: center;
                        }

                        .column.column-todo h2 {

                        }

                        .column.column-ip h2 {
                            background: #F39C12;
                        }

                        .column.column-ip {
                            margin: 0 20px;
                        }

                        .column.drop {
                            border: 2px dashed #FFF;
                        }

                        .column.drop article {
                            pointer-events: none;
                        }

                        .js-card:last-child {
                            margin-bottom: 0;
                        }
                    </style>
                    <h2><?php echo esc_html(__( 'Settings', QA_MAIN_DOMAIN )); ?></h2>
                    <?php do_action( 'after_page_header' ); ?>
                    <?php if ( display_admin_part() == true ) include SP_PLUGIN_DIR_PATH . "pages/settings/tabs.php" ?>
                    <div class="card">
                        <form class="card-body" method="post">
                            <div>
                                <h4><?php echo esc_html( __( 'Category Mapping', QA_MAIN_DOMAIN ) ); ?></h4>
                                <p class="mg-b-20"></p>
                                <p><b><?php echo esc_html( __( 'To understand the patterns and performance of your products, we need to link your store’s categories to predefined segments and industries.', QA_MAIN_DOMAIN ) ); ?></b></p>
                                <p><b><?php echo esc_html( __( 'Please map your store’s categories to the segment that fits best, so we can create the perfect forecast and reports for your products.', QA_MAIN_DOMAIN ) ); ?></b></p>
                                <p><b><?php echo esc_html( __( "If you are unsure about what segment to assign a category to, or if you don’t find it in the list below, you can assign it to 'Other' and we will match it for you.", QA_MAIN_DOMAIN ) ); ?></b></p>
                                <input type="submit" class="btn btn-sm
                                    btn-success" value="Save Settings" name="save-store-settings"> <input type="hidden" id="category-mapping" name="category_mapping" value="">
                            </div>
                            <main class="board">
                                <div class="column column-todo" ondrop="drop(event)" ondragover="allowDrop(event)" data-id="-1">
                                    <h5>Other</h5>
									<?php
									foreach ( $tmp_cats as $tmp_cat_id => $tmp_cat_title ): ?>
                                        <article class="js-card" draggable="true" ondragstart="drag(event)" style="text-align: center; border-radius: 5px" data-id="<?php echo esc_attr( (int) $tmp_cat_id ); ?>"><?php echo  esc_html( $tmp_cat_title ) ?></article>
									<?php endforeach; ?>
                                </div>
								<?php echo  implode( "\n", $industry_columns ) ?>
                            </main>
                            <script>
                                let category_mapping = <?php echo  json_encode( $category_mapping ) ?>;
                            </script>
                            <input style="margin-top: 2em" type="submit" class="btn btn-sm btn-success" value="Save Settings" name="save-store-settings"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div></div>