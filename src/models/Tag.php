<?php
	/* 
		Classe Tags qui représente la table TAGS en base de données
		@Author Yves P.
		@Version 1.0
		@Date Création: 17/11/2023
		@Dernière modification: 17/11/2023
	*/

	class Tag 
	{
	    private $errors = [], $id, $label, $color;

	    // Constantes pour les erreurs
		const INVALID_LABEL = "Le libellé n'est pas bon";
		const INVALID_COLOR = "Veuillez indiquer une couleur";

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
			return !(empty($this->label) || empty($this->color));
		}

		// Setters
		public function setId($id) {
			$this->id = $id;
		}

		public function setLabel($label) {
			if (!is_string($label) || empty($label))
				$this->errors[] = self::INVALID_LABEL;
			else
				$this->label = $label;
		}

		public function setColor($color) {
			if (!is_string($color) || empty($color))
				$this->errors[] = self::INVALID_COLOR;
			else
				$this->color = $color;
		}

		// Getters
		public function getErrors() {
			return $this->errors;
		}

		public function getId() {
			return $this->id;
		}

		public function getLabel() {
			return $this->label;
		}

		public function getColor() {
			return $this->color;
		}
	}	
?>