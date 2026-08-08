(function(){
    'use strict';

    let Shake = require('./shake');

    const shaker = new Shake({
        threshold: 20, // optional shake strength threshold
        timeout: 1000 // optional, determines the frequency of event generation
    });
    shaker.start();

    window.addEventListener('shake', shakeEventDidOccur, false);
    function shakeEventDidOccur (e) {
        const thumb = 'https://photos.hudsonvillewaterpolo.com/thumbs/senior-man-bun.jpg';
        const full = 'https://photos.hudsonvillewaterpolo.com/senior-man-bun.jpg';

        // set the name
        const nameParts = document.querySelectorAll('.page-header h1 span');
        nameParts[1].innerHTML = 'Se&ntilde;ior';
        nameParts[2].textContent = 'Man Bun';

        // update header cover
        document.querySelector('.page-header .bg--img').style.backgroundImage = `url(${full})`;

        // change all of the photos
        const gallery = jQuery('.full-gallery').data.gallery;
        gallery.items.forEach((item) => {
            Object.assign(item, {
                src: full,
                thumb: thumb,
                width: 1902,
                height: 2052
            });
        });

        [...document.querySelectorAll('a.gallery-photo--thumb img')]
            .forEach(img => {
                img.src = thumb;
            });
    }
})();