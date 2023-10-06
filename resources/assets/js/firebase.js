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

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);


getToken(messaging, { vapidKey: "BLI72rxe6N0nax_-6yAf26EaCtnVoc8AdUrnbwkzD8vQBeZlcpgtAK6WOD4qQIlnJqAv9AMhiNZWypAsc5CJPsc" }).then(currentToken => {
    if (currentToken) {
        // send the token to your server and update the UI if necessary
        console.log(currentToken);
    } else {
        console.log('No registration token available, request permission to generate one')
    }
}).catch(err => {
    console.error('Error occurred while retrieving token', err);
});


onMessage(messaging, (payload) => {
    console.log('Message received. ', payload);
    // ...
});