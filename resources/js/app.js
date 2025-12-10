import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';
import listingPage from './listing_page.js'
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light-border.css';
import alpidate from 'alpidate';


window.$ = window.jQuery = $;
window.Alpine = Alpine;

// register listingpage component
Alpine.data('listingpage', listingPage);
// register alpidate validate plugin
Alpine.plugin(alpidate);



window.initTooltips = function() {
    tippy('[data-tippy-content]', {
        placement: 'bottom',
        animation: 'fade',
        arrow: true,
        theme: 'light-border'
    });
};

initTooltips();

Alpine.start();

