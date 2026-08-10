import delegate from "@/utils/delegate.js";
import vex from 'vex-js';

vex.defaultOptions.className = 'vex-theme-default vex-theme-jobSettings';

const table = document.querySelector('table.jobList');

// reads an element's data-* attributes with jQuery-like type coercion
// (numeric strings -> numbers, "true"/"false" -> booleans)
function readData(el) {
    const data = {};
    for (const [key, value] of Object.entries(el.dataset)) {
        if (value === 'true') {
            data[key] = true;
        } else if (value === 'false') {
            data[key] = false;
        } else { // noinspection JSCheckFunctionSignatures
            if (value !== '' && !isNaN(value)) {
                        data[key] = Number(value);
                    } else {
                        data[key] = value;
                    }
        }
    }
    return data;
}

function htmlToElement(html) {
    const template = document.createElement('template');
    template.innerHTML = html.trim();
    return template.content.firstElementChild;
}

// toggling of the settings & logs
delegate(table, 'click', 'a.toggler', function () {
    this.closest('.settingsAndLogs').classList.toggle('open');
});

// clicking on instance base creates
delegate(table, 'click', 'button.add-instance', function (_e) {
    const tbody = this.closest('tbody.instanceBase');

    /**
     * @var {object} data
     * @property {string} jobKey
     * @property {bool} hasSettings
     */
    const data = readData(tbody);

    tbody.classList.add('disabled');

    if (data.hasSettings) {
        const main = tbody.querySelector('template.settings-modal-template').content.firstElementChild.cloneNode(true);
        // noinspection JSUnusedGlobalSymbols
        const vexInstance = vex.open({
            unsafeContent: main.outerHTML,
            beforeClose: function () {
                tbody.classList.remove('disabled');
            }
        });

        const body = vexInstance.contentEl.querySelector('.body');
        const form = vexInstance.contentEl.querySelector('form');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            this.querySelector('button[type="submit"]').disabled = true;
            body.classList.add('loading');
            const formData = new FormData(form);
            formData.append('jobKey', data.jobKey);

            createJobInstance(formData)
                .then((rsp) => {
                    handleNewInstanceResponse(tbody, data, rsp);
                    vexInstance.close();
                })
                .catch(err => {
                    // noinspection JSUnresolvedReference
                    if (err.errorType !== 'validation') {
                        vexInstance.close();
                    }

                    tbody.classList.remove('disabled');
                    this.querySelector('button[type="submit"]').disabled = false;
                    body.classList.remove('loading');
                })
        });
    } else {
        const formData = new FormData();
        formData.append('jobKey', data.jobKey);

        createJobInstance(formData)
            .then((rsp) => {
                handleNewInstanceResponse(tbody, data, rsp);
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

function handleNewInstanceResponse(tbody, data, rsp) {
    const html = htmlToElement(rsp.html);
    html.classList.add('highlightNew');

    // noinspection JSUnresolvedReference
    if (data.allowMultiple === true) {
        tbody.after(html);
    } else {
        tbody.replaceWith(html);
    }

    tbody.classList.remove('disabled');
}

// toggling the auto-run enabled flag
delegate(table, 'change', 'input.toggler', function (_e) {
    const t = this;
    const tbody = t.closest('tbody[data-instance-id]');

    /**
     * @var {object} data
     * @property {int} instanceId
     */
    const data = readData(tbody);
    const checked = t.checked;

    editInstance(tbody, data.instanceId, {enabled: checked})
        .catch(_err => {
            t.checked = !!checked;
        })

});

// editing settings
delegate(table, 'submit', '.instance-settings form', function (e) {
    e.preventDefault();

    const tbody = this.closest('tbody[data-instance-id]');
    const instanceId = tbody.dataset.instanceId;

    const formData = new FormData(this);
    const settings = {}
    for (const [key, value] of formData.entries()) {
        settings[key] = value;
    }

    // noinspection JSIgnoredPromiseFromCall
    editInstance(tbody, instanceId, {settings});
});

function editInstance(tbody, instanceId, data) {
    tbody.classList.add('disabled');

    const headers = getCSFRHeader();
    headers['Content-Type'] = 'application/json';

    return doRequest(`/admin/jobs/${instanceId}`, {
        method: 'PATCH',
        credentials: "include",
        headers: headers,
        body: JSON.stringify(data)
    })
        .finally(() => {
            tbody.classList.remove('disabled');
        })
}

// running jobs
delegate(table, 'click', 'button.run-job', function (_e) {
    const tbody = this.closest('tbody[data-instance-id]');
    const instanceId = tbody.dataset.instanceId;

    tbody.classList.add('disabled');

    doRequest(`/admin/jobs/${instanceId}/run`, {method: 'GET'})
        .then(rsp => {
            let iconClass;
            switch (rsp.status) {
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

            vexInstance.rootEl.querySelector('.btn-close').addEventListener('click', () => {
                vexInstance.close();
            });

            const html = htmlToElement(rsp.html);
            tbody.replaceWith(html);

        })
        .finally(() => {
            tbody.classList.remove('disabled');
        });
});

// deleting instances
delegate(table, 'click', 'button.delete-instance', function (_e) {
    const tbody = this.closest('tbody[data-instance-id]');

    /**
     * @var {object} data
     * @property {int} instanceId
     */
    const data = readData(tbody);

    tbody.classList.add('disabled');

    doRequest(`/admin/jobs/${data.instanceId}`, {
        method: 'DELETE',
        credentials: "include",
        headers: getCSFRHeader(),
    })
        .then(rspData => {
            if (rspData.action === 'replace') {
                const html = htmlToElement(rspData.html);
                tbody.replaceWith(html);
            } else {
                tbody.remove();
            }
        })
        .finally(() => tbody.classList.remove('disabled'));
})

function getCSFRToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
