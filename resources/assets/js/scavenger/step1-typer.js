const typingSound = new Audio('audio/glitching.mp3');
typingSound.loop = true;

export var TxtType = function (el, lines, period) {
    this.lines = lines;
    this.el = el;
    this.wrap = el.querySelectorAll('.glitcher-wrap')[0];
    this.period = parseInt(period, 10) || 2000;
};

TxtType.prototype.start = function () {
    this.reset();
    this.tick();
    this.isDeleting = false;
};

TxtType.CompleteEventName = 'typerCompleted';

TxtType.prototype.reset = function () {
    this.txt = '';
    this.loopNum = 0;
    this.isDeleting = false;
    typingSound.pause();
};

TxtType.prototype.tick = function () {
    var i = this.loopNum % this.lines.length;
    var fullTxt = this.lines[i];

    typingSound.play();
    if (this.isDeleting) {
        this.txt = fullTxt.substring(0, this.txt.length - 1);
    } else {
        this.txt = fullTxt.substring(0, this.txt.length + 1);
    }

    this.wrap.textContent = this.txt;

    var that = this;
    var delta = 150 - Math.random() * 100;

    if (this.isDeleting) {
        delta /= 10;
    }

    if (!this.isDeleting && this.txt === fullTxt) {
        delta = this.period;
        this.isDeleting = true;
        typingSound.pause();
    } else if (this.isDeleting && this.txt === '') {
        this.isDeleting = false;
        this.loopNum++;
        delta = 500;
    }

    if (this.loopNum < this.lines.length) {
        setTimeout(function () {
            that.tick();
        }, delta);
    } else {
        this.reset();
        const e = new CustomEvent(TxtType.CompleteEventName, {writer: this});
        this.el.dispatchEvent(e);
    }
};