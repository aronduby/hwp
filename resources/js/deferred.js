export default class Deferred {

    promise;
    resolve;
    reject;

    constructor() {
        const { promise, resolve, reject } = Promise.withResolvers();

        this.promise = promise;
        this.resolve = resolve;
        this.reject = reject;
    }

}
