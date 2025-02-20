<?php
	/* 
		Contrôleur pour les commentaires depuis l'admin
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 20/02/2025
	    @Dernière modification: 20/02/2025
  	*/

	class CommentsController 
	{
		private $commentManager, $permissionManager;

		public function __construct()
		{
	        $this->commentManager = new commentManager();
	        $this->permissionManager = new PermissionManager();
		}

		public function Delete($id)
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowDelete())
				throw new Exception(NOT_ALLOWED);

			$this->commentManager->Delete($id);

			header("Location: ".BASE_URL."private/content");
			exit;
		}
	}
?>