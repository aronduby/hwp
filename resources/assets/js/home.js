import Rankings from './rankings';
import Recent from "./recent";
import { ghostNav } from "./ghostNav";
import './scavenger/step1';
import './notifications';

document.addEventListener('DOMContentLoaded', async () => {

    // ghost the nav when we're at the top of the page
    ghostNav(document.getElementById('main-menu'), document.getElementById('home-header'));

    // rankings
    new Rankings(document.querySelector('.rankings'));

    // recent
    var recent = new Recent(document.querySelector('.recent-content'));
    recent.load();

});