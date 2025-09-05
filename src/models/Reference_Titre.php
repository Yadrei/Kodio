<?php
	/*
		Classe Reference_Titre qui représente la table REFERENCES_T en base de données
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 26/11/2021
		@Last update: 10/09/2023
	*/

	class Reference_Titre {
		// Attributes
		protected $errors = [], $ref, $libelle;

		// Constantes des erreurs
		const INVALID_REF = "The ref key is not valid";
		const INVALID_LABEL = "The label is not valid";

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

		public function IsValid() {
			return !(empty($this->ref) || empty($this->libelle));
		}

		// Setters
		public function setRef($ref) {
			if (!is_string($ref) || empty($ref))
				$this->errors[] = self::INVALID_REF;
			else
				$this->ref = $ref;
		}

		public function setLibelle($libelle) {
			if (!is_string($libelle) || empty($libelle))
				$this->errors[] = self::INVALID_LABEL;
			else
				$this->libelle = $libelle;
		}

		// Getters
		public function getErrors() {
			return $this->errors;
		}

		public function getRef() {
			return $this->ref;
		}

		public function getLibelle() {
			return $this->libelle;
		}
	}
?>