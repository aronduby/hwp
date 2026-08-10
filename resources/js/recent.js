function parseHTML(html) {
    const template = document.createElement('template');
    template.innerHTML = html.trim();
    return template.content.firstElementChild;
}

function fadeOut(el) {
    const animation = el.animate([{ opacity: 1 }, { opacity: 0 }], { duration: 400 });
    animation.onfinish = () => {
        el.style.display = 'none';
    };
}

class Recent {

    constructor(container) {
        this.grid = container.querySelector('.recent-grid');
        this.btn = container.querySelector('.btn.load-more');

        this.loadCount = 0;

        this.attachEvents();
    }

    attachEvents() {
        this.btn.addEventListener('click', this.load.bind(this));
    }

    load() {
        const url = this.btn.dataset.url;

        this.grid.classList.add('loading');
        this.btn.disabled = true;

        this.loadCount++;

        fetch(url)
            .then(rsp => rsp.json())
            .then(rsp => this.done(rsp))
            .catch(err => this.error(err))
            .finally(() => {
                this.grid.classList.remove('loading');
            });
    }

    done(rsp) {
        const next = rsp.next_page_url;
        const loading = this.grid.querySelectorAll('.recent--loading');
        const max = Math.min(rsp.per_page, rsp.data.length);
        let pageClass = "";

        if (this.loadCount > 1) {
            pageClass = `recent-page--${this.loadCount % 2}`;
        }

        for (let i = 0; i < max; i++) {
            const item = rsp.data[i];
            const newEl = parseHTML(item.rendered);
            const loadingEl = loading[i];

            if (pageClass) {
                newEl.classList.add(pageClass);
            }

            if (item.sticky) {
                newEl.classList.add('recent--sticky');
                newEl.insertAdjacentHTML('beforeend', '<i class="recent-stickyIcon fa-solid fa-thumbtack"></i>');
            }

            if (loadingEl) {
                newEl.className = `${newEl.className} ${loadingEl.className}`;
                newEl.classList.remove('recent--loading');

                loadingEl.replaceWith(newEl);
            } else {
                this.grid.appendChild(newEl);
            }
        }

        // hide and remove anything still set as loading
        const empty = this.grid.querySelectorAll('.recent--loading');
        if (this.loadCount > 1) {
            empty.forEach(fadeOut);
        } else {
            empty.forEach(el => {
                el.classList.remove('recent--loading');
                el.classList.add('bg--smoke');
                el.innerHTML = '';
            });
        }


        if (next) {
            this.btn.dataset.url = next;
            this.btn.removeAttribute('disabled');
        } else {
            this.btn.remove();
        }
    }

    error(err) {
        console.error(err);
        // alert('Error loading the recent content');
        this.grid.querySelectorAll('.recent--loading').forEach(el => el.remove());
        this.loadCount--;
    }
}

export default Recent;
