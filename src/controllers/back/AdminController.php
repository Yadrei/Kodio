<?php
	/* 
		Contrôleur pour la page principale et login de l'admin
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 16/08/2023
	    @Dernière modification: 13/08/2025
  	*/

	class AdminController 
	{
		private $settingManager, $userManager, $permissionManager, $contentLangManager;

		public function __construct()
		{
			$this->settingManager = new SettingManager();
	        $this->userManager = new UserManager();
	        $this->permissionManager = new PermissionManager();
			$this->contentLangManager = new Content_LangManager();
		}

		public function Home() 
		{
			$maintenance = (bool)$this->settingManager->CheckMaintenance();
			$cookies = (bool)$this->settingManager->CheckCookies();
			$comments = (bool)$this->settingManager->CheckComments();
			$contentCount = $this->contentLangManager->CountContentUnpublished();
			
			require_once 'src/views/back/home.php';
		}

		public function LoginPage() 
		{
			if (!isset($_SESSION['isLog']))
				require_once 'src/views/back/login.php';
			else {
				header("Location: ".BASE_URL."private/home");
				exit;
			}
		}

		public function Login() 
		{
			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				throw new Exception(BAD_REQUEST_METHOD);

			CSRF::Check();

			if (!isset($_POST['nickname']) || !isset($_POST['password'])) {
				header("Location: ".BASE_URL);
				exit;
			}

			// Nettoyage des données
			$nickname = htmlspecialchars($_POST['nickname']);
			$nickname = stripslashes($nickname);
			$nickname = trim($nickname);

			$password = htmlspecialchars($_POST['password']);
			$password = stripslashes($password);
			$password = trim($password);

			if (empty($nickname))
				throw new Exception(NICKNAME_EMPTY);

			if (empty($password))
				throw new Exception(PASSWORD_EMPTY);

			if (strlen($nickname) < 4 || strlen($nickname) > 20)
				throw new Exception(NICKNAME_TOO_LONG_OR_TOO_SHORT);

			if (strlen($password) < 8)
				throw new Exception(PASSWORD_TOO_SHORT);


			// On crée un User, plus facile à gérer
			$userCon = new User (
				[
					'nickname' => $nickname,
					'passwordHash' => $password
				]);

			// On cherche après le même utilisateur
			$userDB = $this->userManager->Login($userCon);

			if (!$userDB)
				throw new Exception(UNKNOWN_USER);

			// The user exists, we can continue and check the passwords !
			if (password_verify($userCon->getPasswordHash(), $userDB->getPasswordHash())) {
				$permissionsLogged = $this->permissionManager->getPermissions($userDB->getId());

				if (!$permissionsLogged->getAllowAccess())
					throw new Exception(FORBIDDEN_ACCESS);

				$_SESSION['isLog'] = true;
				$_SESSION['id'] = $userDB->getId();
				$_SESSION['user'] = $userDB->getNickname();
				$_SESSION['role'] = $userDB->getRole();

				header('Location: ./home');
				exit;
			}
			else 
				throw new Exception(PASSWORD_WRONG);
		}

		public function Logout() 
		{
			session_destroy();
			unset($_SESSION);

			header("Location: ".BASE_URL);
			exit;
		}
	}
?>