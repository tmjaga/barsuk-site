import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';
import listingPage from './listing_page.js'

window.$ = window.jQuery = $;
window.Alpine = Alpine;

// attach alpine modules data
Alpine.data('listingpage', listingPage);

Alpine.start();
