<?php
	/* 
		Contrôleur pour la page des utilisateurs de l'admin
	    @Author Yves P.
	    @Version 1.1
	    @Date création: 16/08/2023
	    @Dernière modification: 13/08/2025
  	*/

	class UsersController 
	{
		private $userManager, $referenceDetailManager, $permissionManager;

		public function __construct()
		{
	        $this->userManager = new UserManager();
	        $this->referenceDetailManager = new Reference_DetailManager();
	        $this->permissionManager = new PermissionManager();
		}

		public function Index($page = 1) 
		{
			$currentItems = ($page - 1 ) * $GLOBALS['itemsPerPages'];

			$listUsers = $this->userManager->getAllUsers($currentItems, $GLOBALS['itemsPerPages']);
			$count = $this->userManager->count();

			$references = $this->referenceDetailManager->getDetails("R_ROLE");

			foreach ($listUsers as $user) {
				$userId = $user->getId();
				$permissions[$userId] = $this->permissionManager->getPermissions($userId);
			}

			$pagination = new Pagination(BASE_URL."private/users/%s", $page, $count, $GLOBALS['options']);

			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']); // Pour récupérer les permissions de l'utilisateur connecté

			require_once 'src/views/back/users.php';
		}

		public function AddUser()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowAdd())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['nickname']) || !isset($_POST['password']) || !isset($_POST['email']) || !isset($_POST['role']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			$nickname = Sanitize($_POST['nickname']);
			$password = Sanitize($_POST['password']);
			$email = Sanitize($_POST['email']);
			$role = Sanitize($_POST['role']);

			if (empty($nickname))
				$response = array('status' => false, 'message' => NICKNAME_EMPTY);

			if (strlen($nickname) < 4 || strlen($nickname) > 20) 
				$response = array('status' => false, 'message' => NICKNAME_TOO_LONG_OR_TOO_SHORT);

			if (empty($password))
				$response = array('status' => false, 'message' => PASSWORD_EMPTY);

			if (strlen($password) < 8)
				$response = array('status' => false, 'message' => PASSWORD_TOO_SHORT);

			if (empty($email))
				$response = array('status' => false, 'message' => EMAIL_EMPTY);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				$response = array('status' => false, 'message' => EMAIL_FORMAT);

			if (empty($role))
				$response = array('status' => false, 'message' => ROLE_EMPTY);

			if (empty($response)) {
				$user = new User (
				[
					'nickname' => $nickname,
					'email' => $email,
					'passwordAdmin' => password_hash($password, PASSWORD_DEFAULT),
					'role' => $role
				]);

				/* Generer un random byte
					$token = openssl_random_pseudo_bytes(32);

					Ensuite, dans l'instance de la classe
					'token' => bin2hex($token)
				*/

				try {
					$userId = $this->userManager->Save($user);

					$this->permissionManager->Add($userId);

				    $response = array('status' => true, 'message' => USER_SUCCESS);

				} 
				catch (PDOException $e) {
				    $response = array('success' => false, 'message' => $e->getMessage());
				}				
			}

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function NewPassword() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['id']))
				$response = array('status' => false, 'message' => ID_NOT_FOUND);

			$id = Sanitize($_POST['id']);

			if (!is_numeric($id))
				$response = array('status' => false, 'message' => ID_NOT_NUMERIC);

			if (empty($response)) {
				$password = GenerateRandomPassword();

		    	$user = new User (
					[
						'id' => $id,
						'passwordAdmin' => password_hash($password, PASSWORD_DEFAULT)
					]);

		    	try {
					$this->userManager->Save($user);

					$email = $this->userManager->GetMail($user->getId());

					$content = '
					<p>Un nouveau mot de passe a été généré pour votre utilisateur.</p>
					<p>Mot de passe : '. $password.'</p>';

					$mail = new Mail($email, '', '', 'Nouveau mot de passe', $content);

					if ($mail->Send()) 
						$response = array('status' => true, 'message' => PASSWORD_SUCCESS);
					else 
						$response = array('status' => false, 'message' => PASSWORD_MAIL_ERROR);
				} 
				catch (PDOException $e) {
				    $response = array('status' => false, 'message' => $e->getMessage());
				}
			} 

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function NewRole()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['id']) || !isset($_POST['role']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			$id = Sanitize($_POST['id']);
			$role = Sanitize($_POST['role']);

			if (empty($role))
				$response = array('status' => false, 'message' => ROLE_EMPTY);

			if ($role === "S_ADMIN" && ($_SESSION['role'] != "WEB" && $_SESSION['role'] != "S_ADMIN"))
				$response = array('status' => false, 'message' => ROLE_S_ADMIN);

			if (empty($response)) {
				$user = new User (
				[
					'id' => $id,
					'role' => $role
				]);

				try {
					$this->userManager->Save($user);

				    $response = array('status' => true, 'message' => ROLE_MODIFIED);

				} 
				catch (PDOException $e) {
				    $response = array('success' => false, 'message' => $e->getMessage());
				}
			}
			
			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function Permissions()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				throw new Exception(BAD_REQUEST_METHOD);

			if (!isset($_POST['user']))
				throw new Exception(CRITICAL_ERROR);

			$user = Sanitize($_POST['user']);

			if ($user == $_SESSION['id'])
				throw new Exception(PERMISSIONS_NOT_ALLOWED);

			$permissions = [];

			foreach ($_POST['allow'] as $field => $value) {
				if ($value === 'on') 
		            $permissions[$field] = true;
			}

			$permissionsUser = new Permission(
			[
				'user' => $user,
				'allowAccess' => (isset($permissions['access'])) ? true : false,
				'allowAdd' => (isset($permissions['add'])) ? true : false,
				'allowUpdate' => (isset($permissions['update'])) ? true : false,
				'allowDelete' => (isset($permissions['delete'])) ? true : false
			]);

			
			$this->permissionManager->Save($permissionsUser);
			
			header("Location: ".BASE_URL."private/users");
			exit;
		}
	}
?>