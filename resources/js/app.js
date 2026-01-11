import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';
import listingPage from './listing_page.js'
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light-border.css';
import alpidate from 'alpidate';
import alertStore from './alert_store.js'
import deleteItem from "./delete_item.js";
import { delegate } from 'tippy.js';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

window.flatpickr = flatpickr;
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

// register global method to format date time in listing pages
Alpine.magic('formatDate', () => (dateStr) => {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    const day = String(d.getUTCDate()).padStart(2,'0');
    const month = String(d.getUTCMonth()+1).padStart(2,'0');
    const year = d.getUTCFullYear();
    const hours = String(d.getUTCHours()).padStart(2,'0');
    const minutes = String(d.getUTCMinutes()).padStart(2,'0');
    return `${day}.${month}.${year} ${hours}:${minutes}`;
});

// initialize/register tippy for dynamic content
delegate( document.body, {
    target: '[data-tippy-content]',
    placement: 'bottom',
    animation: 'fade',
    arrow: true,
    theme: 'light-border'
});

Alpine.start();

