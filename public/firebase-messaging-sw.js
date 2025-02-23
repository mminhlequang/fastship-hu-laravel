// Import and configure the Firebase SDK
// These scripts are made available when the app is served or deployed on Firebase Hosting
// If you do not serve/host your project using Firebase Hosting see https://firebase.google.com/docs/web/setup
importScripts('https://www.gstatic.com/firebasejs/5.7.1/firebase.js');
var config = {
    apiKey: "AIzaSyAXv07sWPA1Z0TOwYcBnNAohOP7EFhRLdM",
    authDomain: "xehue-90c4e.firebaseapp.com",
    databaseURL: "https://xehue-90c4e.firebaseio.com",
    projectId: "xehue-90c4e",
    storageBucket: "xehue-90c4e.appspot.com",
    messagingSenderId: "347324955632"
};
firebase.initializeApp(config);
const messaging = firebase.messaging();

/**
 * Here is is the code snippet to initialize Firebase Messaging in the Service
 * Worker when your app is not hosted on Firebase Hosting.
 // [START initialize_firebase_in_sw]
 // Give the service worker access to Firebase Messaging.
 // Note that you can only use Firebase Messaging here, other Firebase libraries
 // are not available in the service worker.
 importScripts('https://www.gstatic.com/firebasejs/4.8.1/firebase-app.js');
 importScripts('https://www.gstatic.com/firebasejs/4.8.1/firebase-messaging.js');
 // Initialize the Firebase app in the service worker by passing in the
 // messagingSenderId.
 firebase.initializeApp({
   'messagingSenderId': 'YOUR-SENDER-ID'
 });
 // Retrieve an instance of Firebase Messaging so that it can handle background
 // messages.
 const messaging = firebase.messaging();
 // [END initialize_firebase_in_sw]
 **/


// If you would like to customize notifications that are received in the
// background (Web app is closed or not in browser focus) then you should
// implement this optional method.
// [START background_handler]
//Chỉ hoạt động override khi data không có trường notification
//{notification:{...}, data:{...}
messaging.setBackgroundMessageHandler(function(payload) {
    return self.registration.showNotification(payload.notification.title, {
        body: payload.notification.body,
        tag: payload.collapse_key,
        click_action: payload.notification.click_action
    });
});
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(self.clients.openWindow(event.notification.click_action));
});
// [END background_handler]