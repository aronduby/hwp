export default function matchMenuHeight() {
    const menu = document.getElementById('main-menu');
    const body = document.querySelector('.scroller-inner');

    body.style.paddingTop = menu.offsetHeight;
}
