export default function delegate(root, eventType, selector, handler) {
    root.addEventListener(eventType, (e) => {
        const target = e.target.closest(selector);
        if (target && root.contains(target)) {
            handler.call(target, e);
        }
    });
}
