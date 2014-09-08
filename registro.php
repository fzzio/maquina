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
      <link rel="stylesheet" type="text/css" href="assets/css/estilo.css">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1>Registro</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
            <button class="login btn btn-primary" id="login">Login</button>
        </div>
        <div class="col-md-4">
          <button class="logout btn btn-warning" id="logout">Logout</button>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <h1>Resultado:</h1>
          <div class="usuario" id="usuario"></div>
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