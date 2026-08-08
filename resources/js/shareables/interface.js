(function () {
  'use strict';

  global.jQuery = require('jquery');
  const $ = jQuery;

  const Signal = require('signals');

  const markup = `
    <div class="shareable-holder">
        <div class="backdrop close"></div>
        <div class="shareable-image-holder">
            <div class="loader"></div>
            <img class="shareable-image"/>
        </div>
        <div class="shareable-sizer">          
          <input id="shareable-size-square" type="radio" name="shareable-size" value="square">
          <label for="shareable-size-square" class="instagram"><i class="fa fa-instagram"></i></label>          
          <input id="shareable-size-rectangle" type="radio" name="shareable-size" value="rectangle">
          <label for="shareable-size-rectangle" class="snapchat"><i class="fa fa-snapchat-ghost"></i></label>
        </div>
        <button class="close pswp__button pswp__button--close"></button>
    </div>
  `;

  const el = $(markup);
  const img = el.find('img');
  const close = el.find('.close');
  const sizes = el.find('input[name="shareable-size"]');

  const closed = new Signal();
  const sizeChanged = new Signal();

  let showing = false;

  img.hide();

  close.on('click', function(e) {
    hide();
    return false;
  });

  sizes.on('input, change', function() {
    sizeChanged.dispatch($(this).val());
    img.hide();
  });

  function show() {
    el.appendTo(document.body);
    showing = true;
  }

  function hide() {
    el.detach();
    img.hide();
    showing = false;
    closed.dispatch();
  }

  function load(src) {
    img.attr('src', src)
      .show();
  }

  function setSize(size) {
    sizes.removeAttr('checked');
    sizes.filter('[value="' + size + '"]').attr('checked', 'checked');
  }

  function isShowing() {
    return showing;
  }

  module.exports = {
    show: show,
    hide: hide,
    load: load,
    setSize: setSize,
    isShowing: isShowing,
    closed: closed,
    sizeChanged: sizeChanged
  };

})();