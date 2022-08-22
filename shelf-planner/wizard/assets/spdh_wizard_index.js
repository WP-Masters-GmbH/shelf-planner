function save_wizard_data(curr_step, next_step) {

    if(jQuery('[name="sp_countries_list"]').length){
        if(jQuery('[name="sp_countries_list"]').val() == 'XX'){
            alert('Please select country');
            return false;
        }
    }

    let postData = {
        action: 'sphd_save_wizard_data',
        curr_step: curr_step,
        next_step: next_step,
    };
    let checked = false;

    jQuery('#id-wizard-answers').find('*').each(function () {
        let name = jQuery(this).attr('name');
        if (name) {
            switch (jQuery(this).attr('type')) {
                case 'checkbox':
                    checked = jQuery(this).prop('checked');
                    if (name === 'force_zero_price_products') {
                        postData[name] = checked ? '1' : '0';
                    } else if (checked) {
                        postData[name] = jQuery(this).attr('data-id');
                    }
                    break;
                case 'radio':
                    if (jQuery(this).prop('checked')) {
                        postData[name] = jQuery(this).val();
                    }
                    break;
                default:
                    postData[name] = jQuery(this).val();
            }
        }
    });
    jQuery.post(ajaxurl, postData, function (response) {
        let json = JSON.parse(response);
        window.location.href = json['redirect_url'];
    });
}

function complete_wizard() {
    let postData = {
        action: 'sphd_complete_wizard',
    };
    jQuery.post(ajaxurl, postData, function (response) {
        let json = JSON.parse(response);
        if (json.isOk) {
            window.location.href = json['redirect_url'];
        } else {
            alert(json.error);
        }
    });
}
