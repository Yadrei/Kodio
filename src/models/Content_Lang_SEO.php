<?php
	/* 
		Classe Content_Lang_SEO qui représente la table CONTENT_LANG_SEO en base de données
		@Author Yves P.
		@Version 1.0
		@Date Création: 27/08/2025
		@Dernière modification: 27/08/2025
	*/

	class Content_Lang_SEO
	{
		private $id, $fkContentLang, $metaTitle, $metaDescription, $url, $robotsIndex, $robotsFollow, $title, $description, $image, $schemaType, $schemaDescription;

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
			return !(empty($this->metaTitle) || empty($this->metaDescription));
		}

		// Setters
		public function setId($id) {
			$this->id = $id;
		}

		public function setFkContentLang($id) {
			$this->fkContentLang = $id;
		}

		public function setMetaTitle($metaTitle) {
			$this->metaTitle = $metaTitle;
		}

        public function setMetaDescription($metaDescription) {
			$this->metaDescription = $metaDescription;
		}

		public function setUrl($url) {
			$this->url = $url;
		}

		public function setRobotsIndex($robotsIndex) {
			$this->robotsIndex = $robotsIndex;
		}

		public function setRobotsFollow($robotsFollow) {
			$this->robotsFollow = $robotsFollow;
		}

		public function setTitle($title) {
			$this->title = $title;
		}

		public function setDescription($description) {
			$this->description = $description;
		}

        public function setImage($image) {
            $this->image = $image;
        }

		public function setSchemaType($schemaType) {
			$this->schemaType = $schemaType;
		}

		public function setSchemaDescription($schemaDescription) {
			$this->schemaDescription = $schemaDescription;
		}

		// Getters
		public function getId() {
			return $this->id;
		}

		public function getFkContentLang() {
			return $this->fkContentLang;
		}

		public function getMetaTitle() {
			return $this->metaTitle;
		}

		public function getMetaDescription() {
			return $this->metaDescription;
		}

		public function getUrl() {
			return $this->url;
		}

		public function getRobotsIndex() {
			return $this->robotsIndex;
		}

		public function getRobotsFollow() {
			return $this->robotsFollow;
		}

		public function getTitle() {
			return $this->title;
		}

		public function getDescription() {
			return $this->description;
		}

        public function getImage() {
            return $this->image;
        }

		public function getSchemaType() {
			return $this->schemaType;
		}

		public function getSchemaDescription() {
			return $this->schemaDescription;
		}
	}
?>