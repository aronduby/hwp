(function () {
  'use strict';

  module.exports = function getDataUri(url, type = 'image/png') {
    return new Promise(function(resolve, reject) {
      var img = new Image();

      img.onload = function() {
        var c = document.createElement('canvas');
        c.width = this.naturalWidth;
        c.height = this.naturalHeight;

        c.getContext('2d').drawImage(this, 0, 0);

        try {
          resolve(c.toDataURL(type));
        } catch(e) {
          console.error(e);
          resolve('');
        }
      };

      img.crossOrigin = "anonymous";
      img.src = url;
    });
  }

})();