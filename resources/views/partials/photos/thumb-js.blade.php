{{--
This gets loaded into JS and rendered through lodash's template method

See resources/js/gallery/__types.js#ImageData for the data available, along with an offset variable
--}}
<a class="gallery-photo--thumb" href="${src}" data-offset="${offset}">
    <img src="${msrc}" />
</a>
