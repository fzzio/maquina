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
      <title>Activacion</title>
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
        <div class="col-md-12">
      
            <form role="form" method="post" action="verificar.php">
              <div class="form-group">
                <label for="txtCodigo">Codigo</label>
                <input type="text" class="form-control" name="txtCodigo" id="txtCodigo" placeholder="Codigo">
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
      </div>
    </div>
    <script type="text/javascript" src="assets/js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
  </body>
</html>