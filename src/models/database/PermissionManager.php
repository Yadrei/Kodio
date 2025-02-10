<?php
	/*
		Class for the DB requests for Permissions
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 29/12/2021
		@Last update: 27/09/2023
	*/

	class PermissionManager 
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		// Private methods
		private function Update(Permission $permission) {
			$query = $this->db->prepare('UPDATE PERMISSIONS SET ALLOW_ADMIN = :allowAccess, ALLOW_ADD = :allowAdd, ALLOW_UPDATE = :allowUpdate, ALLOW_DELETE = :allowDelete WHERE FK_USER = :user');

			$query->bindValue(':user', $permission->getUser(), PDO::PARAM_INT);
			$query->bindValue(':allowAccess', $permission->getAllowAccess(), PDO::PARAM_BOOL);
			$query->bindValue(':allowAdd', $permission->getAllowAdd(), PDO::PARAM_BOOL);
			$query->bindValue(':allowUpdate', $permission->getAllowUpdate(), PDO::PARAM_BOOL);
			$query->bindValue(':allowDelete', $permission->getAllowDelete(), PDO::PARAM_BOOL);

			$query -> execute();
		}

		// Public methods
		public function Save(Permission $permission) {
			if ($permission -> IsValid())
				$this -> Update($permission);
			else
				throw new Exception("Erreure critique !");
		}

		public function getPermissions($id) {
			$query = $this->db->prepare('SELECT FK_USER user, ALLOW_ADMIN allowAccess, ALLOW_ADD allowAdd, ALLOW_UPDATE allowUpdate, ALLOW_DELETE allowDelete FROM PERMISSIONS WHERE FK_USER = :user');

		  	$query->bindParam(':user', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Permission');
			$query->execute();

			$permissions = $query->fetch();

			$query->closeCursor();

			return $permissions;
		}
	}
?>