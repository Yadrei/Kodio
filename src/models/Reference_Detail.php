<?php
	/*
		Classe représentant la table References_D en base de données
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 26/11/2021
		@Last update: 22/09/2023
	*/

	class Reference_Detail {
		// Attributes
		protected $errors = [], $clef, $ref, $label;

		// Constants for the possible errors
		const INVALID_KEY = "The key is not valid";
		const INVALID_REF = "The ref key is not valid";
		const INVALID_LABEL = "The label is not valid";

		public function __construct($values = []) {
			if (!empty($values))
				$this -> SettingAttributes($values);
		}

		// Méthods
		public function SettingAttributes($data) {
			foreach ($data as $attribute => $value) 
			{
				$method = 'set'.ucfirst($attribute);

				if (is_callable([$this, $method]))
					$this -> $method($value);
			}
		}

		public function IsValid() {
			return !(empty($this->clef) || empty($this->ref) || empty($this->label));
		}

		// Setters
		public function setClef($clef) {
			if (!is_string($clef) || empty($clef))
				$this->errors[] = self::INVALID_KEY;
			else
				$this->clef = $clef;
		}

		public function setRef($ref) {
			if (!is_string($ref) || empty($ref))
				$this->errors[] = self::INVALID_REF;
			else
				$this->ref = $ref;
		}

		public function setLabel($label) {
			if (!is_string($label) || empty($label))
				$this->errors[] = self::INVALID_LABEL;
			else
				$this->label = $label;
		}

		// Getters
		public function getErrors() {
			return $this->errors;
		}

		public function getClef() {
			return $this->clef;
		}

		public function getRef() {
			return $this->ref;
		}

		public function getLabel() {
			return $this->label;
		}
	}
?>