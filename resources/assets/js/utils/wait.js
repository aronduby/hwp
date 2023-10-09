export function wait(resolveWith = true, time = 3000) {
    return new Promise((resolve) => {
        setTimeout(() => resolve(resolveWith), time);
    })
}