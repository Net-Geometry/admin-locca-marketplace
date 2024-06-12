// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
apiKey: "AIzaSyCeaw_gVN0iQwFHyuF8pQ6PbVDmSVQw8AY",
  authDomain: "stackfood-bd3ee.firebaseapp.com",
  projectId: "stackfood-bd3ee",
  storageBucket: "stackfood-bd3ee.appspot.com",
  messagingSenderId: "1049699819506",
  appId: "1:1049699819506:web:a4b5e3bedc729aab89956b",
  measurementId: "G-2QNRKR9K5R"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});
