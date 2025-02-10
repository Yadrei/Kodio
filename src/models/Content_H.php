<?php
	/* 
		Classe Content_H qui représente la table Content_H en base de données
		@Author Yves P.
		@Version 1.0
		@Date Création: 19/09/2023
		@Dernière modification: 06/12/2023
	*/

	class Content_H
	{
		private $id, $contentId, $contentLangId, $language, $category, $title, $content, $image, $metaTitle, $metaDescription, $slug, $date, $action;

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

		public function setContentLangId($id) {
			$this->contentLangId = $id;
		}

		public function setLanguage($language) {
			$this->language = $language;
		}

		public function setCategory($category) {
			$this->category = $category;
		}

		public function setTitle($title) {
			$this->title = $title;
		}

		public function setContent($content) {
			$this->content = $content;
		}

        public function setImage($image) {
            $this->image = $image;
        }

		public function setMetaTitle($metaTitle) {
			$this->metaTitle = $metaTitle;
		}

		public function setMetaDescription($metaDescription) {
			$this->metaDescription = $metaDescription;
		}

		public function setSlug($slug) {
			$this->slug = $slug;
		}

		public function setDate(DateTime $date) {
			$this->date = $date;
		}

		public function setAction($action) {
			$this->action = $action;
		}


		// Getters
		public function getId() {
			return $this->id;
		}

		public function getContentId() {
			return $this->contentId;
		}

		public function getContentLangId() {
			return $this->contentLangId;
		}

		public function getLanguage() {
			return $this->language;
		}

		public function getCategory() {
			return $this->category;
		}

		public function getTitle() {
			return $this->title;
		}

		public function getContent() {
			return $this->content;
		}

        public function getImage() {
            return $this->image;
        }

		public function getMetaTitle() {
			return $this->metaTitle;
		}

		public function getMetaDescription() {
			return $this->metaDescription;
		}

		public function getSlug() {
			return $this->slug;
		}

		public function getDate() {
			return (new DateTime($this->date))->format("d/m/Y à H:i:s");
		}

		public function getAction() {
			return $this->action;
		}
	}
?>