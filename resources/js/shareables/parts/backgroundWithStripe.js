import * as GeoPattern from 'geopattern';
import getDataUri from '@/getDataUri';
import Deferred from '@/deferred';
import { fabric } from 'fabric';

export default fabric.util.createClass(fabric.Image, {

    type: 'bg',

    stripe: {
        width: 1080,
        height: 240,
        top: 0,
        left: 0
    },

    originX: 'center',
    originY: 'center',

    initialize: function (photo, defs, options) {
        this.callSuper('initialize', options);

        this.deferred = new Deferred();
        this.cWidth = defs.canvas.width;
        this.cHeight = defs.canvas.height;
        this.width = this.cWidth;
        this.height = this.cHeight;
        this.gridPattern = defs.gridPattern;

        if (photo) {
            this.loadPhoto(photo, defs);
        } else {
            this.loadPattern(defs);
        }

        defs.promises.push(this.deferred.promise);
    },

    loadPhoto: function (photo, defs) {
        // initial workaround to get around dirty context
        getDataUri(photo.photo, 'image/jpg')
            .then((uri) => {
                if (uri) {
                    this.pattern = false;

                    fabric.Image.fromURL(uri, (img) => {
                        const blur = .15;

                        // make sure we adjust our scaling to account for the blur so it doesn't taper off at the edges
                        const ratios = {
                            width: this.cWidth / (img.width - (img.width * blur)),
                            height: this.cHeight / (img.height - (img.height * blur))
                        };
                        const scale =  Math.max(ratios.width, ratios.height);
                        img.scale(scale);

                        img.filters.push(new fabric.Image.filters.Blur({ blur }));
                        img.applyFilters();

                        // now we make this another image from our fabric img to bake in the scale and blur
                        this.image = new Image();
                        this.image.onload = () => {
                            this.loaded = true;
                            this.deferred.resolve();
                        };
                        this.image.src = img.toDataURL();
                    });
                }
            });
    },

    loadPattern: function (defs) {
        const pattern = GeoPattern.generate(Date.now() + '', {
            color: '#435e8d'
        });

        this.image = false;
        this.pattern = new fabric.Pattern({
            source: pattern.toDataUri(),
            repeat: 'repeat'
        });

        this.loaded = true;
        this.deferred.resolve();
    },

    _render: function (ctx) {
        if (this.loaded) {
            ctx.save();

            // draw our background (image or pattern)
            if (this.image) {
                ctx.drawImage(this.image,
                    this.cWidth / 2 - this.image.width / 2,
                    this.cHeight / 2 - this.image.height / 2
                );
            } else {
                ctx.rect(0, 0, this.width, this.height);
                ctx.fillStyle = this.pattern.toLive(ctx);
                ctx.fill();
            }

            // switch to composite blendmode
            ctx.globalCompositeOperation = 'soft-light';
            ctx.globalAlpha = 0.7;

            // draw the stripe
            ctx.fillStyle = '#2f3157';
            ctx.fillRect(this.stripe.left, this.stripe.top, this.width, this.stripe.height);

            ctx.fillStyle = this.gridPattern.toLive(ctx);
            ctx.fillRect(this.stripe.left, this.stripe.top, this.width, this.stripe.height);
            // ctx.fill();

            ctx.restore();
        }
    }

});
