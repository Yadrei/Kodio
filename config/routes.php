<?php
	/* 
		Fichier de configuration des routes du CMS
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 04/10/2023
	    @Dernière modification: 18/06/2025
  	*/

	// Définition des routes et de leurs correspondances aux contrôleurs et actions
	return [
		/* PARTIE PRIVÉE
		----------------
		*/
	    // PARTIE AJAX
	    BASE_URL."private/ajax/addMenu" => ['controller' => 'MenuController', 'action' => 'Add'],
	    BASE_URL."private/ajax/addUser" => ['controller' => 'UsersController', 'action' => 'AddUser'],
	    BASE_URL."private/ajax/addLanguage" => ['controller' => 'SettingsController', 'action' => 'AddLanguage'],
	    BASE_URL."private/ajax/deleteLanguage" => ['controller' => 'SettingsController', 'action' => 'DeleteLanguage'],
	    BASE_URL."private/ajax/addRole" => ['controller' => 'SettingsController', 'action' => 'AddRole'],
	    BASE_URL."private/ajax/deleteRole" => ['controller' => 'SettingsController', 'action' => 'DeleteRole'],
	    BASE_URL."private/ajax/addCategory" => ['controller' => 'SettingsController', 'action' => 'AddCategory'],
	    BASE_URL."private/ajax/deleteCategory" => ['controller' => 'SettingsController', 'action' => 'DeleteCategory'],
	    BASE_URL."private/ajax/addTag" => ['controller' => 'TagsController', 'action' => 'AddTag'],
	    BASE_URL."private/ajax/newPassword" => ['controller' => 'UsersController', 'action' => 'NewPassword'],
	    BASE_URL."private/ajax/newRole" => ['controller' => 'UsersController', 'action' => 'NewRole'],
	    BASE_URL."private/ajax/changeStatus" => ['controller' => 'ContentsController', 'action' => 'ChangeStatus'],
	    // END AJAX
        BASE_URL."preview/{language}/{slug}" => ['controller' => 'ContentsController', 'action' => 'Preview'],
	    BASE_URL."private" => ['controller' => 'AdminController', 'action' => 'LoginPage', 'auth' => false],
	    BASE_URL."private/maintenance" => ['controller' => 'SettingsController', 'action' => 'Maintenance'],
		BASE_URL."private/comments" => ['controller' => 'SettingsController', 'action' => 'Comments'],
		BASE_URL."private/comment/actions/delete/{id}" => ['controller' => 'CommentsController', 'action' => 'Delete'],
		BASE_URL."private/cookies" => ['controller' => 'SettingsController', 'action' => 'Cookies'],
	    BASE_URL."private/login" => ['controller' => 'AdminController', 'action' => 'Login', 'auth' => false],
	    BASE_URL."private/logout" => ['controller' => 'AdminController', 'action' => 'Logout'],
	    BASE_URL."private/home" => ['controller' => 'AdminController', 'action' => 'Home'],
	    BASE_URL."private/menu" => ['controller' => 'MenuController', 'action' => 'Index'],
	    BASE_URL."private/menu/action/delete/{id}" => ['controller' => 'MenuController', 'action' => 'Delete'],
	    BASE_URL."private/menu/action/update" => ['controller' => 'MenuController', 'action' => 'Update'],
	    BASE_URL."private/content" => ['controller' => 'ContentsController', 'action' => 'Index'],
	    BASE_URL."private/content/{page}" => ['controller' => 'ContentsController', 'action' => 'Index'], // Pagination
	    BASE_URL."private/content/action/save" => ['controller' => 'ContentsController', 'action' => 'Save'],
	    BASE_URL."private/content/action/delete/{id}" => ['controller' => 'ContentsController', 'action' => 'Delete'],
	    BASE_URL."private/content/action/recuperation/{id}" => ['controller' => 'ContentsController', 'action' => 'Recuperation'],
	 	BASE_URL."private/content/manage/add" => ['controller' => 'ContentsController', 'action' => 'ManageAdd'],
	    BASE_URL."private/content/manage/update/{id}" => ['controller' => 'ContentsController', 'action' => 'ManageUpdate'],
	    BASE_URL."private/tags" => ['controller' => 'TagsController', 'action' => 'Index'],
	    BASE_URL."private/tags/action/delete/{id}" => ['controller' => 'TagsController', 'action' => 'Delete'],
	    BASE_URL."private/tags/action/updateTextColor" => ['controller' => 'TagsController', 'action' => 'UpdateTextColor'],
	    BASE_URL."private/tags/action/update" => ['controller' => 'TagsController', 'action' => 'Update'],
	    BASE_URL."private/users" => ['controller' => 'UsersController', 'action' => 'Index'],
	    BASE_URL."private/users/{page}" => ['controller' => 'UsersController', 'action' => 'Index'], // Pagination
	    BASE_URL."private/users/action/permissions" => ['controller' => 'UsersController', 'action' => 'Permissions'],
	    BASE_URL."private/library" => ['controller' => 'LibraryController', 'action' => 'Index'],
	    BASE_URL."private/library/addImage" => ['controller' => 'LibraryController', 'action' => 'AddImage'],
        BASE_URL."private/library/deleteImage/{image}" => ['controller' => 'LibraryController', 'action' => 'DeleteImage'],
        BASE_URL."private/library/{folder}" => ['controller' => 'LibraryController', 'action' => 'Folder'],
	    BASE_URL."private/settings" => ['controller' => 'SettingsController', 'action' => 'Index'],
		BASE_URL."private/socials" => ['controller' => 'SettingsController', 'action' => 'Socials'],
	    /* PARTIE PUBLIQUE
	    ------------------
	    */
	    BASE_URL => ['controller' => 'HomeController', 'action' => 'Index', 'auth' => false],
	    BASE_URL."{language}" => ['controller' => 'HomeController', 'action' => 'Index', 'auth' => false],
	    BASE_URL."{language}/home" => ['controller' => 'HomeController', 'action' => 'Index', 'auth' => false],
	    BASE_URL."{language}/contact" => ['controller' => 'ContactController', 'action' => 'Index', 'auth' => false],
	    BASE_URL."{language}/{slug}" => ['controller' => 'ContentController', 'action' => 'Index', 'auth' => false],
		BASE_URL."reaction/action/add"=> ['controller' => 'ContentController', 'action' => 'AddReaction', 'auth' => false],
	    BASE_URL."contact/send" => ['controller' => 'ContactController', 'action' => 'Send', 'auth' => false]
	];
?>