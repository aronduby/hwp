// noinspection JSUnusedLocalSymbols

import FullGallery from "./gallery/full";
import PopupGallery from "./gallery/popup";
import mediaServices from "./gallery/mediaServices";

const _ = require('lodash');
const mlPushMenu = require('./mlpushmenu');
const matchMenuHeight = _.debounce(require('./matchMenuHeight'), 300);
const Note = require('./note');
const shareable = require('./shareables');
const seasonSwitching = require('./seasonSwitching');
// const firebase = require('./firebase');

// window.$ = window.jQuery = require('jquery');


// noinspection JSPotentiallyInvalidConstructorUsage
new mlPushMenu(document.getElementById('mp-menu'), document.getElementById('trigger'));

window.onresize = matchMenuHeight;
document.addEventListener('DOMContentLoaded', matchMenuHeight);

// popup galleries
$(document).ready(function () {
    $('body').on('click', '.popup-gallery', function () {
        const url = $(this).data('gallery-path');
        const el = $(this);
        const gallery = new PopupGallery(url, mediaServices);

        el.addClass('loading');
        gallery.load()
            .always(function () {
                el.removeClass('loading');
            });

        return false;
    });
});

// full galleries
$(document).ready(function () {
    $('.full-gallery').each(function (el) {
        $(this).data.gallery = new FullGallery(this, mediaServices);
    });
});
