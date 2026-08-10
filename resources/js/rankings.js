import moment from "moment";
import * as formatter from './dateFormats';

class Rankings {
    constructor(el) {
        this.el = el;
        this.attachEvents();
    }

    attachEvents() {
        this.el.querySelectorAll('.pager a').forEach(a => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                this.load(a.getAttribute('href'));
                a.parentElement.classList.add('disabled');
            });
        });
    }

    load(url) {
        this.el.classList.add('loading');

        fetch(url)
            .then(rsp => rsp.json())
            .then(this.loaded.bind(this))
            .catch(this.error.bind(this))
            .finally(() => {
                this.el.classList.remove('loading');
            });
    }

    loaded(rsp) {
        const rankings = rsp.data[0];
        rankings.start = moment(rankings.start);
        rankings.end = moment(rankings.end);

        // update the pager
        if (rsp.next_page_url) {
            const next = this.el.querySelector('.pager .next');
            next.classList.remove('disabled');
            next.querySelector('a').setAttribute('href', rsp.next_page_url);
        }

        if (rsp.prev_page_url) {
            const prev = this.el.querySelector('.pager .prev');
            prev.classList.remove('disabled');
            prev.querySelector('a').setAttribute('href', rsp.prev_page_url);
        }

        // redraw the table body
        const body = this.el.querySelector('tbody');
        body.innerHTML = '';
        rankings.ranks.forEach(rank => {
            const tr = document.createElement('tr');
            tr.classList.add('rank');
            if (rank.self) {
                tr.classList.add('rank--self');
            }

            const rankTh = document.createElement('th');
            rankTh.classList.add('rank-rank');
            rankTh.textContent = rank.rank;
            tr.appendChild(rankTh);

            const teamTd = document.createElement('td');
            teamTd.classList.add('rank-team');
            teamTd.textContent = `${rank.team}${rank.tied ? '(tied)' : ''}`;
            tr.appendChild(teamTd);

            const pointsTd = document.createElement('td');
            pointsTd.classList.add('rank-points');
            pointsTd.textContent = rank.points ? rank.points.toLocaleString() : '';
            tr.appendChild(pointsTd);

            body.appendChild(tr);
        });

        // redraw the table footer
        this.el.querySelector('tfoot td')
            .innerHTML = formatter.dateSpan(rankings.start, rankings.end);
    }

    error(err) {
        console.error(err);
        alert('Error loading rankings.');
    }
}

export default Rankings;
