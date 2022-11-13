jQuery(document).ready(function($) {

    var bulk_page = 1;
    var bulk_search = "";
    var columns = [];


    $("body").on("change input","#select-proposal-category, #select-proposals-suppliers, #select-proposals-rows, #search-name-proposal",function() {

        var category = $('#select-proposal-category').val();
        var suppliers = $('#select-proposals-suppliers').val();
        var max_rows = $('#select-proposals-rows').val();
        var search = $('#search-name-proposal').val()

        $.ajax({
            url: admin.ajaxurl,
            data: {
                'action': 'proposal_filter_data',
                'category': category,
                'suppliers': suppliers,
                'max_rows': max_rows,
                'search': search,
                'nonce': admin.nonce
            },
            type:'POST',
            dataType: 'json',
            success:function(response) {
                if(response.status === 'true') {
                    $('#proposal-table').replaceWith(response.html);
                }
            }
        });
    });

    $("body").on("click","#prev-bulk-page-sp, #prev-bulk-page-sp",function() {
        bulk_page = $(this).data('page');
        get_products_sp();
    });

    $("body").on("click","#toggle-select-rows-home",function() {
        $('.select-rows-leaderboards').toggleClass('shows');
    });

    $("body").on("change",".first-table-select-column input",function() {
        var column = $(this).val();
        table.toggleColumn(column);
    });

    $("body").on("change",".two-table-select-column input",function() {
        var column = $(this).val();
        table2.toggleColumn(column);
    });

    $("body").on("input","#bulk-search-sp",function() {
        bulk_search = $(this).val();
        get_products_sp();
    });

    $("body").on("change",".filters-enabled input",function() {
        get_products_sp();
    });

    $("body").on("change","#replenish-stat-selector",function() {
        $.ajax({
            url: admin.ajaxurl,
            data: {
                'action': 'replenish_stat_select',
                'weeks': $(this).val(),
                'nonce': admin.nonce
            },
            type:'POST',
            dataType: 'json',
            success:function(response) {
                if(response.status === 'true') {
                    $('#replenish-table-stat').replaceWith(response.html);
                }
            }
        });
    });

    $("body").on("change","#inspector-select-control-2",function() {
        $.ajax({
            url: admin.ajaxurl,
            data: {
                'action': 'get_leaderboards_rows',
                'max_count': $(this).val(),
                'nonce': admin.nonce
            },
            type:'POST',
            dataType: 'json',
            success:function(response) {
                if(response.status === 'true') {
                    $('#leaderboards-rows-home').html(response.html);
                }
            }
        });
    });

    function get_products_sp()
    {
        columns = []
        $(".filters-enabled input").each(function() {
            if($(this).is(":checked")) {
                var value = $(this).val();
                columns.push(value);
            }
        });

        $.ajax({
            url: admin.ajaxurl,
            data: {
                'action': 'get_page_bulk_products_sp',
                'page': bulk_page,
                'bulk_search': bulk_search,
                'columns': columns,
                'nonce': admin.nonce
            },
            type:'POST',
            dataType: 'json',
            success:function(response) {
                if(response.status === 'true') {
                    $('.bulk-data-table').replaceWith(response.html);
                }
            }
        });
    }



    $("body").on("click","#save-proposal-table",function() {

        var products_data = [];
        $(".proposal-item").each(function() {
            var product_id = $(this).data('product-id');

            products_data.push({
                'product_id': product_id,
                'current_stock': $(this).find('.proposal-current-stock').val(),
                'inbound_stock': $(this).find('.proposal-inbound-stock').val(),
                'inbound_stock_override': $(this).find('.proposal-inbound-stock-override').is(':checked') ? 'yes' : 'no',
                'order_proposal_units': $(this).find('.proposal-order-proposal-units').val()
            })
        });

        if(products_data.length > 0) {
            $.ajax({
                url: admin.ajaxurl,
                data: {
                    'action': 'save_proposals_table',
                    'products_data': products_data,
                    'nonce': admin.nonce
                },
                type:'POST',
                dataType: 'json',
                success:function(response) {
                    if(response.status === 'true') {
                        alert('Data is saved!');
                    }
                }
            });
        }
    });



    $("body").on("click","#save-qa-bulk-sp",function() {

        columns = []
        $(".filters-enabled input").each(function() {
            if($(this).is(":checked")) {
                var value = $(this).val();
                columns.push(value);
            }
        });

        var products_data = [];
        $(".bulk-product-item").each(function() {
            var product_id = $(this).data('product-id');

            products_data.push({
                'product_id': product_id,
                'sp_activate_replenishment': $(this).find('.sp_activate_replenishment').val(),
                'sp_supplier_id': $(this).find('.sp_supplier_id').val(),
                'sp_weeks_of_stock': $(this).find('.sp_weeks_of_stock').val(),
                'sp_lead_time': $(this).find('.sp_lead_time').val(),
                'sp_product_launch_date': $(this).find('.sp_product_launch_date').val(),
                'sp_product_replenishment_date': $(this).find('.sp_product_replenishment_date').val(),
                'sp_inbound_stock_limit': $(this).find('.sp_inbound_stock_limit').val(),
                'sp_on_hold': $(this).find('.sp_on_hold').val(),
                'sp_primary_category': $(this).find('.sp_primary_category').val(),
                'sp_size_packs': $(this).find('.sp_size_packs').val(),
                'sp_size_pack_threshold': $(this).find('.sp_size_pack_threshold').val(),
                'sp_sku_pack_size': $(this).find('.sp_sku_pack_size').val(),
                'sp_supplier_product_id': $(this).find('.sp_supplier_product_id').val(),
                'sp_supplier_product_reference': $(this).find('.sp_supplier_product_reference').val(),
                'sp_cost': $(this).find('.sp_cost').val()
            })
        });

        if(products_data.length > 0) {
            $.ajax({
                url: admin.ajaxurl,
                data: {
                    'action': 'save_bulk_products_settings_sp',
                    'page': bulk_page,
                    'columns': columns,
                    'products_data': products_data,
                    'bulk_search': bulk_search,
                    'nonce': admin.nonce
                },
                type:'POST',
                dataType: 'json',
                success:function(response) {
                    if(response.status === 'true') {
                        $('.bulk-data-table').replaceWith(response.html);
                    }
                }
            });
        }
    });

    $.protip();
});