// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDAIduxCnC5T4-QoDEw1vRjnI1_1K0odeg",
    authDomain: "hudsonvillewaterpolo.firebaseapp.com",
    projectId: "hudsonvillewaterpolo",
    storageBucket: "hudsonvillewaterpolo.appspot.com",
    messagingSenderId: "351470653163",
    appId: "1:351470653163:web:1572d4340e67fe6cf9b8ae",
    measurementId: "G-Z86EW7BNVM"
};

const vapidKey = "BLI72rxe6N0nax_-6yAf26EaCtnVoc8AdUrnbwkzD8vQBeZlcpgtAK6WOD4qQIlnJqAv9AMhiNZWypAsc5CJPsc";

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);


// region Registration

const notificationPermissions = Notification.permission;

const unsubscribedKey = 'fcm.wasUnsubscribed';

export function isSupported() {
    return "serviceWorker" in navigator && "PushManager" in window;
}

export function isPermissionGranted () {
    return notificationPermissions === 'granted';
}

export function isPermissionBlocked() {
    return notificationPermissions === 'blocked';
}

export function isPermissionDefault() {
    return notificationPermissions === 'default';
}

export function wasUnsubscribed() {
    return !!localStorage.getItem(unsubscribedKey);
}

/**
 * Sends to the server and saves the token
 *
 * @return {Promise<{subscribeResponse: *, token: string}>}
 */
export async function doSubscription() {
    const currentToken = await getToken(messaging, { vapidKey });
    if (currentToken) {
        // send the token to your server and update the UI if necessary
        try {
            const subscribeResponse = await sendSubscriptionRequest('POST', currentToken);
            localStorage.removeItem(unsubscribedKey);

            return {
                token: currentToken,
                subscribeResponse
            };
        } catch (err) {
            throw new SaveError('Sorry, but there was an error saving the token to the database. Please try again later');
        }
    } else {
        throw new NoTokenError('No registration token available, request permission to generate one');
    }
}

/**
 * Removes the token from the server
 *
 * @param token
 * @return {Promise<*>}
 */
export async function deleteSubscription(token) {
   const rsp = await sendSubscriptionRequest('DELETE', token);
   localStorage.setItem(unsubscribedKey, 'true');
   return rsp;
}

/**
 *
 * @param {'POST'|'DELETE'} method
 * @param {string} token
 * @return {Promise<any>}
 */
async function sendSubscriptionRequest(method, token) {
    const rsp = await fetch('/api/fcm/subscribe', {
        method: method,
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            token: token
        })
    });

    return await rsp.json();
}


export class SaveError extends Error {
    constructor(message) {
        super(message);
        this.name = "SaveError";
    }
}

export class NoTokenError extends Error {
    constructor(message) {
        super(message);
        this.name = "NoTokenError";
    }
}

// endregion

// region Message Handling

onMessage(messaging, (payload) => {
    console.log('Message received. ', payload);
    // ...
});

// endregion