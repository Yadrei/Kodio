<?php
	/* 
		Classe utilisateur qui représente la table SETTINGS en base de données
		@Author Yves P.
		@Version 1.0
		@Date Création: 18/09/2023
		@Dernière modification: 18/08/2023
	*/

	class Setting 
	{
	    private $errors = [], $id, $setting, $value;

	    // Constantes pour les erreurs
		const INVALID_SETTING = "ID incorrect";
		const INVALID_VALUE = "Valeur incorrecte";

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

		public function isValid() {
			return !(empty($this->id) || empty($this->setting) || empty($this->value));
		}

		// Setters
		public function setId($id) {
			$this->id = $id;
		}

		public function setSetting($setting) {
			if (!is_string($setting) || empty($setting))
				$this->errors[] = self::INVALID_SETTING;
			else
				$this->setting = $setting;
		}

		public function setValue($value) {
			if (!is_string($value) || empty($value))
				$this->errors[] = self::INVALID_VALUE;
			else
				$this->value = $value;
		}

		// Getters
		public function getErrors() {
			return $this->errors;
		}

		public function getId() {
			return $this->id;
		}

		public function getSetting() {
			return $this->setting;
		}

		public function getValue() {
			return $this->value;
		}
	}	
?>