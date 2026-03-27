import './bootstrap';

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

import select2 from 'select2';
select2();

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
