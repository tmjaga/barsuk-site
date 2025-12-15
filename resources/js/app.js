import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';
import listingPage from './listing_page.js'
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light-border.css';
import alpidate from 'alpidate';
import alertStore from './alert_store.js'
import deleteItem from "./delete_item.js";

window.$ = window.jQuery = $;
window.Alpine = Alpine;

// register listingpage component
Alpine.data('listingpage', listingPage);

// register global alert component in store
Alpine.store('alert', alertStore);

// register alpidate validate plugin
Alpine.plugin(alpidate);

// register deleteItem function to use in confirm-delete blade component
Alpine.data('deleteItem', deleteItem);

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

