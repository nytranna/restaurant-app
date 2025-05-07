naja.initialize();

$(document).ready(function () {

    //------------- TRANSLATE DATATABLES --------------------
    $('.dataTable').DataTable({
        language: {
            info: 'Zobrazení stránky _PAGE_ z _PAGES_',
            infoEmpty: 'Žádné dostupné záznamy',
            infoFiltered: '(filtered from _MAX_ total records)',
            lengthMenu: 'Zobraz _MENU_ záznamů na stránku',
            zeroRecords: 'Nic nenalezeno',
            search: "Hledat:"
        }
    });
    //------------- end TRANSLATE DATATABLES --------------------


    //------------- REORDER CATEGORIES ----------------------
    var datatable = $('.dataTable-reorder').DataTable({
        rowReorder: true,
        language: {
            info: 'Zobrazení stránky _PAGE_ z _PAGES_',
            infoEmpty: 'Žádné dostupné záznamy',
            infoFiltered: '(filtered from _MAX_ total records)',
            lengthMenu: 'Zobraz _MENU_ záznamů na stránku',
            zeroRecords: 'Nic nenalezeno',
            search: "Hledat:"
        },
        order: [[0, 'asc']],
        columnDefs: [
            {orderable: false, targets: '_all'},
            {orderable: false, targets: [0], visible: false}
        ],
        stateSave: true
    });

    datatable.on('row-reorder', function (e, diff, edit) {
        var url = $(this).data('reorder_url');
        var db_table = $(this).data('db_table');

        if (!url) {
            console.error('Parameter url is missing');
        }
        if (!db_table) {
            console.error('Parameter db_table is missing');
        }

        var result = {};

        for (var i = 0, ien = diff.length; i < ien; i++) {

            var id = $(diff[i].node).data('id');

            if (diff[i].newPosition !== null && diff[i].oldPosition !== null)
            {
                result[id] = diff[i].newPosition;
            }
        }

        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data: {
                order_data: JSON.stringify(result),
                db_table: db_table
            },
            success: function (e) {
                console.log(e);
            }
        });
    });
    //------------- end REORDER CATEGORIES ----------------------


    //------------- REMOVE VARIANT (item form) ----------------------
    $(document).on('click', '.remove-variant', function (e) {
        const row = $(this).closest('.variant-row');
        if (row) {
            row.remove();
            updateVariantNumbers();
        }
    });
    //------------- end REMOVE VARIANT (item form) ----------------------

});


//------------------- NAVBAR ---------------------------
const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
const Default = {
    scrollbarTheme: 'os-theme-light',
    scrollbarAutoHide: 'leave',
    scrollbarClickScroll: true,
};
//------------------- end NAVBAR ---------------------------


//-------------------  ---------------------------
document.addEventListener('DOMContentLoaded', function () {
    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
    if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
                theme: Default.scrollbarTheme,
                autoHide: Default.scrollbarAutoHide,
                clickScroll: Default.scrollbarClickScroll,
            },
        });
    }
});
//-------------------  ---------------------------

//------------------- ITEM VARIANTS ---------------------------
naja.addEventListener('success', (event) => {
    if (event.detail.payload.add_row) {
        $('#add').after(event.detail.payload.add_row)
        updateVariantNumbers();
    }
    ;
});

function updateVariantNumbers() {
    document.querySelectorAll('.variant-row').forEach((row, index) => {
        const label = row.querySelector('.variant-number');
        if (label) {
            label.textContent = `${index + 1}.`;
        }
    });
}
//------------------- end ITEM VARIANTS ---------------------------


//------------------- NAVBAR ---------------------------
document.querySelectorAll('.nav-item > .nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
        const parent = this.closest('.nav-item');
        const submenu = parent.querySelector('.nav-treeview');
        if (submenu) {
            e.preventDefault();
            parent.classList.toggle('menu-open');
        }
    });
});
//------------------- end NAVBAR ---------------------------


