import moment from "moment";
import * as formatter from './dateFormats';

const $ = jQuery;

function Rankings(el) {
    this.el = $(el);
    this.attachEvents();
}

Rankings.prototype.attachEvents = function () {
    var self = this;

    this.el.find('.pager a').on('click', function () {
        self.load($(this).attr('href'));
        $(this).parent().addClass('disabled');
        return false;
    });
};

Rankings.prototype.load = function (url) {
    var self = this;

    self.el.addClass('loading');

    $.getJSON(url)
        .done(this.loaded.bind(self))
        .fail(this.error.bind(self))
        .always(function () {
            self.el.removeClass('loading');
        })
};

Rankings.prototype.loaded = function (rsp) {
    var rankings = rsp.data[0];
    rankings.start = moment(rankings.start);
    rankings.end = moment(rankings.end);

    // update the pager
    if (rsp.next_page_url) {
        this.el.find('.pager .next').removeClass('disabled')
            .find('a').attr('href', rsp.next_page_url);
    }

    if (rsp.prev_page_url) {
        this.el.find('.pager .prev').removeClass('disabled')
            .find('a').attr('href', rsp.prev_page_url);
    }

    // redraw the table body
    var body = this.el.find('tbody');
    body.empty();
    rankings.ranks.forEach(function (rank) {
        var tr = $('<tr></tr>').addClass('rank');
        if (rank.self) {
            tr.addClass('rank--self');
        }

        $('<th></th>')
            .addClass('rank-rank')
            .text(rank.rank)
            .appendTo(tr);

        $('<td></td>')
            .addClass('rank-team')
            .text(rank.team + (rank.tied ? '(tied)' : ''))
            .appendTo(tr);

        $('<td></td>')
            .addClass('rank-points')
            .text(rank.points ? rank.points.toLocaleString() : '')
            .appendTo(tr);

        body.append(tr);
    });

    // redraw the table footer
    this.el.find('tfoot td')
        .html(formatter.dateSpan(rankings.start, rankings.end));
};

Rankings.prototype.error = function (err) {
    console.error(err);
    alert('Error loading rankings.');
};

export default Rankings;

