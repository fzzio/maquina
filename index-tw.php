<?php
    session_start();
    include("config.php");
    include('libs/MySql.Class.php');
    require_once('funciones.php');

    $db = array(
        'host'=>$cfg_host,
        'user'=>$cfg_user,  
        'pass'=>$cfg_pass,  
        'name'=>$cfg_base
    );
    $usuarioTW = 0;

    if (isset($_GET["c"]) && !empty($_GET["c"])) {
      
      $idcodigobarras = str_pad($_GET["c"], 5, "0", STR_PAD_LEFT);
      $sqlExisteCodigo = "
        SELECT *
        FROM usuario_codigo_tw
        WHERE codigobarras='" . $idcodigobarras . "'";
      $existeCodigo = @MySql::getInstance()->getSingleRow($sqlExisteCodigo);

      //dump($existeCodigo);

      $sqlObtenerUltimoUsuario = "
        SELECT *
        FROM twuser t
        WHERE t.id_str='" . $existeCodigo["twuser_id"] . "'";
      $usuarioTW = @MySql::getInstance()->getSingleRow($sqlObtenerUltimoUsuario);
    }

    //dump($usuarioTW);
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
      <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">
      <link rel="stylesheet" type="text/css" href="assets/css/social-buttons.css">
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

          <?php if ($usuarioTW): ?>

            <div class="container" id="seccion-codigo" style="display: block;">
              <div class="row">
                <div class="col-md-12">
                  <h3 class="copyRecargate text-center">Recárgate de energía <span class="nombre-usuario" id="nombre-usuario"><?php echo $usuarioTW["name"]; ?></span> mostrando el código de barras en el lector del dispensador.</h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <img src="libs/barcodegen/test_1D.php?text=<?php echo $existeCodigo['codigobarras']; ?>" class="img-responsive img-centrar" id="img-barras" />
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-center">
                  <a href="limpiar.php" class="btn-logout btn btn-warning" id="logout">Logout</a>
                </div>
              </div>
            </div>

          <?php else: ?>
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
                  <a href="redirect.php" class="btn btn-twitter">
                    <i class="fa fa-twitter"></i> | Iniciar con Twitter
                  </a>
                </div>

              </div>
            </div>            
          <?php endif ?>



          <div class="container">
            <div class="row">
              <div class="col-md-12 text-center">
                <h4 class="titularObtener text-center">Será publicado en twitter tu asistencia al evento.</h4>
              </div>
            </div>
          </div>
        
      </div>
    </div>
    <script type="text/javascript" src="assets/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/script-tw.js"></script>
  </body>
</html>