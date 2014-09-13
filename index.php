<?php
    session_start();
    include("config.php");
    include('libs/MySql.Class.php');

    $db = array(
        'host'=>$cfg_host,
        'user'=>$cfg_user,  
        'pass'=>$cfg_pass,  
        'name'=>$cfg_base
    );
?>
<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Registro</title>
      <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="assets/fonts/font-newtow.css">
      <link rel="stylesheet" type="text/css" href="assets/css/estilo.css">
  </head>
  <body>
    <div class="v-center-contenedor">
      <div class="v-center-contenido">

          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <img src="assets/img/Logo-220V-CP.png" alt="" class="img-responsive img-centrar img-logo">
              </div>
            </div>
          </div>

          <div class="container" id="seccion-registro">
            <div class="row">
              <div class="col-md-12 text-center">                
                <h1 class="titularEnergia">
                  La energía del<br /> Campus Party
                </h1>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <h3 class="titularObtener text-center">Obtener el código de barras:</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-center">
                <?php /* <button class="btn-login btn btn-primary" id="login">&nbsp;</button> */ ?>
                <img src="assets/img/Boton-FConnect.png" class="img-responsive img-centrar btn-login" id="login" />
              </div>
            </div>
          </div>

          <div class="container" id="seccion-codigo">
            <div class="row">
              <div class="col-md-12">
                <h3 class="copyRecargate text-center">Recárgate de energía <span class="nombre-usuario" id="nombre-usuario"></span> mostrando el código de barras en el lector del dispensador.</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <img src="" class="img-responsive img-centrar" id="img-barras" />
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-center">
                <button class="btn-logout btn btn-warning" id="logout">Logout</button>
              </div>
            </div>


          </div>
        
      </div>
    </div>
    <div id="fb-root"></div>
      <script src="https://connect.facebook.net/en_US/all.js"></script>
      <script type="text/javascript">
         window.fbAsyncInit = function() {
            FB.init ({
               appId : "<?php echo $fbconfig['appid']; ?>", //Your facebook APP here
               status : true,
               xfbml : true,
               cookie : true, // enable cookies to allow the server to access the session
            });
           
            FB.getLoginStatus(function(response) {
              statusChangeCallback(response);
            });
         }
      </script>

    <script type="text/javascript" src="assets/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
  </body>
</html>