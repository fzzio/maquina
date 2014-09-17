function enviarmail () {
    
    var correo= $("#220v_emails").val();
    //var codigo= $("#videoid").val();
    var parametros = {
        "emails" : correo,
        "nombre" : response.name,
        "urlcompartir" : $("#urlcompartir").val()
    };
    $.ajax({
        url: "enviarmail.php",
        type: 'post', 
        data: parametros,
        beforeSend: function () {
        },
        success: function(respuesta){ 
            //alert(respuesta);
            if (respuesta) {
                $("#enviar_mp3").html("<span class='texto-220V texto-400 texto-titulo'>¡EXCELENTE! EL CORREO HA SIDO ENVIADO.</span>");
                //alert("Enviado");
            }else{
                console.log("Error");
            };
        }
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
