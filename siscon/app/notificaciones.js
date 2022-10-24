// Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  var firebaseConfig = {
    apiKey: "AIzaSyCU3tm_wu5dg_gFEgh4o5AIL7VDYrFVyUM",
    authDomain: "gosi-trabajo.firebaseapp.com",
    projectId: "gosi-trabajo",
    storageBucket: "gosi-trabajo.appspot.com",
    messagingSenderId: "94055130437",
    appId: "1:94055130437:web:07a61fa19032026ecf0a2c",
    measurementId: "G-V9X4ZM01Q2"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
  const messaging =firebase.messaging();


  window.onload=function(){
    pedirPermiso();
    let enableForegroundNotification=true;
    messaging.onMessage(function(payload){
        console.log("mensaje recibido");
        console.log(payload);
        if(enableForegroundNotification){
            const {title, body}=JSON.parse(payload.notification);
            console.log(title, body);
            navigator.serviceWorker.getRegistrations().then( registration =>{
                registration[0].showNotification(payload.notification.title, payload.notification.options);
            });
        }
    });
    messaging.onBackgroundMessage(function(payload){
        console.log("mensaje recibido");
        if(enableForegroundNotification){
            const {title, ...options}=JSON.parse(payload.data.notification);
            navigator.serviceWorker.getRegistrations().then( registration =>{
                registration[0].showNotification(title, options);
            });
        }
    });

    function pedirPermiso(){
        messaging.requestPermission()
        .then(function(){
            console.log("Se han haceptado las notificaciones");
            //hideAlert();
            return messaging.getToken({vapidKey: 'BFuZm_zTko2WvtX3MSYTu3lb-jocE2x70LQOpxpGkRQvYCXc5_cbRmpgHZhHYbdwWKPOL28CmuyXsvLJwY6e2Ds'});
        }).then(function(token){
            console.log(token);
            guardarToken(token);
        }).catch(function(err){
            console.log('No se ha recibido el permiso');
            console.log(err);
            //showAlert();
        });
    }
    function guardarToken(token){
        $("#tokenFB").val(token);
        $("#tokenFBR").val(token);
       localStorage.setItem('tokenFB',token);
    }
    function showAlert(){
       // document.querySelector("#alertaError").classList.remove('d-none');
    }
    function hideAlert(){
      //  document.querySelector("#alertaError").classList.add('d-none');
    }
  }//llave on load