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
//------------------- endSCROLL TO RESERVATION ---------------------------
