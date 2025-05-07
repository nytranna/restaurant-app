//------------------- SCROLL TO RESERVATION (public modul)---------------------------
document.addEventListener('DOMContentLoaded', function () {
//    console.log('DOM loaded');
    var trigger = document.getElementById('scroll-to-reservation');
    if (trigger && trigger.dataset.scroll === 'true') {
        var section = document.getElementById('reservation');
        if (section) {
            section.scrollIntoView({behavior: 'smooth'});
        }
    }
});
//------------------- end SCROLL TO RESERVATION ---------------------------

//------------------- GOOGLE MAP (public modul)---------------------------
function initMap() {
    var geocoder = new google.maps.Geocoder();
    var address = window.restaurantAddress;

    geocoder.geocode({address: address}, function (results, status) {
        if (status === 'OK') {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: results[0].geometry.location,
                styles: [
                    {"elementType": "geometry", "stylers": [{"color": "#1d2c4d"}]},
                    {"elementType": "labels.text.fill", "stylers": [{"color": "#8ec3b9"}]},
                    {"elementType": "labels.text.stroke", "stylers": [{"color": "#1a3646"}]},
                    {"featureType": "administrative", "elementType": "geometry", "stylers": [{"visibility": "off"}]},
                    {"featureType": "administrative.country", "elementType": "geometry.stroke", "stylers": [{"color": "#4b6878"}]},
                    {"featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{"color": "#64779e"}]},
                    {"featureType": "administrative.province", "elementType": "geometry.stroke", "stylers": [{"color": "#4b6878"}]},
                    {"featureType": "landscape.man_made", "elementType": "geometry.stroke", "stylers": [{"color": "#334e87"}]},
                    {"featureType": "landscape.natural", "elementType": "geometry", "stylers": [{"color": "#023e58"}]},
                    {"featureType": "poi", "stylers": [{"visibility": "off"}]},
                    {"featureType": "poi", "elementType": "geometry", "stylers": [{"color": "#283d6a"}]},
                    {"featureType": "poi", "elementType": "labels.text.fill", "stylers": [{"color": "#6f9ba5"}]},
                    {"featureType": "poi", "elementType": "labels.text.stroke", "stylers": [{"color": "#1d2c4d"}]},
                    {"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"color": "#023e58"}]},
                    {"featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{"color": "#3C7680"}]},
                    {"featureType": "road", "elementType": "geometry", "stylers": [{"color": "#304a7d"}]},
                    {"featureType": "road", "elementType": "labels.icon", "stylers": [{"visibility": "off"}]},
                    {"featureType": "road", "elementType": "labels.text.fill", "stylers": [{"color": "#98a5be"}]},
                    {"featureType": "road", "elementType": "labels.text.stroke", "stylers": [{"color": "#1d2c4d"}]},
                    {"featureType": "road.highway", "elementType": "geometry", "stylers": [{"color": "#2c6675"}]},
                    {"featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{"color": "#255763"}]},
                    {"featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{"color": "#b0d5ce"}]},
                    {"featureType": "road.highway", "elementType": "labels.text.stroke", "stylers": [{"color": "#023e58"}]},
                    {"featureType": "transit", "stylers": [{"visibility": "off"}]},
                    {"featureType": "transit", "elementType": "labels.text.fill", "stylers": [{"color": "#98a5be"}]},
                    {"featureType": "transit", "elementType": "labels.text.stroke", "stylers": [{"color": "#1d2c4d"}]},
                    {"featureType": "transit.line", "elementType": "geometry.fill", "stylers": [{"color": "#283d6a"}]},
                    {"featureType": "transit.station", "elementType": "geometry", "stylers": [{"color": "#3a4762"}]},
                    {"featureType": "water", "elementType": "geometry", "stylers": [{"color": "#0e1626"}]},
                    {"featureType": "water", "elementType": "geometry.fill", "stylers": [{"color": "#b3f6ff"}, {"lightness": -100}]},
                    {"featureType": "water", "elementType": "labels.text.fill", "stylers": [{"color": "#4e6d70"}]}
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

//------------------- end TIME ---------------------------
document.addEventListener('DOMContentLoaded', function () {
    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minuteIncrement: 1,
    });
});
//------------------- end TIME ---------------------------

//------------------- end DATE ---------------------------
document.addEventListener('DOMContentLoaded', function () {
    // Inicializace pro pole s třídou .datepicker
    flatpickr(".datepicker", {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today",
        locale: "cs"
    });
});
//------------------- end DATE ---------------------------

//------------------- SWIPER STOP ---------------------------
const swiper = new Swiper('.events-slider', {
    speed: 600,
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false // necháváme běžet, dokud ručně nezastavíme
    },
    slidesPerView: 'auto',
    pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true
    }
});

document.querySelector('.swiper-pagination').addEventListener('click', () => {
    swiper.autoplay.stop();
});
//------------------- end SWIPER STOP ---------------------------

document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.querySelector('input[name="phone"]');

    phoneInput.addEventListener('input', function () {
        // Reset zprávy, když uživatel něco píše
        this.setCustomValidity('');
    });

    phoneInput.addEventListener('invalid', function () {
        // Nastav vlastní hlášku
        if (this.validity.valueMissing) {
            this.setCustomValidity('Zadejte prosím telefonní číslo.');
        } else if (this.validity.patternMismatch) {
            this.setCustomValidity('Zadejte platné telefonní číslo (např. +420123456789).');
        }
    });
});
