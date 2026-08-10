import { debounce } from 'lodash';

let el;
let container;
const className = 'ghosted';
const scroller = document.getElementsByClassName('scroller')[0];
const delay = 100;

export function ghostNav(element, context) {
    el = element;
    container = context;

    window.addEventListener('DOMContentLoaded', check);
    window.addEventListener('resize', debounce(check, delay));
    scroller.addEventListener('scroll', debounce(check, delay));
}

function ghost() {
    el.classList.add(className);
}

function unghost() {
    el.classList.remove(className)
}

function check() {
    // if el bounds is within container bounds add class
    // otherwise remove class
    if (scroller.scrollTop < container.clientHeight - el.clientHeight) {
        ghost();
    } else {
        unghost();
    }
}
