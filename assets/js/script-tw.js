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



$(document).ready(function() {
  
    /*$("#login").click(function(e){
        e.preventDefault();
        conectarFacebook();
    });*/


    /*$("#logout").click(function(e){
        e.preventDefault();
        logoutFB();
    });*/


});
