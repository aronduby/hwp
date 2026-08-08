import _ from 'lodash';

var el;
var container;
var className = 'ghosted';
var scroller = document.getElementsByClassName('scroller')[0];
var delay = 100;

export function ghostNav(element, context) {
    el = element;
    container = context;

    window.addEventListener('DOMContentLoaded', check);
    window.addEventListener('resize', _.debounce(check, delay));
    scroller.addEventListener('scroll', _.debounce(check, delay));
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