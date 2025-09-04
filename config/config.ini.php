<?php
	/* 
		Fichier de configuration du CMS
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 04/05/2020
	    @Dernière modification: 04/09/2025
  	*/

	define("BASE_URL_LOCAL", "/Kodio/");
	define("BASE_URL_ONLINE", "/");

	if ($_SERVER['SERVER_NAME'] === 'localhost')
		define("BASE_URL", BASE_URL_LOCAL);
	else
		define("BASE_URL", BASE_URL_ONLINE);

	// Éléments du menu
	define ("MENU", array (
		"Accueil" => array (
			"icon" => "home",
			"link" => BASE_URL."private/home"
		),
		"Menu" => array (
			"icon" => "menu",
			"link" => BASE_URL."private/menu"
		),
		"Contenu" => array (
			"icon" => "file-text",
			"link" => BASE_URL."private/content"
		),
		"Utilisateurs" => array (
			"icon" => "users",
			"link" => BASE_URL."private/users"
		),
		"Étiquettes" => array (
			"icon" => "tag",
			"link" => BASE_URL."private/tags"
		),
		"Médiathèque" => array (
			"icon" => "image",
			"link" => BASE_URL."private/library"
		),
		"Paramètres" => array (
			"icon" => "settings",
			"link" => BASE_URL."private/settings"
		)
	));

	// Tableau pour les retours ajax
	$response = array();

	loadEnv(__DIR__.'/../.env'); // charge les variables avant toute autre logique

	// Chargement automatique des classes
	require_once 'config/autoload.php';

	// PAGINATION - ADMIN
	$itemsPerPages = 40;

	$options = [
		'range' => 2, 
	  	'posts_per_page' => $itemsPerPages,
	  	'text_first_page' => '&laquo;',
		'text_last_page' => '&raquo;',
		'text_next_page' => '&rsaquo;',
		'text_previous_page' => '&lsaquo;'
	];

	// Messages d'erreurs
	define("BAD_REQUEST_METHOD", "Erreur de méthode de requête");
	define("CATEGORY_ABBREVIATION_EMPTY", "L'abbréviation de la catégorie ne peut pas être vide");
	define("CATEGORY_ABBREVIATION_LENGTH", "L'abbréviation doit être comprise entre 2 et 10 caractères");
	define("CATEGORY_DEFAULT", "Une catégorie n'a pas correctement été définie");
	define("CATEGORY_DELETED", "Catégorie supprimée");
	define("CATEGORY_EMPTY", "Le libellé de la catégorie ne peut pas être vide");
	define("CATEGORY_LABEL_TOO_LONG_OR_TOO_SHORT", "L'intitulé doit être compris entre 4 et 50 caractères");
	define("CATEGORY_SUCCESS", "Catégorie ajoutée avec succès");
	define("CRITICAL_ERROR", "Erreure critique");
	define("EMAIL_EMPTY", "L'adresse email ne peut pas être vide");
	define("EMAIL_FORMAT", "Le format de l'adresse email n'est pas correct");
	define("EMPTY_FIELD_TRANSLATE", "Un des champs de traduction ne peut pas être vide");
	define("ERROR_MAIN_LANGUAGE", "Éléments obligatoires manquants dans la langue principale");
	define("FIELD_NOT_FOUND", "Un champs obligatoire n'a pas pu être trouvé");
	define("FORBIDDEN_ACCESS", "Accès interdit");
	define("ID_NOT_FOUND", "L'ID requis n'a pas pu être trouvé");
	define("ID_NOT_NUMERIC", "L'ID doit avoir une valeure numérique");
	define("LANGUAGE_ABBREVIATION_EMPTY", "L'abbréviation de la langue ne peut pas être vide");
	define("LANGUAGE_ABBREVIATION_LENGTH", "L'abbréviation doit être comprise entre 2 et 10 caractères");
	define("LANGUAGE_DELETE", "Langue supprimée");
	define("LANGUAGE_LABEL_EMPTY", "Le libellé de la langue ne peut pas être vide");
	define("LANGUAGE_LABEL_TOO_LONG_OR_TOO_SHORT", "Le libellé doit être compris entre 4 et 50 caractères");
	define("LANGUAGE_SUCCESS", "Langue ajoutée");
	define("MENU_SUCCESS", "Menu ajouté avec succès");
	define("NICKNAME_EMPTY", "Le champs nickname ne peut pas être vide");
	define("NICKNAME_TOO_LONG_OR_TOO_SHORT", "Le nom d'utilisateur est trop court ou trop long (entre 4 et 20 caractères)");
	define("NO_LANGUAGE", "Veuillez sélectionner une langue");
	define("NO_MENU", "Aucun menu trouvé pour cette langue");
	define("NOT_ALLOWED", "Vous n'avez pas les droits requis pour effectuer cette action");
	define("NOT_NUMERIC", "Seules les valeurs numérique sont acceptées");
    define("NO_TAGS", "Aucun tags spécifiés !");
	define("PASSWORD_EMPTY", "Le champs mot de passe ne peut pas être vide");
	define("PASSWORD_TOO_SHORT", "Le mot de passe n'est pas assez long (minimum 8 caractères)");
	define("PASSWORD_WRONG", "Mauvais mot de passe");
	define("PASSWORD_SUCCESS", "Mot de passe généré avec succès");
	define("PASSWORD_MAIL_ERROR", "Le mot de passe n'a pas pu être envoyé par mail, mais a été modifié");
	define("PERMISSIONS_NOT_ALLOWED", "Vous ne pouvez pas mettre à jour vos propres permissions");
	define("ROLE_ABBREVIATION_EMPTY", "L'abbréviation du rôle ne peut pas être vide");
	define("ROLE_ABBREVIATION_LENGTH", "L'abbréviation doit être comprise entre 3 et 10 caractères");
	define("ROLE_DEFAULT", "Le rôle n'a pas été défini");
	define("ROLE_DELETED", "Rôle supprimé");
	define("ROLE_EMPTY", "Veuillez choisir un rôle");
	define("ROLE_LABEL_TOO_LONG_OR_TOO_SHORT", "Le libellé du rôle doit être compris entre 4 et 50 caractères");
	define("ROLE_MODIFIED", "Rôle modifié avec succès");
	define("ROLE_S_ADMIN", "Vous ne pouvez pas vous octroyer le rôle de super administrateur");
	define("ROLE_SUCCESS", "Rôle ajouté");
	define("STATUS_UPDATED", "Le statut a correctement été modifié");
	define("TAG_COLOR", "Veuillez choisir une couleur");
	define("TAG_LABEL_EMPTY", "Le libellé de l'étiquette ne poeut pas être vide");
	define("TAG_LABEL_LENGTH", "Le libellé doit être compris entre 4 et 20 caractères");
	define("TAG_SUCCESS", "Étiquette ajoutée avec succès");
	define("TEXT_EMPTY", "Veuillez entrer un texte");
	define("UNKNOWN_USER", "Utilisateur inconnu");
	define("USER_SUCCESS", "Utilisateur ajouté avec succès");
	define("WRONG_LENGTH_TRANSLATE", "Un des champs de traduction est trop court ou trop long"); // Attention, texte aussi à modifié légèrement dans controller backend 'Contents' en cas de traduction

	function loadEnv(string $path): void {
        if (!file_exists($path))
            throw new RuntimeException(".env file not found at: $path");

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#'))
                continue;

            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
?>