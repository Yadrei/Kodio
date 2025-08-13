<?php
	/* 
		Classe Content_Main qui représente la vue V_CONTENT_MAIN en base de données
		@Author Yves P.
		@Version 1.0
		@Date Création: 14/09/2023
		@Dernière modification: 20/12/2023
	*/

	class V_Content_Main 
	{
		private $id, $contentId, $category, $author, $title, $slug, $dateCre, $dateMod, $published;

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

		// Setters
		public function setId($id) {
			$this->id = $id;
		}

		public function setContentId($id) {
			$this->idContent = $id;
		}

		public function setCategory($category) {
			$this->Category = $category;
		}

		public function setAuthor($author) {
			$this->author = $author;
		}

		public function setTitle($title){
			$this->title = $title;
		}

        public function setSlug($slug){
            $this->slug = $slug;
        }

		public function setDateCre(DateTime $dateCre) {
			$this->dateCre = $dateCre;
		}

		public function setDateMod(DateTime $dateMod) {
			$this->dateMod = $dateMod;
		}

		public function setPublished($published) {
			$this->published = $published;
		}

		// Getters
		public function getId() {
			return $this->id;
		}

		public function getContentId() {
			return $this->contentId;
		}

		public function getCategory() {
			return $this->category;
		}

		public function getAuthor() {
			return $this->author;
		}

		public function getTitle() {
			return $this->title;
		}

        public function getSlug(){
            return $this->slug;
        }

		public function getDateCre() {
			return (new DateTime($this->dateCre))->format("d/m/Y");
		}

		public function getDateMod() {
			if (!is_null($this->dateMod))
				return (new DateTime($this->dateMod))->format("d/m/Y");
			else
				return null;
		}

		public function getPublished() {
			if ($this->published)
				return 'Publié';
			else
				return 'Non publié';
		}
	}
?>