(function () {
    'use strict';

    const Cookies = require('js-cookie');
    const keyName = 'season_id';

    window.addEventListener('DOMContentLoaded', function () {
        [...document.querySelectorAll('a[data-season-id]')].forEach((node) => {
            node.addEventListener('click', function (e) {
                const data = e.currentTarget.dataset;

                if (data.current) {
                    Cookies.remove(keyName);
                } else {
                    const sid = data.seasonId;
                    Cookies.set(keyName, sid);
                }

                window.location.reload(true);
                e.stopPropagation();
                e.preventDefault();
            });
        });
    });

})();