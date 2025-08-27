<?php
	/* 
		Routeur du CMS
		@Author Yves P.
		@Version 1.0
		@Date Création: 14/08/2023
		@Dernière modification: 27/08/2025
	*/
	
	// Configuration sessions sécurisées
	ini_set('session.use_strict_mode', 1);
	ini_set('session.cookie_httponly', 1);
	ini_set('session.cookie_samesite', 'Strict');

	if ($_SERVER['SERVER_NAME'] !== 'localhost')
		ini_set('session.cookie_secure', 1);
	
	session_start();

	try {		
		// Chargement automatique des classes
		require_once 'config/config.ini.php';

		//set_exception_handler(['ExceptionHandler', 'handleException']);

		$routes = include 'config/routes.php';

		// Récupération de l'URL demandée
		$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		// Nettoyage de l'URL par rapport à BASE_URL
		$uriSansBase = substr($requestUri, strlen(BASE_URL));

		// 1. Gestion spécifique pour /private/* non définies : 404 direct
		if (str_starts_with($uriSansBase, "private")) {
			$privateRouteExists = false;
			foreach ($routes as $routeUrl => $routeInfo) {
				if (str_starts_with($routeUrl, BASE_URL."private")) {
					// Création regex pour la route
					$regexRouteUrl = preg_replace('/{([^}]+)}/', '([^/]+)', $routeUrl);
					$regexRouteUrl = str_replace('/', '\/', $regexRouteUrl);
					$regexRouteUrl = '/^'.$regexRouteUrl.'\/?$/';

					if (preg_match($regexRouteUrl, $requestUri)) {
						$privateRouteExists = true;
						break;
					}
				}
			}

			if (!$privateRouteExists) {
				// Route /private/* inconnue => 404
				header("Location: ".BASE_URL."private/home");
				exit;
			}
		}

		// 2. Recherche normale dans les routes
		$matchedRoute = null;
		foreach ($routes as $routeUrl => $routeInfo) {
			$regexRouteUrl = preg_replace('/{([^}]+)}/', '([^/]+)', $routeUrl);
			$regexRouteUrl = str_replace('/', '\/', $regexRouteUrl);
			$regexRouteUrl = '/^'.$regexRouteUrl.'\/?$/';

			if (preg_match($regexRouteUrl, $requestUri, $matches)) {
				$matchedRoute = $routeInfo;
				$params = array_slice($matches, 1);
				break;
			}
		}

		if ($matchedRoute !== null) {
		    $controllerName = $matchedRoute['controller'];
		    $actionName = $matchedRoute['action'];

			// Authentification requise
			$authRequired = isset($matchedRoute['auth']) ? $matchedRoute['auth'] : true;

			if ($authRequired && str_starts_with($requestUri, BASE_URL . "private")) {
				if (!isset($_SESSION['isLog'])) {
					header("Location: ".BASE_URL."private");
					exit;
				}
			}

		    // Instanciation du contrôleur et appel de l'action correspondante
		    $controller = new $controllerName();

		    call_user_func_array([$controller, $actionName], $params);
		} else {
		    // Route non trouvée (hors private), 404 ou redirection possible ici
		    header('HTTP/1.0 404 Not Found');
		    
		    exit;
		}
	}
	catch (Exception $e) {
		ExceptionHandler::HandleException($e);
	}
?>