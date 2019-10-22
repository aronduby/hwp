(function() {
    'use strict';

    function toggleTwirl(enable) {
        window.localStorage.setItem('twirl', enable);
        document.body.classList.toggle('twirl', enable);
    }

    function eventHandler() {
        twirl = !twirl;
        toggleTwirl(twirl);
    }

    let twirl = JSON.parse(window.localStorage.getItem('twirl') || 'false');
    if (twirl) {
        toggleTwirl(twirl);
    }

    const header = document.querySelector('.page-header');

    header.addEventListener('dblclick', eventHandler);

    // double tap
    let tapedTwice = false;
    header.addEventListener('touchstart', (event) => {
        if(!tapedTwice) {
            tapedTwice = true;
            setTimeout( () => {
                tapedTwice = false;
            }, 300 );
            return false;
        }

        event.preventDefault();
        eventHandler();
    });

}());