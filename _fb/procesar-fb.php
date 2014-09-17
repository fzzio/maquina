<?php
	session_start();

	include("config.php");
	include('libs/MySql.Class.php');
	include("funciones.php");

	// Must pass session data for the library to work (only if not already included in your app)

	// Define the root directoy
	define( 'ROOT', dirname( __FILE__ ) . '/' );

	// Autoload the required files
	require_once( ROOT . 'libs/autoload.php' );

	use Facebook\FacebookRequest;
	use Facebook\GraphObject;
	use Facebook\FacebookSession;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\Entities\AccessToken;

	$db = array(
		'host'=>$cfg_host,
		'user'=>$cfg_user,
		'pass'=>$cfg_pass,
		'name'=>$cfg_base
	);

	FacebookSession::setDefaultApplication( $fbconfig['appid'], $fbconfig['secret']);




	$idcodigobarras = "00000";
	$arrReturn = array();

	if (isset($_POST["datafb"]) && !empty($_POST["datafb"])){
		$userInfo = $_POST["datafb"];

		$session = new FacebookSession( $_POST["accessTokenS"] );
		/// 
		// Generamos Tokens de larga duracion en base a los de corta duracion recibidos
		$accessToken = $session->getAccessToken();
		$longLivedAccessToken = $accessToken->extend();

		
		$ifbuser = $userInfo["id"];
		$iname = safe_string_escape($userInfo["name"]);
		$ifirst_name = safe_string_escape($userInfo["first_name"]);
		$ilast_name = safe_string_escape($userInfo["last_name"]);
		$ilink = $userInfo["link"];
		//$iusername = "$userInfo["username"]";
		$iusername = "";
		if (!empty($userInfo["birthday"]))
		    $ibirthday = date('Y-m-d', strtotime(str_replace('-','/',$userInfo["birthday"])));
		else
		    $ibirthday = "0000-00-00";
		//$ihometown_id = $userInfo["hometown"]["id"];
		$ihometown_id = "";
		//$ihometown_name = $userInfo["hometown"]["name"];
		$ihometown_name = "";
		//$ibio = safe_string_escape($userInfo["bio"]);
		$ibio = safe_string_escape("");
		$igender = $userInfo["gender"];
		$iemail = $userInfo["email"];
		$itimezone = $userInfo["timezone"];
		$ilocale = $userInfo["locale"];

		
		// Insertamos el nuevo usuario en la base de datos
		$sqlUsuario = "
		    INSERT IGNORE INTO
		    fbuser
			(fbuser, name, first_name, last_name, link, username, birthday, hometown_id, hometown_name, bio, gender, email, timezone, locale, token_comun, token_largo)
			VALUES
			('$ifbuser', '$iname', '$ifirst_name', '$ilast_name', '$ilink', '$iusername', $ibirthday, '$ihometown_id', '$ihometown_name',
			'$ibio', '$igender', '$iemail', '$itimezone', '$ilocale', '$accessToken', '$longLivedAccessToken')
		";

		// Insertamos el nuevo usuario en la base de datos
		$idpersona = @MySql::getInstance()->executeQuery($sqlUsuario);

		//Verificamos que exista previamente en la base
		$sqlObtenerUltimoUsuario = "
			SELECT *
			FROM fbuser f
			WHERE f.fbuser='" . $ifbuser . "'";
		$usuarioDatos = @MySql::getInstance()->getSingleRow($sqlObtenerUltimoUsuario);

		if( !$usuarioDatos ){
			$arrReturn = array (
				'codigo'=> 0,
				'mensaje'=>"Error, no se encontro usuario"
			);
		}else{
			$idcodigobarras = str_pad($usuarioDatos["id"], 4, "0", STR_PAD_LEFT);
			$sqlExisteCodigo = "
				SELECT *
				FROM usuario_codigo
				WHERE codigobarras='" . $idcodigobarras . "'
				AND fbuser_id='" . $ifbuser . "'";
			$existeCodigo = @MySql::getInstance()->getSingleRow($sqlExisteCodigo);



			if (!$existeCodigo) {
				$sqlCodigo = "
				    INSERT IGNORE INTO
				    usuario_codigo
					(fbuser_id, codigobarras, activado)
					VALUES
					('$ifbuser', $idcodigobarras, 0)
				";
				$idcodigopersona = @MySql::getInstance()->executeQuery($sqlCodigo);
			}

			$arrReturn = array (
				'codigo'=> 1,
				'mensaje'=>"Codigo generado",
				'codigobarras'=> $idcodigobarras
			);
		}

	}else{
		$arrReturn = array (
			'codigo'=> 0,
			'mensaje'=>"Error, no se recibieron datos"
		);
	}
	
	// retornamos la respuesta de JSON
	header('Content-Type: application/json');
	echo json_encode($arrReturn);
?>