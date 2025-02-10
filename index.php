<?php
	/* 
		Routeur du CMS
		@Author Yves P.
		@Version 1.0
		@Date Création: 14/08/2023
		@Dernière modification: 22/10/2023
	*/
	
	session_start();
	
	// Chargement automatique des classes
	require_once 'config/config.ini.php';

	//set_exception_handler(['ExceptionHandler', 'handleException']);

	$routes = include 'config/routes.php';

	// Récupération de l'URL demandée
	$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

	// Vérification si la route existe en parcourant les routes
	$matchedRoute = null;

	foreach ($routes as $routeUrl => $routeInfo) {
	    // Convertir les segments dynamiques en expressions régulières
	    $regexRouteUrl = preg_replace('/{([^}]+)}/', '([^/]+)', $routeUrl);
	    $regexRouteUrl = str_replace('/', '\/', $regexRouteUrl);
	    $regexRouteUrl = '/^' . $regexRouteUrl . '$/';

	    // Vérifier si l'URL demandée correspond à la route actuelle
	    if (preg_match($regexRouteUrl, $requestUri, $matches)) {
	        $matchedRoute = $routeInfo;

	        // Stocker les valeurs des paramètres dynamiques dans un tableau
        	$params = array_slice($matches, 1);

	        break;
	    }
	}

	try {
		if ($matchedRoute !== null) {
		    $controllerName = $matchedRoute['controller'];
		    $actionName = $matchedRoute['action'];

		    // Instanciation du contrôleur et appel de l'action correspondante
		    $controller = new $controllerName();

		    // Passer les paramètres dynamiques au contrôleur
        	call_user_func_array([$controller, $actionName], $params);
		} 
		else {
			/*
		    // Gestion de la page 404
		    header('HTTP/1.0 404 Not Found');
		    echo 'Page not found';
		    */

		    var_dump(BASE_URL);
		}
	}
	catch (Exception $e) {
		echo $e;
		//ExceptionHandler::HandleException($e);
	}
?>