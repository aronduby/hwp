import Cookies from 'js-cookie';

const keyName = 'season_id';

window.addEventListener('DOMContentLoaded', () => {
    [...document.querySelectorAll('a[data-season-id]')].forEach((node) => {
        node.addEventListener('click', (e) => {
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
