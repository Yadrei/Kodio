<?php
	/*
		Class Manager pour les relations avec la DB sur la table USERS
		@Author Yves Ponchelet
		@Version 1.1
		@Creation: 26/11/2021
		@Last update: 13/08/2025
	*/

	class UserManager 
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		// Private methods
		private function Add(User $user) {
			$query = $this->db->prepare('INSERT INTO USERS (NICKNAME, EMAIL, PASSWORD_ADMIN, R_ROLE) VALUES (:nickname, :email, :passwordAdmin, :role)');

			$query->bindValue(':nickname', $user -> getNickname(), PDO::PARAM_STR);
			$query->bindValue(':email', $user -> getEmail(), PDO::PARAM_STR);
			$query->bindValue(':passwordAdmin', $user -> getPasswordAdmin(), PDO::PARAM_STR);
			$query->bindValue(':role', $user -> getRole(), PDO::PARAM_STR);

			$query->execute();

			return (int) $this->db->lastInsertId();
		}

		public function Login(User $user) {
			$query = $this->db->prepare('SELECT ID id, NICKNAME nickname, PASSWORD_ADMIN passwordAdmin, R_ROLE role FROM USERS WHERE UPPER(NICKNAME) = UPPER(:nickname)');

			$query->bindValue(':nickname', $user->getNickname(), PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
			$query->execute();

			$user = $query->fetch();

			$query->closeCursor();

			if ($user)
			    return $user;
			else
			    return false;
		}

		private function UpdatePasswordAdmin(User $user) {
			$query = $this->db->prepare('UPDATE USERS SET PASSWORD_ADMIN = :passwordPublic WHERE ID = :id');

			$query->bindValue(':id', $user->getId(), PDO::PARAM_INT);
			$query->bindValue(':passwordPublic', $user->getPasswordAdmin(), PDO::PARAM_STR);

			$query->execute();
		}

		private function UpdateRole(User $user) {
			$query = $this->db->prepare('UPDATE USERS SET R_ROLE = :role WHERE ID = :id');

			$query->bindValue(':id', $user->getId(), PDO::PARAM_INT);
			$query->bindValue(':role', $user->getRole(), PDO::PARAM_STR);

			$query->execute();
		}

		// Méthodes publiques
		public function Count() {
			$query = $this->db->prepare('SELECT COUNT(*) FROM USERS');

			$query->execute();

			return $query->fetchColumn();
		}

		public function Save(User $user) {
			if ($user->isValid()) {
				if ($user->isNew())
					return $this->Add($user);
				else if (!is_null($user->getId()) && !is_null($user->getPasswordAdmin()))
					$this->UpdatePasswordAdmin($user);
				else if (!is_null($user->getId()) && !is_null($user->getRole()))
					$this->UpdateRole($user);
				else
					throw new Exception("Update impossible");
			}
			else
				throw new Exception($user -> getErrors());
		}

		public function GetAllUsers($begin = -1, $limit = -1) {
			$query = 'SELECT ID id, NICKNAME nickname, EMAIL email, R_ROLE role FROM USERS ORDER BY NICKNAME';

			// Checking parameters
		    if ($begin != -1 || $limit != -1)
		      $query .= ' LIMIT '.(int) $limit.' OFFSET '.(int) $begin;

		  	$query = $this -> db -> prepare($query);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
			$query->execute();

			$users = $query->fetchAll();

			$query->closeCursor();

			foreach ($users as $user) {
				$manager = new Reference_DetailManager();
				$role = $manager->GetRole($user->getRole());

				$user->setRole($role);
			}

			return $users;
		}

		public function GetUserById($id) {
			$query = $this->db->prepare('SELECT NICKNAME nickname FROM USERS WHERE ID = :id');

			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'User');
			$query->execute();

			$user = $query->fetch();

			$query->closeCursor();

			return $user;
		}

		public function GetMail($id) {
			$query = $this->db->prepare('SELECT EMAIL FROM USERS WHERE ID = :id');

			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			return $query->fetchColumn();
		}
	}
?>