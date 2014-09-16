<?php
  session_start();
  require_once('config.php');
  require_once('libs/twitteroauth/twitteroauth.php');
  include('libs/MySql.Class.php');
  require_once('funciones.php');

    $db = array(
        'host'=>$cfg_host,
        'user'=>$cfg_user,  
        'pass'=>$cfg_pass,  
        'name'=>$cfg_base
    );


  function publicarEnTwitter($usuario){
    /* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
    $access_token = array(
      "oauth_token" => $usuario["atoken"],
      "oauth_token_secret" => $usuario["oatoken"],
      "user_id"=> $usuario["id_str"],
      "screen_name"=> $usuario["screen_name"]
    );

    $_SESSION['access_token'] = $access_token;

    $connection = new TwitterOAuth($twconfig['consumer'], $twconfig['secret'], $usuario["atoken"], $usuario["oatoken"]);
    //$tweetMessage = 'Me recargué de energía con 220V para seguir disfrutando del ‪#‎CPQuito4‬';
    $tweetMessage = 'Tweet prueba';

    if (200 == $connection->http_code){
      //Check for 140 characters
      if(strlen($tweetMessage) <= 140){
        // Post the status message
        $connection->post('statuses/update', array('status' => $tweetMessage));
      } 
      return true;
    }else{
      return false;
    }
  }

	$arrExiste = array ();

	if (isset($_POST["txtCodigo"]) && !empty($_POST["txtCodigo"])){
    $codigoObtenido = str_pad($_POST["txtCodigo"] . "1", 5, "0", STR_PAD_LEFT);


		$sqlExisteCodigo = "
      SELECT uc.id as idregistro, uc.codigobarras AS codigobarras, uc.activado as activado, tw. * 
      FROM usuario_codigo_tw uc, twuser tw
      WHERE tw.id_str = uc.twuser_id
      AND uc.codigobarras = '" . $codigoObtenido . "'";
		$existeCodigo = @MySql::getInstance()->getSingleRow($sqlExisteCodigo);

		if($existeCodigo) {
      $posteado = publicarEnTwitter($existeCodigo);

        $sqlActivar = "
          UPDATE usuario_codigo_tw
          SET activado=1
          WHERE id = ". $existeCodigo["idregistro"];
        $activado = @MySql::getInstance()->updateRecord($sqlActivar);

        $arrExiste = array (
          'codigo'=> 1,
          'mensaje'=>"Código encontrado",
          'codigobarras'=> $existeCodigo["codigobarras"],
          'twuserid' => $existeCodigo["id_str"],
          'nombre' => $existeCodigo["name"],
          'posteado' => $posteado
        );

		}else{
			$arrExiste = array (
        'codigo'=> 0,
        'mensaje'=>"Error: Código o usuario no encontrado"
      );
		}
	}else{
    $arrExiste = array (
      'codigo'=> 0,
      'mensaje'=>"Error: No se recibió parámetros"
    );
  }

  header('Content-Type: application/json');
	echo json_encode($arrExiste);
?>