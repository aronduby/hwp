(function () {
  'use strict';
  global.jQuery = require('jquery');
  const $ = jQuery;


  var fabric = require('fabric').fabric;

  var BackgroundWithStripe = require('./parts/backgroundWithStripe');
  var logo = require('./parts/logo');
  var message = require('./parts/updateMessage');
  var meta = require('./parts/updateMeta');

  module.exports = function draw(data, defs, event) {
    return new Promise(function(resolve, reject) {

      var canvas = defs.canvas;
      var padding = defs.padding;
      var update = JSON.parse(
        $(event.currentTarget)
          .parents('.update')
          .find('.json')
          .text()
      );

      // Background
      // bg and stripe height is relative to the update
      var bg = new BackgroundWithStripe(data.photo, defs);
      canvas.add(bg);

      // Logo
      logo(logo.BOTTOM, defs)
        .then(function(img) {
          img.set({
            top: canvas.height - img.height
          });

          canvas.add(img);
        });

      // Message
      var msg = message(update.msg, defs, 3.5);
      msg.set({
        fontSize: 129,
        lineHeight: 1.1,
        top: (canvas.height / 2) - 68,
        left: canvas.width / 2
      });
      canvas.add(msg);

      // bounds needed for stripe and the meta
      var msgBounding = msg.getBoundingRect();

      // update the stripe bg
      var tbp = (padding / 2) * 3;
      bg.stripe.height = msgBounding.height + tbp;
      bg.stripe.top = msgBounding.top - (tbp / 2);

      // Meta Info
      var metaInfo = meta(update, defs);
      metaInfo.set({
        top: msgBounding.top + msgBounding.height + (tbp / 2) + metaInfo.height * 1.5,
        left: canvas.width / 2
      });
      canvas.add(metaInfo);

    });
  }

})();