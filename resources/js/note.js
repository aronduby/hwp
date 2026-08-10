import vex from 'vex-js';
vex.defaultOptions.className = 'vex-theme-default';

document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-note-id]');
    if (!trigger) {
        return;
    }

    e.preventDefault();

    const id = trigger.dataset.noteId;

    trigger.classList.add('loading');
    fetch(`/notes/${id}`)
        .then(rsp => rsp.text())
        .then(rsp => {
            vex.open({
                unsafeContent: rsp,
                className: 'vex-theme-note'
            });

            ga('send', 'event', 'notes', 'view', id);
        })
        .catch(err => {
            console.error(err);
            alert('could not load note');
        })
        .finally(() => {
            trigger.classList.remove('loading');
        });
});
