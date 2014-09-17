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

	$db = array(
		'host'=>$cfg_host,
		'user'=>$cfg_user,
		'pass'=>$cfg_pass,
		'name'=>$cfg_base
	);

  FacebookSession::setDefaultApplication( $fbconfig['appid'], $fbconfig['secret']);



  function publicarEnMuro($usuario){
    
    $session = new FacebookSession( $usuario["token_largo"] );
    // Validate the access_token to make sure it's still valid
    /*try {
      if ( ! $session->validate() ) {
        $session = null;
      }
    } catch ( Exception $e ) {
      // Catch any exceptions
      $session = null;
    }*/

    if($session) {
      try {
        $response = (new FacebookRequest(
          $session, 'POST', '/me/feed', array(
            'link' => 'www.220V.ec/campus-party/',
            'picture' => "www.220V.ec/campus-party/assets/img/200x200.jpg",
            'name' => 'La energía del campus party',
            //'caption' => 'este es el caption',
            'description' => 'La Energía del Campus Party. Escanea tu código de barras en la 220V energy machine para obtener una botella gratis de 220V en el Campus Party.',
            'message' => 'Me recargué de energía con 220V para seguir disfrutando del #CPQuito4',
          )
        ))->execute()->getGraphObject();

        //echo "Posted with id: " . $response->getProperty('id');
        return true;

      } catch(FacebookRequestException $e) {
        //echo "Exception occured, code: " . $e->getCode();
        //echo " with message: " . $e->getMessage();

        return false;
      }
    }

    return false;

  }



	$arrExiste = array ();

	if (isset($_POST["txtCodigo"]) && !empty($_POST["txtCodigo"])){
    $codigoObtenido = str_pad($_POST["txtCodigo"], 4, "0", STR_PAD_LEFT);


		$sqlExisteCodigo = "
      SELECT uc.id as idregistro, uc.codigobarras AS codigobarras, uc.activado as activado, fb. * 
      FROM usuario_codigo uc, fbuser fb
      WHERE fb.fbuser = uc.fbuser_id
      AND uc.codigobarras = '" . $codigoObtenido . "'";
		$existeCodigo = @MySql::getInstance()->getSingleRow($sqlExisteCodigo);

		if($existeCodigo) {
      $posteado = publicarEnMuro($existeCodigo);

      //print_r($existeCodigo );
      /*if($existeCodigo["activado"]){
        $arrExiste = array (
          'codigo'=> 2,
          'mensaje'=>"Código activado previamente",
          'codigobarras'=> $existeCodigo["codigobarras"],
          'fbuserid' => $existeCodigo["fbuser"],
          'nombre' => $existeCodigo["name"],
          'posteado' => $posteado
        );
      }else{*/
        $sqlActivar = "
          UPDATE usuario_codigo
          SET activado=1
          WHERE id = ". $existeCodigo["idregistro"];
        $activado = @MySql::getInstance()->updateRecord($sqlActivar);

        $arrExiste = array (
          'codigo'=> 1,
          'mensaje'=>"Código encontrado",
          'codigobarras'=> $existeCodigo["codigobarras"],
          'fbuserid' => $existeCodigo["fbuser"],
          'nombre' => $existeCodigo["name"],
          'posteado' => $posteado
        );
      /*}*/

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