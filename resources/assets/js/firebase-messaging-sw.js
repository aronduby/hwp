import { initializeApp } from "firebase/app";
import { getMessaging } from "firebase/messaging/sw";
import { onBackgroundMessage } from "firebase/messaging/sw";

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
onBackgroundMessage(messaging, (payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const notificationTitle = 'Background Message Title';
    const notificationOptions = {
        body: 'Background Message body.',
        icon: '/firebase-logo.png'
    };

    self.registration.showNotification(notificationTitle,
        notificationOptions);
});