export default function getDataUri(url, type = 'image/png') {
    return new Promise((resolve, reject) => {
        const img = new Image();

        img.onload = function () {
            const c = document.createElement('canvas');
            c.width = this.naturalWidth;
            c.height = this.naturalHeight;

            c.getContext('2d').drawImage(this, 0, 0);

            try {
                resolve(c.toDataURL(type));
            } catch (e) {
                console.error(e);
                resolve('');
            }
        };

        img.crossOrigin = "anonymous";
        img.src = url;
    });
}
