(function () {
    'use strict';

    var vex = require('vex-js');
    vex.defaultOptions.className = 'vex-theme-default vex-theme-jobSettings';

    global.jQuery = require('jquery');
    var $ = jQuery;

    const $table = $('table.jobList');

    // toggling of the settings & logs
    $table.on('click', 'a.toggler', function() {
        $(this).parents('.settingsAndLogs').toggleClass('open');
    });

    // clicking on instance base creates
    $table.on('click', 'button.add-instance', function(e) {
        const $t = $(this);
        const $tbody = $t.parents('tbody.instanceBase');

        /**
         * @var {object} data
         * @property {string} jobKey
         * @property {bool} hasSettings
         */
        const data = $tbody.data();

        $tbody.addClass('disabled');

        if (data.hasSettings) {
            const main = $tbody.find('template.settings-modal-template')[0].content.firstElementChild.cloneNode(true);
            const vexInstance = vex.open({
                unsafeContent: main.outerHTML,
                beforeClose: function() {
                    $tbody.removeClass('disabled');
                }
            });

            const body = vexInstance.contentEl.querySelector('.body');
            const form = vexInstance.contentEl.querySelector('form');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                this.querySelector('button[type="submit"]').disabled = true;
                body.classList.add('loading');
                const formData = new FormData(form);
                formData.append('jobKey', data.jobKey);

                createJobInstance(formData)
                    .then((rsp) => {
                        handleNewInstanceResponse($tbody, data, rsp);
                        vexInstance.close();
                    })
                    .catch(err => {
                        if (err.errorType !== 'validation') {
                            vexInstance.close();
                        }

                        $tbody.removeClass('disabled');
                        this.querySelector('button[type="submit"]').disabled = false;
                        body.classList.remove('loading');
                    })
            });
        } else {
            const formData = new FormData();
            formData.append('jobKey', data.jobKey);

            createJobInstance(formData)
                .then((rsp) => {
                    handleNewInstanceResponse($tbody, data, rsp);
                });
        }
    });

    function createJobInstance(formData) {
        return doRequest('/admin/jobs', {
            method: 'POST',
            credentials: "include",
            body: formData,
            headers: getCSFRHeader()
        });
    }

    function handleNewInstanceResponse($tbody, data, rsp) {
        const $html = $(rsp.html);
        $html.addClass('highlightNew');

        if (data.allowMultiple === true) {
            $html.insertAfter($tbody);
        } else {
            $tbody.replaceWith($html);
        }

        $tbody.removeClass('disabled');
    }

    // toggling the auto-run enabled flag
    $table.on('change', 'input.toggler', function(e) {
        const $t = $(this);
        const $tbody = $t.parents('tbody[data-instance-id]');

        /**
         * @var {object} data
         * @property {int} instanceId
         */
        const data = $tbody.data();
        const checked = $t[0].checked;

        editInstance($tbody, data.instanceId, { enabled: checked })
            .catch(err => {
                this.checked = !!checked;
            })

    });

    // editing settings
    $table.on('submit', '.instance-settings form', function(e) {
        e.preventDefault();

        const $t = $(this);
        const $tbody = $t.parents('tbody[data-instance-id]');
        const instanceId = $tbody.data('instanceId');

        const formData = new FormData(this);
        const settings = {}
        for (let [key, value] of formData.entries()) {
            settings[key] = value;
        }

        editInstance($tbody, instanceId, { settings });
    });

    function editInstance($tbody, instanceId, data) {
        $tbody.addClass('disabled');

        const headers = getCSFRHeader();
        headers['Content-Type'] = 'application/json';

        return doRequest('/admin/jobs/' + instanceId, {
            method: 'PATCH',
            credentials: "include",
            headers: headers,
            body: JSON.stringify(data)
        })
            .finally(() => {
                $tbody.removeClass('disabled');
            })
    }

    // running jobs
    $table.on('click', 'button.run-job', function(e) {
        const $t = $(this);
        const $tbody = $t.parents('tbody[data-instance-id]');
        const instanceId = $tbody.data('instanceId');

        $tbody.addClass('disabled');

        doRequest('/admin/jobs/' + instanceId + '/run', {method: 'GET'})
            .then(rsp => {
                let iconClass;
                switch(rsp.status) {
                    case 'success':
                        iconClass = 'fa-check-circle';
                        break;
                    case 'warning':
                        iconClass = 'fa-exclamation-triangle';
                        break;
                    case 'error':
                        iconClass = 'fa-exclamation-circle';
                        break;
                }

                const vexInstance = vex.open({
                    unsafeContent: `<div class="run-result">
                        <i class="fa fa-5x ${iconClass} text--${rsp.status}"></i>
                        <h2>${rsp.status}</h2>
                        <pre>${rsp.output}</pre>
                        <button class="btn btn-close">Close</button>
                    </div>`
                });

                $(vexInstance.rootEl).find('.btn-close').on('click', () => {
                    vexInstance.close();
                });

                const $html = $(rsp.html);
                $tbody.replaceWith($html);

            })
            .finally(() => {
                $tbody.removeClass('disabled');
            });
    });

    // deleting instances
    $table.on('click', 'button.delete-instance', function(e) {
        const $t = $(this);
        const $tbody = $t.parents('tbody[data-instance-id]');

        /**
         * @var {object} data
         * @property {int} instanceId
         */
        const data = $tbody.data();

        $tbody.addClass('disabled');

        doRequest('/admin/jobs/' + data.instanceId, {
            method: 'DELETE',
            credentials: "include",
            headers: getCSFRHeader(),
        })
            .then(rspData => {
                if (rspData.action === 'replace') {
                    const $html = $(rspData.html);
                    $tbody.replaceWith($html);
                } else {
                    $tbody.remove();
                }
            })
            .finally(() => $tbody.removeClass('disabled'));
    })

    function getCSFRToken() {
        return $('meta[name="csrf-token"]').attr('content');
    }
    function getCSFRHeader() {
        return {
            'X-CSRF-TOKEN': getCSFRToken()
        }
    }

    function handleError(err) {
        alert(err.message);
    }

    function doRequest(url, config) {

        config.headers = config.headers || {};
        config.headers['X-CSRF-TOKEN'] = config.headers['X-CSRF-TOKEN'] || getCSFRToken();

        return fetch(url, config)
            .then(rsp => {
                return rsp.json()
                    .then(data => {
                        if (rsp.ok === false) {
                            throw data;
                        } else {
                            return data;
                        }
                    })
            })
            .catch(err => {
                handleError(err);
                throw err;
            });
    }
})()