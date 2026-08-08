(function () {
    'use strict';

    var menu = document.getElementById('main-menu');
    var body = document.querySelector('.scroller-inner');

    module.exports = function() {
        body.style.paddingTop = menu.offsetHeight;
    }


})();