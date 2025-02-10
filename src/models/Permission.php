<?php
	/* 
		Class Permission qui représente la table PERMISSIONS en base de données
		@Author Yves P.
		@Version 1.0
		@Date Création: 26/11/2021
		@Dernière modification: 27/09/2023
	*/

	class Permission 
	{
	    private $errors = [], $user, $allowAccess, $allowAdd, $allowUpdate, $allowDelete;

	    // Constantes pour les erreurs
		const INVALID_USER = "The user is invalid";

	    public function __construct($values = []) {
			if (!empty($values))
				$this->SettingAttributes($values);
		}

		// Méthodes
		public function SettingAttributes($data) {
			foreach ($data as $attribute => $value) 
			{
				$method = 'set'.ucfirst($attribute);

				if (is_callable([$this, $method]))
					$this->$method($value);
			}
		}

		public function IsValid() {
				return !(empty($this->user));
		}

		// Setters
		public function setUser($user) {
			if (empty($user))
				$this->errors[] = self::INVALID_USER;
			else
				$this->user = $user;
		}

		public function setAllowAccess($access) {
			$this->allowAccess = $access;
		}

		public function setAllowAdd($add) {
			$this->allowAdd = $add;
		}

		public function setAllowUpdate($update) {
			$this->allowUpdate = $update;
		}

		public function setAllowDelete($delete) {
			$this->allowDelete = $delete;
		}

		// Getters
		public function getErrors() {
			return $this->errors;
		}

		public function getUser() {
			return $this->user;
		}

		public function getAllowAccess() {
			return $this->allowAccess;
		}

		public function getAllowAdd() {
			return $this->allowAdd;
		}

		public function getAllowUpdate() {
			return $this->allowUpdate;
		}

		public function getAllowDelete() {
			return $this->allowDelete;
		}
	}	
?>