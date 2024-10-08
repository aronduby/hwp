import {
    deleteSubscription,
    doSubscription,
    isPermissionBlocked,
    isPermissionDefault,
    isSupported,
    isInstalled,
    wasUnsubscribed,
    NoTokenError,
} from "./firebase";

let token;

document.addEventListener('DOMContentLoaded', async () => {

    const notificationActions = document.getElementById('notificationActions');

    // wait till it's visible, so you get the full animation fanciness
    const observer = new IntersectionObserver(async (entries) => {
        const shouldStart = entries.some(e => e.isIntersecting);
        if (shouldStart) {
            observer.disconnect();

            if (!isSupported()) {
                // ios (maybe others?) has to be installed before its supported, so add this additional check
                if (!isInstalled()) {
                    notificationActions.dataset.state = 'not-installed';
                } else {
                    notificationActions.dataset.state = 'not-supported';
                }
            } else if (isPermissionBlocked()) {
                notificationActions.dataset.state = 'blocked';
            } else if (isPermissionDefault() || wasUnsubscribed()) {
                notificationActions.dataset.state = 'not-subscribed';
            } else {
                await trySubscribing();
            }
        }
    });
    observer.observe(notificationActions);

    // subscribe button clicks
    document.getElementById('fcm-subscribe').addEventListener('click', async (e) => {
        const btn = e.currentTarget;
        btn.classList.add('loading');
        btn.disabled = true;

        try {
            await trySubscribing();
        } finally {
            btn.classList.remove('loading');
            btn.disabled = false;
        }
    });

    document.getElementById('fcm-unsubscribe').addEventListener('click', async (e) => {
        e.preventDefault();
        e.stopPropagation();

        notificationActions.dataset.state = 'loading';
        try {
            await deleteSubscription(token);

            notificationActions.querySelector('.state--not-subscribed')
                .classList.add('state--unsubscribed');

            notificationActions.dataset.state = 'not-subscribed';
            token = null;
        } catch (err) {
            notificationActions.dataset.state = 'error';
            console.error(err);
        }
    });

    async function trySubscribing() {
        try {
            const rsp = await doSubscription();
            token = rsp.token;
            notificationActions.dataset.state = 'subscribed';
        } catch (err) {
            if (err instanceof NoTokenError) {
                notificationActions.dataset.state = 'not-subscribed';
            } else {
                notificationActions.dataset.state = 'error';
                console.error(err);
            }
        }
    }
});