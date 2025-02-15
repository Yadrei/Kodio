<?php
	/* 
		Contrôleur pour la page de paramètres de l'admin
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 18/09/2023
	    @Dernière modification: 15/02/2025
  	*/

	class SettingsController 
	{
		private $referenceDetailManager, $settingManager, $permissionManager;

		public function __construct()
		{
	        $this->settingManager = new SettingManager();
	        $this->referenceDetailManager = new Reference_DetailManager();
	        $this->permissionManager = new PermissionManager();
		}

		public function Index() 
		{
			$categories = $this->referenceDetailManager->getDetails("R_CAT");
			$langues = $this->referenceDetailManager->getDetails("R_LANG");
			$roles = $this->referenceDetailManager->getDetails("R_ROLE");

			$maintenance = (bool)$this->settingManager->CheckMaintenance();
			$cookies = (bool)$this->settingManager->CheckCookies();
			$comments = (bool)$this->settingManager->CheckComments();

			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']); // Pour récupérer les permissions de l'utilisateur connecté

			require_once 'src/views/back/settings.php';
		}

		public function AddLanguage() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowAdd())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['language']) || !isset($_POST['key']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			$language = Sanitize($_POST['language']);
			$key = Sanitize($_POST['key']);

			if (empty($language))
				$response = array('status' => false, 'message' => LANGUAGE_LABEL_EMPTY);

			if (strlen($language) < 4 || strlen($language) > 50) 
				$response = array('status' => false, 'message' => LANGUAGE_LABEL_TOO_LONG_OR_TOO_SHORT);

			if (empty($key))
				$response = array('status' => false, 'message' => LANGUAGE_ABBREVIATION_EMPTY);

			if (strlen($key) < 2 || strlen($key) > 10)
				$response = array('status' => false, 'message' => LANGUAGE_ABBREVIATION_LENGTH);

			if (empty($response)) {
				$langue = new Reference_Detail (
				[
					'clef' => strtoupper($key),
					'ref' => 'R_LANG',
					'label' => $language,
				]);

				try {
					$this->referenceDetailManager->Save($langue);

				    $response = array('status' => true, 'message' => LANGUAGE_SUCCESS);

				} 
				catch (PDOException $e) {
				    $response = array('success' => false, 'message' => $e->getMessage());
				}				
			}

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function DeleteLanguage() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowDelete())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['key']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			$key = Sanitize($_POST['key']);

			if (empty($response)) {
				$langue = new Reference_Detail (
				[
					'clef' => $key,
					'ref' => 'R_LANG'
				]);

				try {
					$this->referenceDetailManager->Delete($langue);

				    $response = array('status' => true, 'message' => LANGUAGE_DELETE);

				} 
				catch (PDOException $e) {
				    $response = array('success' => false, 'message' => $e->getMessage());
				}				
			}

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function AddRole() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowAdd())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['role']) || !isset($_POST['key']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			$role = Sanitize($_POST['role']);
			$key = Sanitize($_POST['key']);

			if (empty($role))
				$response = array('status' => false, 'message' => ROLE_EMPTY);

			if (strlen($role) < 4 || strlen($role) > 50) 
				$response = array('status' => false, 'message' => ROLE_LABEL_TOO_LONG_OR_TOO_SHORT);

			if (empty($key))
				$response = array('status' => false, 'message' => ROLE_ABBREVIATION_EMPTY);

			if (strlen($key) < 3 || strlen($key) > 10)
				$response = array('status' => false, 'message' => ROLE_ABBREVIATION_LENGTH);

			if (empty($response)) {
				$role = new Reference_Detail (
				[
					'clef' => $key,
					'ref' => 'R_ROLE',
					'label' => $role,
				]);

				try {
					$this->referenceDetailManager->Save($role);

				    $response = array('status' => true, 'message' => ROLE_SUCCESS);

				} 
				catch (PDOException $e) {
				    $response = array('success' => false, 'message' => $e->getMessage());
				}				
			}

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function DeleteRole() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowDelete())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['key']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			$key = Sanitize($_POST['key']);

			if (empty($response)) {
				$role = new Reference_Detail (
				[
					'clef' => $key,
					'ref' => 'R_ROLE'
				]);

				try {
					$this->referenceDetailManager->Delete($role);

				    $response = array('status' => true, 'message' => ROLE_DELETED);

				} 
				catch (PDOException $e) {
				    $response = array('success' => false, 'message' => $e->getMessage());
				}				
			}

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function AddCategory()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowAdd())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['cat']) || !isset($_POST['key']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			$cat = Sanitize($_POST['cat']);
			$key = Sanitize($_POST['key']);

			if (empty($cat))
				$response = array('status' => false, 'message' => CATEGORY_EMPTY);

			if (strlen($cat) < 4 || strlen($cat) > 50) 
				$response = array('status' => false, 'message' => CATEGORY_LABEL_TOO_LONG_OR_TOO_SHORT);

			if (empty($key))
				$response = array('status' => false, 'message' => CATEGORY_ABBREVIATION_EMPTY);

			if (strlen($key) < 3 || strlen($key) > 10)
				$response = array('status' => false, 'message' => CATEGORY_ABBREVIATION_LENGTH);

			if (empty($response)) {
				$cat = new Reference_Detail (
				[
					'clef' => $key,
					'ref' => 'R_CAT',
					'label' => $cat,
				]);

				try {
					$this->referenceDetailManager->Save($cat);

				    $response = array('status' => true, 'message' => CATEGORY_SUCCESS);

				} 
				catch (PDOException $e) {
				    $response = array('success' => false, 'message' => $e->getMessage());
				}				
			}

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function DeleteCategory()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowDelete())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['key']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			$key = Sanitize($_POST['key']);

			if (empty($response)) {
				$cat = new Reference_Detail (
				[
					'clef' => $key,
					'ref' => 'R_CAT'
				]);

				try {
					$this->referenceDetailManager->Delete($cat);

				    $response = array('status' => true, 'message' => CATEGORY_DELETED);

				} 
				catch (PDOException $e) {
				    $response = array('success' => false, 'message' => $e->getMessage());
				}				
			}

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function Maintenance() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);
			
			if (!isset($_POST['submit'])) {
				header('Location: /Kodio');
				exit;
			}

			$this->settingManager->Maintenance(isset($_POST['maintenance']));

			header("Location: ".BASE_URL."private/settings");
			exit;
		}

		public function Cookies()
		{
			$permissionsLogged = $this->permissionManager->getPermissions(($_SESSION['id']));

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);
			
			if (!isset($_POST['submit'])) {
				header('Location: /Kodio');
				exit;
			}

			$this->settingManager->Cookies(isset($_POST['cookies']));

			header("Location: ".BASE_URL."private/settings");
			exit;
		}

		public function Comments()
		{
			$permissionsLogged = $this->permissionManager->getPermissions(($_SESSION['id']));

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);
			
			if (!isset($_POST['submit'])) {
				header('Location: /Kodio');
				exit;
			}

			$this->settingManager->Comments(isset($_POST['comments']));

			header("Location: ".BASE_URL."private/settings");
			exit;
		}
	}
?>