window.onload = function () {
    const el = document.querySelector('[aria-label="Deactivate Shelf Planner"]');

    if (el) {
        el.addEventListener('click', function (event) {

            event.preventDefault();
            const href = el.getAttribute('href');

            if (!href) {
                return false;
            }

            if (confirm('Keep data and settings?')) {
                window.location.href = href;
            } else {
                jQuery.post(ajaxurl, {action: 'sphd_purge_data'}, function () {
                    window.location.href = href;
                });
            }
        });
    }
}
