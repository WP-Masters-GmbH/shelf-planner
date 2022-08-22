jQuery(document).ready(function($) {

    var bulk_page = 1;
    var bulk_search = "";

    $('#import-csv').on('change', function () {
        var formData = new FormData();
        for(var i = 0; i < this.files.length; i++) {
            formData.append('file', this.files[i]);
            formData.append('action', 'import_qa_costs');

            $.ajax({
                url: admin.ajaxurl,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response.status === 'true') {
                        alert("Products Cost is Updated!");
                    }
                }
            })
        }
    });

    $("body").on("click","#import-file",function() {
        $('#import-csv').trigger('click');
    });

    $("body").on("click","#prev-bulk-page, #prev-bulk-page",function() {
        bulk_page = $(this).data('page');
        get_products();
    });

    $("body").on("input","#bulk-search",function() {
        bulk_search = $(this).val();
        get_products();
    });

    function get_products()
    {
        $.ajax({
            url: admin.ajaxurl,
            data: {
                'action': 'get_page_bulk_products',
                'page': bulk_page,
                'bulk_search': bulk_search,
                'nonce': admin.nonce
            },
            type:'POST',
            dataType: 'json',
            success:function(response) {
                if(response.status === 'true') {
                    $('.qa-bulk-edit-costs').replaceWith(response.html);
                }
            }
        });
    }

    $("body").on("click","#save-qa-bulk",function() {

        var prices_data = [];
        $(".new-price-set").each(function() {
            var product_id = $(this).data('product-id');
            var new_price = parseInt($(this).val());

            if(new_price > 0) {
                prices_data.push({
                    'product_id': product_id,
                    'new_price': new_price
                });
            }
        });

        if(prices_data.length > 0) {
            $.ajax({
                url: admin.ajaxurl,
                data: {
                    'action': 'save_bulk_products_settings_dev',
                    'page': bulk_page,
                    'prices_data': prices_data,
                    'bulk_search': bulk_search,
                    'nonce': admin.nonce
                },
                type:'POST',
                dataType: 'json',
                success:function(response) {
                    if(response.status === 'true') {
                        $('.qa-bulk-edit-costs').replaceWith(response.html);
                    }
                }
            });
        }
    });
});