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

	// Include twitteroauth

	$idcodigobarras = "00000";
	$arrReturn = array();

	/* If the oauth_token is old redirect to the connect page. */
	if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token'])
	{
		$_SESSION['oauth_status'] = 'oldtoken';
		header('Location: ./limpiar.php');
	}

	/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
	$connection = new TwitterOAuth($twconfig['consumer'], $twconfig['secret'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

	/* Request access tokens from twitter */
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

	/* Save the access tokens. Normally these would be saved in a database for future use. */
	$_SESSION['access_token'] = $access_token;

	/* If HTTP response is 200 continue otherwise send to connect page to retry */
	if (200 == $connection->http_code){
		$fechahoy = date('Y-m-d H:i:s');


		$account = $connection->get('account/verify_credentials');

		$TWid_str = safe_string_escape($account->id_str);
		$TWname = safe_string_escape($account->name);
		$TWprofile_image_url = $account->profile_image_url;
		$TWscreen_name = safe_string_escape($account->screen_name);
		$TWfriends_count = $account->friends_count;
		$TWfollowers_count = $account->followers_count;
		$TWurl = safe_string_escape($account->url);
		$TWdescription = safe_string_escape($account->description);
		$TWfollowing = $account->following;
		$TWlocation = $account->location;
		$TWstatuses_count = $account->statuses_count;
		$TWfecha_registro = $fechahoy;

		$TWatoken = $_SESSION['oauth_token'];
		$TWoatoken = $_SESSION['oauth_token_secret'];


		//Verificamos que exista previamente en la base
		$sqlObtenerUltimoUsuario = "
			SELECT *
			FROM twuser t
			WHERE t.id_str='" . $TWid_str . "'";
		$usuarioDatos = @MySql::getInstance()->getSingleRow($sqlObtenerUltimoUsuario);

		if( !$usuarioDatos ){
			$sqlUsuario = "
			    INSERT IGNORE INTO
			    twuser
				(id_str, name, profile_image_url, screen_name, friends_count, followers_count, url, description, following, location, statuses_count, fecha_registro, atoken, oatoken)
				VALUES
				('$TWid_str', '$TWname', '$TWprofile_image_url', '$TWscreen_name', '$TWfriends_count', '$TWfollowers_count', '$TWurl', '$TWdescription', '$TWfollowing', '$TWlocation', '$TWstatuses_count', '$TWfecha_registro', '$TWatoken', '$TWoatoken')
			";

			// Insertamos el nuevo usuario en la base de datos
			$idpersona = @MySql::getInstance()->executeQuery($sqlUsuario);

			$sqlObtenerUltimoUsuario = "
				SELECT *
				FROM twuser t
				WHERE t.id_str='" . $TWid_str . "'";
			$usuarioDatos = @MySql::getInstance()->getSingleRow($sqlObtenerUltimoUsuario);

			$idcodigobarras = str_pad($usuarioDatos["id"] . "1", 5, "0", STR_PAD_LEFT); //el ultimo 1 es para TW
			$sqlExisteCodigo = "
				SELECT *
				FROM usuario_codigo_tw
				WHERE codigobarras='" . $idcodigobarras . "'
				AND twuser_id='" . $TWid_str . "'";
			$existeCodigo = @MySql::getInstance()->getSingleRow($sqlExisteCodigo);

			if (!$existeCodigo) {
				$sqlCodigo = "
				    INSERT IGNORE INTO
				    usuario_codigo_tw
					(twuser_id, codigobarras, activado)
					VALUES
					('$TWid_str', $idcodigobarras, 0)
				";
				$idcodigopersona = @MySql::getInstance()->executeQuery($sqlCodigo);
			}

			$arrReturn = array (
				'codigo'=> 1,
				'mensaje'=>"Codigo generado",
				'codigobarras'=> $idcodigobarras
			);
		}else{
			$idcodigobarras = str_pad($usuarioDatos["id"] . "1", 5, "0", STR_PAD_LEFT); //el ultimo 1 es para TW
			$sqlExisteCodigo = "
				SELECT *
				FROM usuario_codigo_tw
				WHERE codigobarras='" . $idcodigobarras . "'
				AND twuser_id='" . $TWid_str . "'";
			$existeCodigo = @MySql::getInstance()->getSingleRow($sqlExisteCodigo);
		}


		
		header("Location: index-tw.php?c=" . $idcodigobarras);
		//dump($_SESSION);
		//dump($account);
	}else{
		//problema
		header("Location: index-tw.php");
	}

	/* Remove no longer needed request tokens */
	unset($_SESSION['oauth_token']);
	unset($_SESSION['oauth_token_secret']);

	// Set status message
	//$tweetMessage = 'Prueba.';

	// Check for 140 characters
	//if(strlen($tweetMessage) <= 140)
	//{
	    // Post the status message
	    //$connection->post('statuses/update', array('status' => $tweetMessage));
	//}	
?>