//------------------- SCROLL TO RESERVATION ---------------------------
document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded');
    var trigger = document.getElementById('scroll-to-reservation');
    if (trigger && trigger.dataset.scroll === 'true') {
        var section = document.getElementById('reservation');
        if (section) {
            section.scrollIntoView({behavior: 'smooth'});
        }
    }
});
//------------------- end SCROLL TO RESERVATION ---------------------------

//------------------- GOOGLE MAP ---------------------------
function initMap() {
    var geocoder = new google.maps.Geocoder();
    var address = window.restaurantAddress;

    geocoder.geocode({ address: address }, function(results, status) {
        if (status === 'OK') {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: results[0].geometry.location,
                styles: [
                    { "elementType": "geometry", "stylers": [{ "color": "#1d2c4d" }] },
                    { "elementType": "labels.text.fill", "stylers": [{ "color": "#8ec3b9" }] },
                    { "elementType": "labels.text.stroke", "stylers": [{ "color": "#1a3646" }] },
                    { "featureType": "administrative", "elementType": "geometry", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "administrative.country", "elementType": "geometry.stroke", "stylers": [{ "color": "#4b6878" }] },
                    { "featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{ "color": "#64779e" }] },
                    { "featureType": "administrative.province", "elementType": "geometry.stroke", "stylers": [{ "color": "#4b6878" }] },
                    { "featureType": "landscape.man_made", "elementType": "geometry.stroke", "stylers": [{ "color": "#334e87" }] },
                    { "featureType": "landscape.natural", "elementType": "geometry", "stylers": [{ "color": "#023e58" }] },
                    { "featureType": "poi", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "poi", "elementType": "geometry", "stylers": [{ "color": "#283d6a" }] },
                    { "featureType": "poi", "elementType": "labels.text.fill", "stylers": [{ "color": "#6f9ba5" }] },
                    { "featureType": "poi", "elementType": "labels.text.stroke", "stylers": [{ "color": "#1d2c4d" }] },
                    { "featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{ "color": "#023e58" }] },
                    { "featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{ "color": "#3C7680" }] },
                    { "featureType": "road", "elementType": "geometry", "stylers": [{ "color": "#304a7d" }] },
                    { "featureType": "road", "elementType": "labels.icon", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "road", "elementType": "labels.text.fill", "stylers": [{ "color": "#98a5be" }] },
                    { "featureType": "road", "elementType": "labels.text.stroke", "stylers": [{ "color": "#1d2c4d" }] },
                    { "featureType": "road.highway", "elementType": "geometry", "stylers": [{ "color": "#2c6675" }] },
                    { "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{ "color": "#255763" }] },
                    { "featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{ "color": "#b0d5ce" }] },
                    { "featureType": "road.highway", "elementType": "labels.text.stroke", "stylers": [{ "color": "#023e58" }] },
                    { "featureType": "transit", "stylers": [{ "visibility": "off" }] },
                    { "featureType": "transit", "elementType": "labels.text.fill", "stylers": [{ "color": "#98a5be" }] },
                    { "featureType": "transit", "elementType": "labels.text.stroke", "stylers": [{ "color": "#1d2c4d" }] },
                    { "featureType": "transit.line", "elementType": "geometry.fill", "stylers": [{ "color": "#283d6a" }] },
                    { "featureType": "transit.station", "elementType": "geometry", "stylers": [{ "color": "#3a4762" }] },
                    { "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#0e1626" }] },
                    { "featureType": "water", "elementType": "geometry.fill", "stylers": [{ "color": "#b3f6ff" }, { "lightness": -100 }] },
                    { "featureType": "water", "elementType": "labels.text.fill", "stylers": [{ "color": "#4e6d70" }] }
                ]
            });
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
        } else {
            console.error('Geocode failed: ' + status);
        }
    });
}

window.initMap = initMap;
//------------------- end GOOGLE MAP ---------------------------
