import Signal from "signals";

const markup = `
    <div class="shareable-holder">
        <div class="backdrop close"></div>
        <div class="shareable-image-holder">
            <div class="loader"></div>
            <img class="shareable-image"/>
        </div>
        <div class="shareable-sizer">
          <input id="shareable-size-square" type="radio" name="shareable-size" value="square">
          <label for="shareable-size-square" class="instagram"><i class="fa fa-instagram"></i></label>
          <input id="shareable-size-rectangle" type="radio" name="shareable-size" value="rectangle">
          <label for="shareable-size-rectangle" class="snapchat"><i class="fa fa-snapchat-ghost"></i></label>
        </div>
        <button class="close pswp__button pswp__button--close"></button>
    </div>
  `;

const template = document.createElement('template');
template.innerHTML = markup.trim();
const el = template.content.firstElementChild;

const img = el.querySelector('img');
const closeEls = el.querySelectorAll('.close');
const sizes = el.querySelectorAll('input[name="shareable-size"]');

export const closed = new Signal();
export const sizeChanged = new Signal();

let showing = false;

img.style.display = 'none';

closeEls.forEach((closeEl) => {
    closeEl.addEventListener('click', () => {
        hide();
    });
});

sizes.forEach((input) => {
    ['input', 'change'].forEach((eventType) => {
        input.addEventListener(eventType, function () {
            sizeChanged.dispatch(this.value);
            img.style.display = 'none';
        });
    });
});

export function show() {
    document.body.appendChild(el);
    showing = true;
}

export function hide() {
    el.remove();
    img.style.display = 'none';
    showing = false;
    closed.dispatch();
}

export function load(src) {
    img.src = src;
    img.style.display = '';
}

export function setSize(size) {
    sizes.forEach((input) => input.removeAttribute('checked'));

    const match = [...sizes].find((input) => input.value === size);
    if (match) {
        match.setAttribute('checked', 'checked');
    }
}

export function isShowing() {
    return showing;
}
