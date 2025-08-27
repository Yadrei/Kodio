<?php
	/*
		Class Manager pour les relations avec la DB sur la table CONTENT_LANG_SEO
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 27/08/2025
		@Last update: 27/08/2025
	*/

	class Content_Lang_SEOManager 
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		// Méthodes privées
		private function Add(Content_Lang_SEO $content) {
            $sql = "INSERT INTO CONTENT_LANG_SEO 
                        (FK_CONTENT_LANG, META_TITLE, META_DESCRIPTION, CANONICAL_URL, ROBOTS_INDEX, ROBOTS_FOLLOW, OG_TITLE, OG_DESCRIPTION, OG_IMAGE, SCHEMA_TYPE, SCHEMA_DESCRIPTION) 
                    VALUES(:fkContentLang, :metaTitle, :metaDescription, :url, :robotsIndex, :robotsFollow, :title, :description, :image, :schemaType, :schemaDescription)";

			$query = $this->db->prepare($sql);

			$query->bindValue(':fkContentLang', $content->getFkContentLang(), PDO::PARAM_INT);
            $query->bindValue(':metaTitle', $content->getMetaTitle(), PDO::PARAM_STR);
            $query->bindValue(':metaDescription', $content->getMetaDescription(), PDO::PARAM_STR);
            $query->bindValue(':url', $content->getUrl(), PDO::PARAM_STR);
            $query->bindValue(':robotsIndex', $content->getRobotsIndex(), PDO::PARAM_INT);
            $query->bindValue(':robotsFollow', $content->getRobotsFollow(), PDO::PARAM_INT);
            $query->bindValue(':title', $content->GetTitle(), PDO::PARAM_STR);
            $query->bindValue(':description', $content->getDescription(), PDO::PARAM_STR);
            $query->bindValue(':image', $content->getImage(), PDO::PARAM_STR);
            $query->bindValue(':schemaType', $content->getSchemaType(), PDO::PARAM_STR);
            $query->bindValue(':schemaDescription', $content->getSchemaDescription(), PDO::PARAM_STR);

			$query->execute();

			$query->closeCursor();
		}

		private function Update(Content_Lang_SEO $content) {
            $sql = "UPDATE CONTENT_LANG_SEO
                     SET FK_CONTENT_LANG = :fkContentLang,
                        META_TITLE = :metaTitle,
                        META_DESCRIPTION = :metaDescription,
                        CANONICAL_URL = :url,
                        ROBOTS_INDEX = :robotsIndex,
                        ROBOTS_FOLLOW = :robotsFollow,
                        OG_TITLE = :title,
                        OG_DESCRIPTION = :description,
                        OG_IMAGE = :image,
                        SCHEMA_TYPE = :schemaType,
                        SCHEMA_DESCRIPTION = :schemaDescription
                     WHERE ID = :id";

            $query = $this->db->prepare($sql);

            $query->bindValue(':id', $content->getId(), PDO::PARAM_INT);
            $query->bindValue(':fkContentLang', $content->getFkContentLang(), PDO::PARAM_INT);
            $query->bindValue(':metaTitle', $content->getMetaTitle(), PDO::PARAM_STR);
            $query->bindValue(':metaDescription', $content->getMetaDescription(), PDO::PARAM_STR);
            $query->bindValue(':url', $content->getUrl(), PDO::PARAM_STR);
            $query->bindValue(':robotsIndex', $content->getRobotsIndex(), PDO::PARAM_INT);
            $query->bindValue(':robotsFollow', $content->getRobotsFollow(), PDO::PARAM_INT);
            $query->bindValue(':title', $content->GetTitle(), PDO::PARAM_STR);
            $query->bindValue(':description', $content->getDescription(), PDO::PARAM_STR);
            $query->bindValue(':image', $content->getImage(), PDO::PARAM_STR);
            $query->bindValue(':schemaType', $content->getSchemaType(), PDO::PARAM_STR);
            $query->bindValue(':schemaDescription', $content->getSchemaDescription(), PDO::PARAM_STR);


			$query->execute();

            $query->closeCursor();
		}

		// Méthodes publiques
		public function GetSEOById($id) {
		  	$sql = "SELECT 
                        ID id, 
                        FK_CONTENT_LANG fkContentLang, 
                        META_TITLE metaTitle,
                        META_DESCRIPTION metaDescription,
                        CANONICAL_URL url,
                        ROBOTS_INDEX robotsIndex,
                        ROBOTS_FOLLOW robotsFollow,
                        OG_TITLE title,
                        OG_DESCRIPTION description,
                        OG_IMAGE image,
                        SCHEMA_TYPE schemaType,
                        SCHEMA_DESCRIPTION schemaDescription
                    FROM CONTENT_LANG_SEO
                    WHERE ID = :id";

            $query = $this->db->prepare($sql);

		  	$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_Lang_SEO');
			$query->execute();

			$seo = $query->fetch();

			$query->closeCursor();

			return $seo;
		}

		public function GetSEOByFkContentId($id) {
		  	$sql = "SELECT 
                        ID id, 
                        FK_CONTENT_LANG fkContentLang, 
                        META_TITLE metaTitle,
                        META_DESCRIPTION metaDescription,
                        CANONICAL_URL url,
                        ROBOTS_INDEX robotsIndex,
                        ROBOTS_FOLLOW robotsFollow,
                        OG_TITLE title,
                        OG_DESCRIPTION description,
                        OG_IMAGE image,
                        SCHEMA_TYPE schemaType,
                        SCHEMA_DESCRIPTION schemaDescription
                    FROM CONTENT_LANG_SEO
                    WHERE FK_CONTENT_LANG = :id";

            $query = $this->db->prepare($sql);

		  	$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_Lang_SEO');
			$query->execute();

			$seo = $query->fetch();

			$query->closeCursor();

			return $seo;
		}

		public function Save($values = []) {
			try {
				$this->db->beginTransaction();

				foreach ($values as $content) 
				{
					if ($content->isValid()) {
						if ($content->isNew())
							$this->Add($content);
						else 
							$this->Update($content);
					}
					else
						throw new Exception('Erreur !!');
				}

				$this->db->commit();
			} 
			catch (Exception $e) {
				$this->db->rollBack();
		        
		        throw new Exception("Erreur à la sauvegarde - ". $e);
			}
		}
	}
?>