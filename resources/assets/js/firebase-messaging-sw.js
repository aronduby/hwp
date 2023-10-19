import { initializeApp } from "firebase/app";
import { getMessaging } from "firebase/messaging/sw";
import { onBackgroundMessage } from "firebase/messaging/sw";

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
// noinspection SpellCheckingInspection
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
    // we can't use top level payload notification now since that triggers fcm notification which creates double
    // instead we're going to look for notification within the data level of things
    const notificationData = JSON.parse(payload.data.notification);

    // Customize notification here
    const notificationTitle = notificationData.title;
    const notificationOptions = {
        body: notificationData.body,
        icon: '/icons/android-chrome-192x192.png'
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener('notificationclick', function(event){

    // console.log('notification clicked', event.notification.tag);

    // Android doesn't close the notification when you click on it
    // See: http://crbug.com/463146
    event.notification.close();

    // Looks to see if the current window is already open and focuses if it is
    event.waitUntil(
        self.clients.matchAll({ type: "window" })
            .then(function(clientList){
                for(let i=0; i<clientList.length; i++){
                    const client = clientList[i];
                    if(client.url === '/' && 'focus' in client)
                        return client.focus();
                }

                if(self.clients.openWindow){
                    return self.clients.openWindow('/');
                }
            })
    );

});