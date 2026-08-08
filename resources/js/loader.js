function _load(tag) {
    return function (url, preload = false) {
        // This promise will be used by Promise.all to determine success or failure
        return new Promise(function (resolve, reject) {
            let element = document.createElement(tag);
            let parent = 'body';
            let attr = 'src';

            // Important success and error for the promise
            element.onload = function () {
                resolve(url);
            };
            element.onerror = function () {
                reject(url);
            };

            // Need to set different attributes depending on tag type
            switch (tag) {
                case 'script':
                    element.async = true;
                    break;
                case 'link':
                    element.type = 'text/css';
                    element.rel = 'stylesheet';
                    attr = 'href';
                    parent = 'head';
                    break;
                case 'img':
                    element.origin = 'anonymous';
                    element.crossOrigin = 'anonymous';
                    break;
            }

            // Inject into document to kick off loading
            element[attr] = url;
            if (!preload) {
                document[parent].appendChild(element);
            }
        });
    };
}

export default {
    css: _load('link'),
    js: _load('script'),
    img: _load('img')
}
