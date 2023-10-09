import _ from 'lodash';

const $ = jQuery;

function Recent(holder, btn) {
    this.holder = $(holder);
    this.btn = $(btn);
    this.loadCount = 0;
    this.templates = [];

    this.getTemplates();
    this.attachEvents();
}

Recent.prototype.pageIDs = ['page-even', 'page-odd'];

Recent.prototype.getTemplates = function () {
    var self = this;

    _.forEach(this.pageIDs, function (val, idx) {
        self.templates[idx] = $($('#' + val).text());
    });
};

Recent.prototype.attachEvents = function () {
    var self = this;

    this.btn.on('click', this.load.bind(self));
};

Recent.prototype.load = function () {
    var self = this;
    var url = this.btn.data('url');
    var tmpl;

    this.holder.addClass('loading');
    this.btn.attr('disabled', 'disabled');

    this.loadCount++;

    if (this.loadCount > 1) {
        tmpl = this.templates[this.loadCount % 2].clone();
        tmpl.appendTo(this.holder);
    }

    $.getJSON(url)
        .done(this.done.bind(self))
        .fail(this.error.bind(self))
        .always(function () {
            self.holder.removeClass('loading');
        });
};

Recent.prototype.done = function (rsp) {
    var self = this;
    var next = rsp.next_page_url;
    var loading = this.holder.find('.recent--loading');
    var i = 0;
    var max = Math.min(rsp.per_page, rsp.data.length);
    var pageClass = "";

    if (this.loadCount > 1) {
        pageClass = 'recent-page--' + this.loadCount % 2;
    }

    for (i; i < max; i++) {
        var item = rsp.data[i];
        var newEl = $(item.rendered);
        var loadingEl = loading.eq(i);

        newEl.addClass(pageClass);

        if (loadingEl.length) {
            newEl.attr('class', newEl.attr('class') + ' ' + loadingEl.attr('class'))
                .removeClass('recent--loading');

            loadingEl.replaceWith(newEl);
        } else {
            newEl.appendTo(self.holder);
        }
    }

    // hide and remove anything still set as loading
    var empty = this.holder.find('.recent--loading');
    if (this.loadCount > 1) {
        empty.fadeOut();
    } else {
        empty.removeClass('recent--loading').addClass('bg--smoke').empty();
    }


    if (next) {
        this.btn.data('url', next)
            .removeAttr('disabled');
    } else {
        this.btn.remove();
    }
};

Recent.prototype.error = function (err) {
    console.error(err);
    alert('Error loading the recent content');
    this.holder.find('.recent--loading').remove();
    this.loadCount--;
};

export default Recent;