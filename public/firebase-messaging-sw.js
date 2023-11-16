importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');

importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
   
firebase.initializeApp({
    apiKey: "AIzaSyA8nJPvK8XFPdqULuAZD5bYzyy6nM61i1E",
    projectId: "flawless-psyche-393405",
    messagingSenderId: "531313352431",
    appId: "1:531313352431:web:ffa20a9ea9a9ec7f09451a"
});
  
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
