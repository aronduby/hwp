import vex from 'vex-js';

export default function subscribeModalInit() {

	window.addEventListener('DOMContentLoaded', () => {

		const subscribeModelContent = document.getElementById('subscribe-modal').textContent;
		const subscribeBtn = document.querySelector('button.subscribe');
		subscribeBtn.addEventListener('click', () => {
			vex.open({
				unsafeContent: subscribeModelContent,
				className: 'vex-theme-note'
			});
		});

	});

}