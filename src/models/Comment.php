<?php
	/* 
		Classe Comment qui représente la table COMMENTS en base de données
		@Author Yves P.
		@Version 1.0
		@Date Création: 19/02/2025
		@Dernière modification: 20/02/2025
	*/

	class Comment 
	{
	    private $errors = [], $id, $nickname, $fKContent, $text, $dateComment;

	    // Constantes pour les erreurs
		const INVALID_NICKNAME = "Le nickname n'est pas bon";
		const INVALID_TEXT = "Le texte n'est pas conforme";

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
			return !(empty($this->nickname) || empty($this->text));
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

        public function setContent($content) {
            $this->fKContent = $content;
        }

		public function setText($text) {
			if (!is_string($text) || empty($text))
				$this->errors[] = self::INVALID_TEXT;
			else
				$this->text = $text;
		}

        public function setDateComment(DateTime $dateComment) {
            $this->dateComment = $dateComment;
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

        public function getContent() {
            return $this->fkContent;
        }

		public function getText() {
			return $this->text;
		}

        public function getDateComment() {
			return (new DateTime($this->dateComment))->format("d/m/Y");
		}
	}	
?>