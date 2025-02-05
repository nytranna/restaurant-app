naja.initialize();

$(document).ready(function(){
  $('.dataTable').DataTable({
    language: {
        info: 'Zobrazení stránky _PAGE_ z _PAGES_',
        infoEmpty: 'Žádné dostupné záznamy',
        infoFiltered: '(filtered from _MAX_ total records)',
        lengthMenu: 'Zobraz _MENU_ záznamů na stránku',
        zeroRecords: 'Nic nenalezeno'
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
            
            /*
new DataTable('#example', {
    language: {
        info: 'Zobrazení stránky _PAGE_ z _PAGES_',
        infoEmpty: 'Žádné dostupné záznamy',
        infoFiltered: '(filtered from _MAX_ total records)',
        lengthMenu: 'Zobraz _MENU_ záznamů na stránku',
        zeroRecords: 'Nic nenalezeno'
    }
});
 
             */