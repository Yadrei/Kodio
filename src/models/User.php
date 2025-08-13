<?php
	/* 
		Classe utilisateur qui représente la table USERS en base de données
		@Author Yves P.
		@Version 1.1
		@Date Création: 26/11/2021
		@Dernière modification: 13/08/2025
	*/

	class User 
	{
	    private $errors = [], $id, $nickname, $email, $passwordHash, $role, $dateCre;

	    // Constantes pour les erreurs
		const INVALID_NICKNAME = "The nickname can't be empty or is not correct";
		const INVALID_EMAIL = "The email is invalid";
		const INVALID_PASSWORD_HASH = "The password admin can't be empty";
		const INVALID_ROLE = "The role is not valid";

	    public function __construct($values = []) {
			if (!empty($values))
				$this -> SettingAttributes($values);
		}

		// Méthodes
		public function SettingAttributes($data) {
			foreach ($data as $attribute => $value) 
			{
				$method = 'set'.ucfirst($attribute);

				if (is_callable([$this, $method]))
					$this -> $method($value);
			}
		}

		public function isNew() {
			return empty($this->id);
		}

		public function isValid() {
			if (!empty($this->id))
				return true;
			else
				return !(empty($this->nickname) || empty($this->email) || empty($this->passwordHash) || empty($this->role));
		}

		// Setters
		public function setId($id) {
			$this->id = $id;
		}

		public function setNickname($nickname) {
			if (!is_string($nickname) || empty($nickname))
				$this->errors[] = self::INVALID_NICKNAME;
			else
				$this->nickname = $nickname;
		}

		public function setEmail($email) {
			if (!is_string($email) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
				$this->errors[] = self::INVALID_EMAIL;
			else
				$this->email = $email;
		}

		public function setPasswordHash($password) {
			if (!is_string($password) || empty($password)) 
				$this->errors[] = self::INVALID_PASSWORD_HASH;
			else
				$this->passwordHash = $password;
		}

		public function setRole($role) {
			if (empty($role))
				$this->errors[] = self::INVALID_ROLE;
			else
				$this->role = $role;
		}

		public function setDateCre(DateTime $date) {
			$this->dateCre = $date;
		}

		// Getters
		public function getErrors() {
			return $this->errors;
		}

		public function getId() {
			return $this->id;
		}

		public function getNickname() {
			return $this->nickname;
		}

		public function getEmail() {
			return $this->email;
		}

		public function getPasswordHash() {
			return $this->passwordHash;
		}

		public function getRole() {
			return $this->role;
		}

		public function getDateCre() {
			return (new DateTime($this->dateCre))->format("d/m/Y H:i:s");
		}
	}	
?>