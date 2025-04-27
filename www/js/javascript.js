naja.initialize();

$(document).ready(function () {
    $('.dataTable').DataTable({
        language: {
            info: 'Zobrazení stránky _PAGE_ z _PAGES_',
            infoEmpty: 'Žádné dostupné záznamy',
            infoFiltered: '(filtered from _MAX_ total records)',
            lengthMenu: 'Zobraz _MENU_ záznamů na stránku',
            zeroRecords: 'Nic nenalezeno'
        }
    });

    $(document).on('click', '.remove-variant', function(e) {
        const row = $(this).closest('.variant-row');
        if (row) {
            row.remove();
            updateVariantNumbers();
        }
    });
});

const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
const Default = {
    scrollbarTheme: 'os-theme-light',
    scrollbarAutoHide: 'leave',
    scrollbarClickScroll: true,
};
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



document.querySelectorAll('.nav-item > .nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
        const parent = this.closest('.nav-item');
        const submenu = parent.querySelector('.nav-treeview');
        if (submenu) {
            e.preventDefault(); // zabrání defaultní akci
            parent.classList.toggle('menu-open');
        }
    });
});
