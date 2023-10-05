import Shake from '../shake';
import * as scavenger from "./scavenger";

scavenger.trackStep('3');

const shaker = new Shake({
    threshold: 20, // optional shake strength threshold
    timeout: 1000 // optional, determines the frequency of event generation
});
shaker.start();

window.addEventListener('shake', shakeEventDidOccur, false);

function shakeEventDidOccur(e) {
    scavenger.trackStep('4');
    window.location.href = 'shook';
}
