<?php
    //ini_set('error_reporting', E_ALL);
    //ini_set('display_errors', 1);

	$fbconfig['appid'] = "291971787673791"; // app ID o API key
	$fbconfig['secret'] = "9de22d8da1461f097df3f9cda0b9fecd"; // codigo secreto de la aplicacion

	$fbconfig['baseUrl'] = "http://www.220v.ec/maquinacpquito/"; // aqui va la url donde esta alojada la app
	$fbconfig['appBaseUrl'] = "http://apps.facebook.com/maquinacpquito"; // reemplazar miaplicacion con el "Espacio de nombres" que se puso al crear la misma
	$fbconfig['pageUrl'] = "http://www.facebook.com/220V"; // reemplazar fanpageXXX con el nombre o direccion de la fanpage donde ira la app
	$fbconfig['appPageUrl'] = $fbconfig['pageUrl']."?sk=app_".$fbconfig['appid']; // NO MODIFICAR AQUI

	$fbconfig['pageId'] = '160136950740859'; // El id de la FanPage se obtiene del link de editar informacion del fanpage

	$fbconfig['debug'] = false;

	$cfg_host = "localhost";
	$cfg_user = "energycl_cpquito";
	$cfg_pass = "cpquito";
	$cfg_base = "energycl_maquinacpquito";

	// Configuraciones Twitter

	$twconfig['consumer'] = "G7tTWgN3Bgiby5u97hcNKyRed";
	$twconfig['secret'] = "6ZAsrMJrjk0OqtT4Qpj7Xh8c9xolDTEpaBKHvWhwjUh3sdF16m";
	$twconfig['callback'] = "http://www.220v.ec/campus-party/cb.php";
?>