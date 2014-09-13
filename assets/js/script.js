function openFbPopUp() {
    //event.preventDefault();
    FB.ui(
      {
        method: 'feed',
        name: 'Prueba',
        link: 'www.google.com',
        picture: 'http://placehold.it/200x200',
        caption: 'Participa tu también',
        description: 'Texto descriptivo'
      },
      function(response) {
        if (response && response.post_id) {
          alert('Post was published.');
        } else {
          alert('Post was not published.');
        }
      }
    );
}


// Load the SDK asynchronously
(function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/es_LA/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));


 // This is called with the results from from FB.getLoginStatus().
function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
        // Logged into your app and Facebook.
        //testAPI();
        verificarLogueado();
    } else if (response.status === 'not_authorized') {
        // The person is logged into Facebook, but not your app.
        
    } else {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
        console.log("Por favor loogueate dentro de Facebook");
        
    }
}

// This function is called when someone finishes with the Login
// Button.  See the onlogin handler attached to it in the sample
// code below.
function checkLoginState() {
    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });
}


// Here we run a very simple test of the Graph API after login is successful. 
// This testAPI() function is only called in those cases. 
function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
        console.log('Good to see you, ' + response.name + '.');
    });
}

function verificarLogueado(){   
    FB.api('/me', function(response) {
        console.log("El id del usuario es: " + response.id);
       
    }); 
}


// Funcion para logarse con Facebook.
function login() {
    fb.login(function(){ 
        if (fb.logged) {
            console.log('Bienvenido, ' + fb.user.name + '.');
        } else {
            console.log("No se pudo identificar al usuario");
        }
    })
};

function conectarFacebook(){
    FB.login(function(response){
        if (response.authResponse){
            console.log('Bienvenido, ahora se obtienen los datos');

            var accessTokenS = response.authResponse.accessToken;

            FB.api('/me', function(response){
                console.log('Se obtuvieron los datos, bienvenido, ' + response.name + '.');

                var parametros = {
                    datafb: response,
                    accessTokenS: accessTokenS
                }

                $.ajax({
                    url: "procesar.php",
                    type: 'POST',
                    async: true,
                    data: parametros,
                    dataType: "json",
                    success: function (respuesta) {
                        if(respuesta.codigo == 1){
                            
                            $("#nombre-usuario").html(response.name);
                            $("#img-barras").attr( "src", "libs/barcodegen/test_1D.php?text=" + respuesta.codigobarras );
                            $("#seccion-registro").hide();
                            $("#seccion-codigo").show();
                        }else{
                            //$("#usuario").html("<p>No se pudo generar el código</p>");
                        }
                        //console.log(respuesta.precio);
                    }, 
                    error: function (error) {
                        console.log("ERROR: " + error);
                        $("#usuario").html("<p>No se pudo generar el código</p>");
                    }
                });


                //var html = "<p>" + response.name + "</p><br/><img src='libs/barcodegen/test_1D.php?text=" + response.id + "' alt='barcode' class='img-responsive'  />";


            });
        }else{
            console.log('El usuario ha cancelado los permisos, etc. y tenemos que redireccionar a otro lado.');
            //location.href = "index.php";
        }
    },{
        scope: 'publish_actions, email'
    });
}

function logoutFB(){
    FB.logout(function(response) {
      // user is now logged out
        $("#seccion-registro").show();
        $("#seccion-codigo").hide();
    });
}



$(document).ready(function() {
  
    $("#login").click(function(e){
        e.preventDefault();
        conectarFacebook();
    });


    $("#logout").click(function(e){
        e.preventDefault();
        logoutFB();
    });


});
