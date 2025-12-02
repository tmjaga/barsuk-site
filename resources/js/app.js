import './bootstrap';
import Alpine from 'alpinejs';
import $ from 'jquery';
import paginate from './paginate.js'

window.$ = window.jQuery = $;
window.Alpine = Alpine;

// attach alpine modules data
Alpine.data('paginate', paginate);

Alpine.start();
