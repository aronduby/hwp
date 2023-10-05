const trackingCategory = 'Scavenger Hunt';

export function trackStep(action, ...rest) {
    ga('send', 'event', trackingCategory, `Step ${action}`, ...rest);
}

export default {
    trackStep
}