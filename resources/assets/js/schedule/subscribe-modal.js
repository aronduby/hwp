var vex = require('vex-js');

export default function subscribeModalInit() {

	window.addEventListener('DOMContentLoaded', function () {

		var subscribeModelContent = document.getElementById('subscribe-modal').textContent;
		var subscribeBtn = document.querySelector('button.subscribe');
		subscribeBtn.addEventListener('click', function() {
			vex.open({
				unsafeContent: subscribeModelContent,
				className: 'vex-theme-note'
			});
		});

	});

}