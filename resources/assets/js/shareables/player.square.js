(function () {
  'use strict';

  var _ = require('lodash');
  var fabric = require('fabric').fabric;

  var BackgroundWithStripe = require('./parts/backgroundWithStripe');
  var logo = require('./parts/logo');
  var badge = require('./parts/badge');
  var name = require('./parts/name');
  var stat = require('./parts/stat');

  module.exports = function draw(data, defs) {
    return new Promise(function (resolve, reject) {

      var canvas = defs.canvas;
      var padding = defs.padding;
      var badgeY = 400;

      // bg - no stripe
      var bg = new BackgroundWithStripe(data.photo, defs);
      bg.stripe.height = 0;
      canvas.add(bg);

      // logo
      logo(logo.STACKED, defs)
        .then(function(img) {
          img.set({
            top: canvas.height - img.height - padding,
            left: padding
          });

          canvas.add(img);
        });

      // Charts
      if (data.charts.length) {
        var gradientBG = new fabric.Rect({
          top: 0,
          left: 784,
          height: defs.canvas.height,
          width: 304
        });

        defs.gradients.blueTransRight.coords.x2 = 304;
        gradientBG.set('fill', defs.gradients.blueTransRight);
        defs.canvas.add(gradientBG);


        var circleWidth = 204;
        var statHeight = ((circleWidth + 82.8) * .9);
        var spacing = ((defs.canvas.height - (padding * 2)) - (statHeight * 4)) / 3;
        data.charts.forEach(function(statData, i) {
          var statGroup = stat(statData);
          statGroup.set({
            scaleX: .9,
            scaleY: .9,
            originX: 'center',
            originY: 'top',
            top: padding + (i * statHeight + spacing),
            left: 845 + circleWidth / 2
          });

          defs.canvas.add(statGroup);
        });
      }

      // Name
      if (data.player) {
        var nameBox = name(data.player.player, padding, defs);
        nameBox.set({
          fontSize: 195,
          originY: 'top',
          top: padding / 2,
          left: padding,
          width: canvas.width - defs.padding - 304
        });

        canvas.add(nameBox);

        var bounding = nameBox.getBoundingRect();
        badgeY = bounding.top + bounding.height;
      }

      // Badges
      if (data.badges && data.badges.length) {
        var maxPerRow = 6;
        var countPerRow = 0;

        data.badges.forEach(function(badgeData, i) {
          var badgeGroup = badge(badgeData, false, defs);
          var x = (padding / 2) + (countPerRow * 105);
          badgeGroup.set({
            transformMatrix: [1, 0, 0, 1, x, badgeY]
          });
          canvas.add(badgeGroup);

          countPerRow++;
          if (countPerRow > maxPerRow) {
            countPerRow = 0;
            badgeY += 125;
          }
        });
      }

    });
  }

})();