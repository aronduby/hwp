(function () {
  'use strict';

  var fabric = require('fabric').fabric;
  var Deferred = require('../../deferred');

  var path = '/images/shareables/';

  var logo = function logo(position, defs) {
    var d = new Deferred();
    defs.promises.push(d.promise);

    fabric.Image.fromURL(path + position, function(img) {
      d.resolve(img);
    });

    return d.promise;
  };

  logo.BOTTOM = 'logo-url-bottom.png';
  logo.TOP = 'logo-url-top.png';
  logo.STACKED = 'logo-url-stacked.png';


  module.exports = logo;

})();