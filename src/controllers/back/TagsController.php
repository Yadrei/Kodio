<?php
	/* 
		Contrôleur pour la page des étiquettes de l'admin
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 17/11/2023
	    @Dernière modification: 23/11/2023
  	*/

	class TagsController 
	{
		private $tagManager, $permissionManager;

		public function __construct()
		{
	        $this->tagManager = new TagManager();
	        $this->permissionManager = new PermissionManager();
		}

		public function Index($page = 1) 
		{
			/*
			$currentItems = ($page - 1 ) * $GLOBALS['itemsPerPages'];

			$listUsers = $this->userManager->getAllUsers($currentItems, $GLOBALS['itemsPerPages']);
			$count = $this->userManager->count();

			$references = $this->referenceDetailManager->getDetails("R_ROLE");

			foreach ($listUsers as $user) {
				$userId = $user->getId();
				$permissions[$userId] = $this->permissionManager->getPermissions($userId);
			}

			$pagination = new Pagination(BASE_URL."private/tags/%s", $page, $count, $GLOBALS['options']);

			
			*/

			$tags = $this->tagManager->GetAllTags();

			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']); // Pour récupérer les permissions de l'utilisateur connecté

			require_once 'src/views/back/tags.php';
		}

		public function AddTag() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowAdd())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['label']) || !isset($_POST['color']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			$label = Sanitize($_POST['label']);
			$color = Sanitize($_POST['color']);

			if (empty($label))
				$response = array('status' => false, 'message' => TAG_LABEL_EMPTY);

			if (strlen($label) < 2 || strlen($label) > 20)
				$response = array('status' => false, 'message' => TAG_LABEL_LENGTH);

			if (empty($color))
				$response = array('status' => false, 'message' => TAG_COLOR);

			if (strlen($color) == 6)
				$color = '#'.$color;

			if (empty($response)) {
				$tag = new Tag (
				[
					'label' => $label,
					'color' => $color
				]);

				try {
					$this->tagManager->Save($tag);

					$response = array('status' => true, 'message' => TAG_SUCCESS);
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

			$this->tagManager->Delete($id);

			header("Location: ".BASE_URL."private/tags");
			exit;
		}

		public function Update()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				throw new Exception(BAD_REQUEST_METHOD);

			if (!isset($_POST['id']))
				throw new Exception(ID_NOT_FOUND);

			if (!isset($_POST['label']) || !isset($_POST['color']))
				throw new Exception(FIELD_NOT_FOUND);

			$id = Sanitize($_POST['id']);
			$label = Sanitize($_POST['label']);
			$color = Sanitize($_POST['color']);

			if (empty($label))
				throw new Exception(TAG_LABEL_EMPTY);

			if (strlen($label) < 2 || strlen($label) > 20)
				throw new Exception(TAG_LABEL_LENGTH);

			if (empty($color))
				throw new Exception(TAG_COLOR);

			if (strlen($color) == 6)
				$color = '#'.$color;

			$tag = new Tag (
			[
				'id' => $id,
				'label' => $label,
				'color' => $color
			]);

			try {
				$this->tagManager->Save($tag);

				header("Location: ".BASE_URL."private/tags");
				exit;
			}
			catch (PDOException $e) {
			    throw new Exception($e->getMessage());
			}	
		}

		public function UpdateColor()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				throw new Exception(AD_REQUEST_METHOD);

			if (!isset($_POST['id']))
				throw new Exception(ID_NOT_FOUND);

			if (!isset($_POST['color']))
				throw new Exception(FIELD_NOT_FOUND);

			$id = Sanitize($_POST['id']);
			$color = Sanitize($_POST['color']);

			if (empty($color))
				throw new Exception(TAG_COLOR);

			if (strlen($color) == 6)
				$color = '#'.$color;

			$tag = new Tag (
				[
					'id' => $id,
					'color' => $color
				]);

			try {
				$this->tagManager->SaveColor($tag);

				header("Location: ".BASE_URL."private/tags");
				exit;
			}
			catch (PDOException $e) {
			    throw new Exception($e->getMessage());
			}	
		}
	}
?>