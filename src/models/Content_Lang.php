<?php
	/* 
		Classe Content_Lang qui représente la vtable CONTENT_LANG en base de données
		@Author Yves P.
		@Version 1.0
		@Date Création: 18/09/2023
		@Dernière modification: 29/12/2023
	*/

	class Content_Lang
	{
		private $id, $contentId, $language, $category, $author, $title, $content, $image, $metaTitle, $metaDescription, $slug, $dateCre, $datePublication, $dateMod, $published;

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
			return !(empty($this->language) || empty($this->category) || empty($this->author) || empty($this->title) || empty($this->metaTitle) || empty($this->metaDescription));
		}

		// Setters
		public function setId($id) {
			$this->id = $id;
		}

		public function setContentId($id) {
			$this->contentId = $id;
		}

		public function setLanguage($language) {
			$this->language = $language;
		}

		public function setCategory($category) {
			$this->category = $category;
		}

		public function setAuthor($author) {
			$this->author = $author;
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

		public function setDateCre(DateTime $dateCre) {
			$this->dateCre = $dateCre;
		}

        public function setDatePublication(DateTime $datePublication) {
            $this->datePublication = $datePublication;
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

		public function getLanguage() {
			return $this->language;
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

		public function getDateCre() {
			return (new DateTime($this->dateCre))->format("d/m/Y");
		}

        public function getDatePublication() {
            return $this->datePublication;
        }

		public function getDateMod() {
			if (!is_null($this->dateMod))
				return (new DateTime($this->dateMod))->format("d/m/Y");
			else
				return null;
		}

		public function getPublished() {
			return $this->published;
		}
	}
?>