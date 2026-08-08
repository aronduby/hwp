(function () {
  'use strict';

  var fabric = require('fabric').fabric;

  const svg = `<?xml version="1.0" encoding="iso-8859-1"?><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
            width="72" height="72" viewBox="32 -72.249 72 72">
                <polygon style="fill:none;" points="32,-0.249 32,-72.25 104,-72.25 104,-0.249"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="31.75" y1="-63.249" x2="104.25" y2="-63.249"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="31.75" y1="-27.249" x2="104.25" y2="-27.249"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="0" y1="-63.249" x2="28" y2="-63.249"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="31.75" y1="-45.249" x2="104.25" y2="-45.249"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="31.75" y1="-9.249" x2="104.25" y2="-9.249"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="0" y1="-45.249" x2="28" y2="-45.249"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="77" y1="-72.5" x2="77" y2="0.001"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="41" y1="-72.5" x2="41" y2="0.001"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="5" y1="-68.187" x2="5" y2="-40.249"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="95" y1="-72.5" x2="95" y2="0.001"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="59" y1="-72.5" x2="59" y2="0.001"/>
                <line style="fill:none;stroke:#231F20;stroke-width:0.3;" x1="23" y1="-68.187" x2="23" y2="-40.249"/>
        </svg>`;

  module.exports = function gridPattern() {
    return new Promise(function(resolve, reject) {
      try {
        fabric.loadSVGFromString(svg, function(objects, opts) {
          var patternSourceCanvas = new fabric.StaticCanvas();
          var ptnGroup = fabric.util.groupSVGElements(objects, opts);

          patternSourceCanvas.add(ptnGroup);
          patternSourceCanvas.setDimensions({
            width: 72,
            height: 72
          });

          var texture = patternSourceCanvas.getElement();
          resolve(new fabric.Pattern({
            source: texture,
            repeat: 'repeat'
          }));
        });
      } catch(e) {
        reject(e);
      }
    });
  }


})();