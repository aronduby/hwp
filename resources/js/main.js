import FullGallery from "@/gallery/full";
import PopupGallery from "@/gallery/popup";
import mediaServices from "@/gallery/mediaServices";
import _matchMenuHeight from "@/matchMenuHeight";
import { mlPushMenu }  from '@/mlpushmenu';
import { debounce } from "lodash";
import '@/note';
import '@/shareables';
import '@/seasonSwitching'

// noinspection JSPotentiallyInvalidConstructorUsage
new mlPushMenu(document.getElementById('mp-menu'), document.getElementById('trigger'));

const matchMenuHeight = debounce(_matchMenuHeight, 300);
window.onresize = matchMenuHeight;
document.addEventListener('DOMContentLoaded', matchMenuHeight);

// popup galleries
document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('click', (e) => {
        const trigger = e.target.closest('.popup-gallery');
        if (!trigger) {
            return;
        }

        e.preventDefault();

        const url = trigger.dataset.galleryPath;
        const gallery = new PopupGallery(url, mediaServices);

        trigger.classList.add('loading');
        gallery.load()
            .finally(() => {
                trigger.classList.remove('loading');
            });
    });
});

// full galleries
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.full-gallery').forEach((el) => {
        el.fullGallery = new FullGallery(el, mediaServices);
    });
});
