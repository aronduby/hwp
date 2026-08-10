import delegate from "@/utils/delegate.js";

/**
 * ADD/REMOVE STAT ROWS
 */
document.addEventListener('DOMContentLoaded', () => {

    delegate(document, 'click', 'button.add-row', function () {
        const section = this.closest('section');
        const tbody = section.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr');
        const lastRow = rows[rows.length - 1];
        const newRow = lastRow.cloneNode(true);
        const oldI = lastRow.dataset.i;
        const newI = Number(oldI) + 1;

        newRow.dataset.i = newI;

        newRow.querySelectorAll('input, select').forEach((field) => {
            const name = field.getAttribute('name').replace(oldI, newI);
            field.setAttribute('name', name);

            field.value = '';
        });

        tbody.appendChild(newRow);

        return false;
    });

    delegate(document, 'click', 'button.remove-row', function () {
        const row = this.closest('tr');
        removeRow(row);

        return false;
    });

    function emptyInputs(parent) {
        parent.querySelectorAll('input, select').forEach((field) => {
            field.value = '';
        });
    }

    function removeRow(row) {
        // trigger input to update the totals row
        row.querySelectorAll('input[type="number"]').forEach((field) => {
            field.value = '';
            field.dispatchEvent(new Event('input', {bubbles: true}));
        });

        if (row.parentElement.children.length === 1) {
            emptyInputs(row);

        } else {
            row.remove();
        }
    }
});

/**
 * Autogenerate Scores
 */
document.addEventListener('DOMContentLoaded', () => {
    let autogenerateScore = true;
    const keys = ['score_us', 'score_them'];
    const values = {};

    function setAutogenerateScore(enabled) {
        autogenerateScore = enabled;

        document.querySelectorAll('[data-autogenerate-score-status]').forEach((el) => {
            el.dataset.autogenerateScoreStatus = autogenerateScore ? 'on' : 'off';
        });

        // turning on should update everything
        if (autogenerateScore) {
            keys.forEach((key) => {
                updateValuesForKey(key);
                updateDisplayForKey(key);
            });

            updateScoreHeaders();
        }
    }

    document.querySelectorAll('input.autogenerate-score-toggle').forEach((toggle) => {
        toggle.addEventListener('change', function () {
            setAutogenerateScore(this.checked);
        });

        setAutogenerateScore(toggle.checked);
    });

    delegate(document, 'input', 'input[data-autogenerate-score-source]', function () {
        if (autogenerateScore) {
            const key = this.dataset.autogenerateScoreSource;

            updateValuesForKey(key);
            updateDisplayForKey(key);
            updateScoreHeaders();
        }
    });

    document.querySelectorAll('input.game-score').forEach((input) => {
        input.addEventListener('input', function () {
            const key = this.dataset.autogenerateScoreValue;

            values[key] = this.value;

            updateScoreHeaders();
        });
    });

    function updateValuesForKey(key) {
        values[key] = [...document.querySelectorAll(`input[data-autogenerate-score-source="${key}"]`)]
            .map((field) => Number(field.value))
            .reduce((acc, val) => acc + val, 0);
    }

    function updateDisplayForKey(key) {
        const val = values[key];
        document.querySelectorAll(`[data-autogenerate-score-value="${key}"]`)
            .forEach((field) => {
                if (field.matches('input, select')) {
                    field.value = val;
                } else {
                    field.textContent = val;
                }
            });
    }

    function updateScoreHeaders() {
        let us, them;

        if (values.score_us > values.score_them) {
            us = "win";
            them = "loss";
        } else if (values.score_us < values.score_them) {
            us = "loss";
            them = "win";
        } else {
            us = them = "tie";
        }

        document.querySelectorAll('.result--us').forEach((el) => el.dataset.result = us);
        document.querySelectorAll('.result--them').forEach((el) => el.dataset.result = them);
    }
});

/**
 * Autogenerate totals at the bottom of the table
 */
document.addEventListener('DOMContentLoaded', () => {
    delegate(document, 'input', '.stats-table--hasTotals td input[type="number"]', function () {
        const td = this.parentElement;
        const tdIdx = [...td.parentElement.children].indexOf(td);

        const table = this.closest('table');
        let sum = 0;
        table.querySelectorAll(`tbody td:nth-child(${tdIdx + 1}) input`)
            .forEach((field) => {
                const val = parseInt(field.value, 10);
                if (!isNaN(val)) {
                    sum += val;
                }
            });

        table.querySelector(`tfoot td:nth-child(${tdIdx + 1})`)
            .textContent = sum ? sum : '';
    });

    // trigger the handler to catch prefilled forms
    document.querySelectorAll('.stats-table--hasTotals tr:first-child td input[type="number"]')
        .forEach((field) => field.dispatchEvent(new Event('input', {bubbles: true})));
});
