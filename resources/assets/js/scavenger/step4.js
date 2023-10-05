import * as scavenger from "./scavenger";

const horn = new Audio('audio/party-horn.mp3');
horn.autoplay = false;
horn.loop = false;

function rnd(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function confetti(el) {
    let count = (el.offsetWidth / 50) * 10;
    for (var i = 0; i <= count; i++) {
        let span = document.createElement('span');
        span.classList.add(`particle`, `c${rnd(1, 2)}`);
        span.style = `top:${rnd(10, 50)}%; left:${rnd(0, 100)}%; width:${rnd(6, 8)}px; height:${rnd(3, 4)}px; animation-delay:${Math.random() / 1.5}s`;
        el.appendChild(span);
    }
}

document.addEventListener('DOMContentLoaded', function () {

    const form = document.forms.streaker;
    const btn = document.querySelector('button[type="submit"]');
    const first = form.querySelector('[name="first"]');
    const second = form.querySelector('[name="second"]');
    const inputs = [first, second];

    function reset() {
        form.reset();
        form.classList.remove('status--success', 'status--fail');
        inputs.forEach(el => el.classList.remove('dirty'));
        [btn, ...inputs].forEach(el => el.disabled = false);
    }

    // form styling, don't show invalid until it's been blurred
    inputs.forEach(el => {
        el.addEventListener('blur', function () {
            this.classList.add('dirty');
        });
    });

    // submission
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!this.checkValidity()) {
            return false;
        }

        const fd = new FormData(form);

        // make sure this is after creating the form data
        [btn, ...inputs].forEach(el => el.disabled = true);

        fetch(window.location, {
            method: 'POST',
            body: fd,
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(rsp => rsp.json())
            .then(data => {

                // console.log(data);

                form.classList.add(`status--${data.success ? 'success' : 'fail'}`);
                first.value = data.first;
                second.value = data.second;

                let next = reset;

                if (data.success) {
                    confetti(form.querySelector('div.particle-holder'));
                    horn.play();

                    next = () => {
                        scavenger.trackStep('5');
                        window.location.href = 'poltergeist';
                    }
                }

                setTimeout(next, 1500);
            });

    });

});
