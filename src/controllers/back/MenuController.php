<?php
	/* 
		Contrôleur pour gérer le menu
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 12/10/2023
	    @Dernière modification: 02/09/2025
  	*/

	class MenuController 
	{
		private $contentManager, $referenceDetailManager, $menuManager, $permissionManager;

		public function __construct()
		{
			$this->contentManager = new Content_LangManager();
			$this->referenceDetailManager = new Reference_DetailManager();
	        $this->menuManager = new MenuManager();
	        $this->permissionManager = new PermissionManager();
		}

		public function Index() 
		{
			$langues = $this->referenceDetailManager->getDetails("R_LANG");
			$mainMenu = $this->menuManager->GetAllMainMenu();
			$contentMenu = $this->contentManager->GetContentMenu();

			foreach($langues as $langue) {
				$langueKey = $langue->getClef();
				$menus[$langueKey] = $this->menuManager->GetMenuByLanguage($langueKey);
			}

			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']); // Pour récupérer les permissions de l'utilisateur connecté

			require_once 'src/views/back/menu.php';
		}

		public function Add()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowAdd())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			CSRF::Check();

			if (!isset($_POST['parent']) || !isset($_POST['label']) || !isset($_POST['language']) || !isset($_POST['content']) || !isset($_POST['ordre']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			if (!is_numeric($_POST['parent']))
				$response = array('status' => false, 'message' => NOT_NUMERIC);

			if (!is_numeric($_POST['content']))
				$response = array('status' => false, 'message' => NOT_NUMERIC);

			if (!is_numeric($_POST['ordre']))
				$response = array('status' => false, 'message' => NOT_NUMERIC);

			if (empty($_POST['language']))
				$response = array('status' => false, 'message' => NO_LANGUAGE);

			$parent = (int)$_POST['parent'];
			$label = Sanitize($_POST['label']);
			$language = Sanitize($_POST['language']);
			$content = (int)$_POST['content'];
			$ordre = (int)$_POST['ordre'];

			if (empty($response)) {
				$menu = new Menu (
				[
					'parent' => ($parent != 0) ? $parent : null,
					'language' => $language,
					'content' => ($content != 0) ? $content : null,
					'label' => $label,
					'ordre' => $ordre
				]);

				try {
					$this->menuManager->Save($menu);

				    $response = array('status' => true, 'message' => MENU_SUCCESS);
				}
				catch (PDOException $e) {
					$response = array('success' => false, 'message' => $e->getMessage());
				}
			}

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function Delete($id)
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowDelete())
				throw new Exception(NOT_ALLOWED);

			$this->menuManager->Delete($id);
			
			header("Location: ".BASE_URL."private/menu");
			exit;
		}

		public function Update()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				throw new Exception(BAD_REQUEST_METHOD);

			CSRF::Check();

			if (!isset($_POST['id']) || !isset($_POST['parent']) || !isset($_POST['label']) || !isset($_POST['language']) || !isset($_POST['content']) || !isset($_POST['ordre']))
				throw new Exception(FIELD_NOT_FOUND);

			if (!is_numeric($_POST['id']))
				throw new Exception(NOT_NUMERIC);

			if (!is_numeric($_POST['parent']))
				throw new Exception(NOT_NUMERIC);

			if (!is_numeric($_POST['content']))
				throw new Exception(NOT_NUMERIC);

			if (!is_numeric($_POST['ordre']))
				throw new Exception(NOT_NUMERIC);

			if ($_POST['language'] == "DEFAULT")
				throw new Exception(NO_LANGUAGE);

			$id = (int)$_POST['id'];
			$parent = (int)$_POST['parent'];
			$label = Sanitize($_POST['label']);
			$language = Sanitize($_POST['language']);
			$content = (int)$_POST['content'];
			$ordre = (int)$_POST['ordre'];

			$menu = new Menu (
			[
				'id' => $id,
				'parent' => ($parent != 0) ? $parent : null,
				'language' => $language,
				'content' => ($content != 0) ? $content : null,
				'label' => $label,
				'ordre' => $ordre
			]);

			$this->menuManager->Save($menu);

			header("Location: ".BASE_URL."private/menu");
			exit;
		}
	}
?>