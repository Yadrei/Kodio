<?php
	/* 
		Contrôleur pour gérer le menu
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 25/11/2023
	    @Dernière modification: 02/09/2025
  	*/

	class LibraryController 
	{
		private $permissionManager;

		public function __construct()
		{
	        $this->permissionManager = new PermissionManager();
		}

		public function Index() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']); // Pour récupérer les permissions de l'utilisateur connecté

            $allowedExtensions = array('webp');
			$initialFolder = __DIR__.'/../../../public/images/medias/';
            $urlImage = BASE_URL.'public/images/medias/';

			require_once 'src/views/back/library.php';
		}

        public function Folder($folder)
        {
            $allowedExtensions = array('webp');
            $initialFolder = __DIR__.'/../../../public/images/medias/'.$folder;
            $urlImage = BASE_URL.'public/images/medias/'.$folder.'/';

            require_once 'src/views/back/library.php';
        }

		public function AddImage()
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowAdd())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER["REQUEST_METHOD"] !== "POST")
				throw new Exception(BAD_REQUEST_METHOD);

			CSRF::Check();

			if (!isset($_FILES["images"]) || !isset($_POST['folder'])) 
				throw new Exception(FIELD_NOT_FOUND);

			$uploadFolder = "";

			if (!empty($_POST['folder']))
				$uploadFolder = Sanitize($_POST['folder']);

			ProcessImages('medias/'.$uploadFolder);

			header("Location: ".BASE_URL."private/library");
			exit;
		}

        public function DeleteImage($image)
        {
            $permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

            if (!$permissionsLogged->getAllowDelete())
                throw new Exception(NOT_ALLOWED);

            $path = "public/images/medias";

            if (DeleteImage($path, $image)) {
                header("Location: ".BASE_URL."private/library");
                exit;
            }
            else
                throw new Exception("Suppression image - ERREUR");
        }
	}
?>