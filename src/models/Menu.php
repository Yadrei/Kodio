<?php
	/* 
		Classe Menu qui représente la table MENU en base de données
		@Author Yves P.
		@Version 1.0
		@Date Création: 12/10/2023
		@Dernière modification: 15/10/2023
	*/

	class Menu
	{
		private $errors = [], $id, $parent, $language, $content, $label, $ordre;

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
			return !(empty($this->language) || empty($this->label));
		}

		// Setters
		public function setId($id) {
			$this->id = $id;
		}

		public function setParent($parent) {
			$this->parent = $parent;
		}

		public function setLanguage($language) {
			$this->language = $language;
		}

		public function setContent($content) {
			$this->content = $content;
		}

		public function setLabel($label) {
			$this->label = $label;
		}

		public function setOrdre($ordre) {
			$this->ordre = $ordre;
		}

		// Getters
		public function getErrors() {
			return $this->errors;
		}

		public function getId() {
			return $this->id;
		}

		public function getParent() {
			return $this->parent;
		}

		public function getLanguage() {
			return $this->language;
		}

		public function getContent() {
			return $this->content;
		}

		public function getLabel() {
			return $this->label;
		}

		public function getOrdre() {
			return $this->ordre;
		}
	}
?>