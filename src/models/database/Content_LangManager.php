<?php
	/*
		Class Manager pour les relations avec la DB sur la table CONTENT_LANG
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 18/09/2023
		@Last update: 19/02/2025
	*/

	class Content_LangManager 
	{
		private $db, $contentId = null, $mainId = null;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		// Méthodes privées
		private function Add(Content_Lang $content) {
			if (is_null($this->contentId)) {
				$query = $this->db->prepare('INSERT INTO CONTENT VALUES (NULL, NOW())'); // Pour générer l'ID global à toutes les langues
				$query->execute();

                $this->mainId = $this->contentId = $this->db->lastInsertId();
			}

			$query = $this->db->prepare('INSERT INTO CONTENT_LANG (FK_CONTENT, R_LANG, R_CAT, FK_AUTHOR, TITLE, CONTENT, HEADING_IMAGE, META_TITLE, META_DESCRIPTION, SLUG, DATE_CRE, DATE_PUBLICATION, IS_PUBLISHED) VALUES(:contentId, :lang, :cat, :author, :title, :content, :image, :metaTitle, :metaDescription, :slug, NOW(), :datePublication, :published)');

			$query->bindValue(':contentId', $this->contentId, PDO::PARAM_INT);
			$query->bindValue(':lang', $content->getLanguage(), PDO::PARAM_STR);
			$query->bindValue(':cat', $content->getCategory(), PDO::PARAM_STR);
			$query->bindValue(':author', $content->getAuthor(), PDO::PARAM_INT);
			$query->bindValue(':title', $content->getTitle(), PDO::PARAM_STR);
			$query->bindValue(':content', $content->getContent(), PDO::PARAM_STR);
            $query->bindValue(':image', $content->getImage(), PDO::PARAM_STR);
			$query->bindValue(':metaTitle', $content->getMetaTitle(), PDO::PARAM_STR);
			$query->bindValue(':metaDescription', $content->getMetaDescription(), PDO::PARAM_STR);

            // Gérer le NULL proprement
			$datePub = $content->getDatePublication();

			if ($datePub instanceof DateTimeInterface) 
				$query->bindValue(':datePublication', $datePub->format('Y-m-d H:i:s'), PDO::PARAM_STR);
			 else 
				$query->bindValue(':datePublication', null, PDO::PARAM_NULL);

			$query->bindValue(':slug', $content->getSlug(), PDO::PARAM_STR);
			$query->bindValue(':published', $content->getPublished(), PDO::PARAM_BOOL);

			$query->execute();

			$contentId = (int) $this->db->lastInsertId();

			// Historique
			(new Content_HManager($this->db))->LogFromContentLangId($contentId, 'ADD');

			$query->closeCursor();

			return $contentId;
		}

		private function Update(Content_Lang $content) {
            if (is_null($content->getImage())) {
                $query = $this->db->prepare('UPDATE CONTENT_LANG SET TITLE = :title, CONTENT = :content, META_TITLE = :metaTitle, META_DESCRIPTION = :metaDescription, SLUG = :slug, DATE_MOD = NOW(), IS_PUBLISHED = :published WHERE ID = :id');

                $query->bindValue(':id', $content->getId(), PDO::PARAM_INT);
                $query->bindValue(':title', $content->getTitle(), PDO::PARAM_STR);
                $query->bindValue(':content', $content->getContent(), PDO::PARAM_STR);
                $query->bindValue(':metaTitle', $content->getMetaTitle(), PDO::PARAM_STR);
                $query->bindValue(':metaDescription', $content->getMetaDescription(), PDO::PARAM_STR);
                $query->bindValue(':slug', $content->getSlug(), PDO::PARAM_STR);
                $query->bindValue(':published', $content->getPublished(), PDO::PARAM_BOOL);
            }
			else {
                $query = $this->db->prepare('UPDATE CONTENT_LANG SET TITLE = :title, CONTENT = :content, HEADING_IMAGE = :image, META_TITLE = :metaTitle, META_DESCRIPTION = :metaDescription, SLUG = :slug, DATE_MOD = NOW(), IS_PUBLISHED = :published WHERE ID = :id');

                $query->bindValue(':id', $content->getId(), PDO::PARAM_INT);
                $query->bindValue(':title', $content->getTitle(), PDO::PARAM_STR);
                $query->bindValue(':content', $content->getContent(), PDO::PARAM_STR);
                $query->bindValue(':image', $content->getImage(), PDO::PARAM_STR);
                $query->bindValue(':metaTitle', $content->getMetaTitle(), PDO::PARAM_STR);
                $query->bindValue(':metaDescription', $content->getMetaDescription(), PDO::PARAM_STR);
                $query->bindValue(':slug', $content->getSlug(), PDO::PARAM_STR);
                $query->bindValue(':published', $content->getPublished(), PDO::PARAM_BOOL);
            }

			$query->execute();

            $this->mainId = $content->getContentId();

			// Historique
    		(new Content_HManager($this->db))->LogFromContentLangId((int)$content->getId(), 'UPDATE');

			return $content->getId();
		}

		// Méthodes publiques
		public function ChangeStatus($id) {
			$query = $this->db->prepare('
				UPDATE CONTENT_LANG
				SET IS_PUBLISHED = 1 - IS_PUBLISHED,
					DATE_MOD = NOW()
				WHERE ID = :id');

			$query->bindValue(':id', $id, PDO::PARAM_INT);
			$query->execute();
		}

		public function CountContentUnpublished() {
			$query = $this->db->prepare('SELECT COUNT(*) FROM CONTENT_LANG WHERE IS_PUBLISHED  = 0');

			$query->execute();

			return $query->fetchColumn();
		}

		public function Delete($id) {
			/* 
				On regarde si on veut supprimer la langue FR ou pas.
				Si oui, on supprime toutes les langues pour le même contenu principal

				RAPPEL: La langue FR est considérée comme la langue principale dans tous les cas !!
			*/

			$query = $this->db->prepare('SELECT R_LANG, FK_CONTENT FROM CONTENT_LANG WHERE id = :id');

			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);


			$this->db->beginTransaction();
			try {
				$h = new Content_HManager($this->db);

				if ($result['R_LANG'] === 'FR') {
					// Historique de TOUTES les traductions
					$h->LogAllByMainId((int)$result['FK_CONTENT'], 'DELETE');

					// suppression cascade logique
					$q = $this->db->prepare('DELETE FROM CONTENT_LANG WHERE FK_CONTENT = :id');
					$q->bindParam(':id', $result['FK_CONTENT'], PDO::PARAM_INT);
					$q->execute();

					$q = $this->db->prepare('DELETE FROM CONTENT WHERE ID = :id');
					$q->bindParam(':id', $result['FK_CONTENT'], PDO::PARAM_INT);
					$q->execute();
				} else {
					// Historique de la version spécifique
					$h->LogFromContentLangId((int)$id, 'DELETE');

					$q = $this->db->prepare('DELETE FROM CONTENT_LANG WHERE ID = :id');
					$q->bindParam(':id', $id, PDO::PARAM_INT);
					$q->execute();
				}

				$this->db->commit();
			} catch (\Throwable $e) {
				if ($this->db->inTransaction()) $this->db->rollBack();
				throw $e;
			}
		}

		public function GetContentMenu() {
			$query = $this->db->prepare('SELECT ID id, FK_CONTENT contentId, R_LANG language, R_CAT category, FK_AUTHOR author, TITLE title, CONTENT content, META_TITLE metaTitle, META_DESCRIPTION metaDescription, IS_PUBLISHED published FROM CONTENT_LANG WHERE R_CAT IN ("SYSTEM", "MENU") ORDER BY R_CAT, R_LANG, DATE_CRE');

			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_Lang');
			$query->execute();

			$listContents = $query->fetchAll();

			$query->closeCursor();

			return $listContents;
		}

		public function GetContentById($id) {
		  	$query = $this->db->prepare('SELECT ID id, FK_CONTENT contentId, R_LANG language, R_CAT category, FK_AUTHOR author, TITLE title, CONTENT content, META_TITLE metaTitle, META_DESCRIPTION metaDescription, SLUG slug, IS_PUBLISHED published FROM CONTENT_LANG WHERE ID = :id');

		  	$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_Lang');
			$query->execute();

			$content = $query->fetch();

			$query->closeCursor();

			return $content;
		}

		public function GetContentByMainContentId($id) {
		  	$query = $this->db->prepare('SELECT ID id, FK_CONTENT contentId, R_LANG language, R_CAT category, FK_AUTHOR author, TITLE title, CONTENT content, META_TITLE metaTitle, META_DESCRIPTION metaDescription, SLUG slug, IS_PUBLISHED published FROM CONTENT_LANG WHERE FK_CONTENT = :id');

		  	$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_Lang');
			$query->execute();

			$listContents = $query->fetchAll();

			$query->closeCursor();

			return $listContents;
		}

		public function GetContentByLanguage($language) {
			$query = $this->db->prepare('SELECT ID id, FK_CONTENT contentId, R_LANG language, R_CAT category, FK_AUTHOR author, TITLE title, CONTENT content, HEADING_IMAGE image, META_TITLE metaTitle, META_DESCRIPTION metaDescription, SLUG slug, IS_PUBLISHED published FROM CONTENT_LANG WHERE R_LANG = :language AND R_CAT NOT IN ("SYSTEM", "MENU") AND IS_PUBLISHED IS TRUE');

		  	$query->bindParam(':language', $language, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_Lang');
			$query->execute();

			$listContents = $query->fetchAll();

			$query->closeCursor();

			foreach ($listContents as $content) {
				$manager = new UserManager();
				$author = $manager->GetUserById($content->getAuthor());

				$content->setAuthor($author);
			}

			return $listContents;
		}

		public function GetContentBySlug($slug) {
			$query = $this->db->prepare('SELECT ID id, FK_CONTENT contentId, R_LANG language, R_CAT category, FK_AUTHOR author, TITLE title, CONTENT content, HEADING_IMAGE image, META_TITLE metaTitle, META_DESCRIPTION metaDescription, IS_PUBLISHED published FROM CONTENT_LANG WHERE UPPER(SLUG) = UPPER(:slug) AND IS_PUBLISHED IS TRUE');

			//$query->debugDumpParams();

		  	$query->bindParam(':slug', $slug, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_Lang');
			$query->execute();

			$content = $query->fetch();

			$query->closeCursor();

			return $content;
		}

        public function GetPreview($slug) {
            $query = $this->db->prepare('SELECT ID id, FK_CONTENT contentId, R_LANG language, R_CAT category, FK_AUTHOR author, TITLE title, CONTENT content, META_TITLE metaTitle, META_DESCRIPTION metaDescription, IS_PUBLISHED published FROM CONTENT_LANG WHERE UPPER(SLUG) = UPPER(:slug)');

            $query->bindParam(':slug', $slug, PDO::PARAM_STR);
            $query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_Lang');
            $query->execute();

            $content = $query->fetch();

            $query->closeCursor();

            return $content;
        }

		public function Save($values = []) {
			try {
				$this->db->beginTransaction();

				$savedContentIds = [];

				foreach ($values as $content) 
				{
					if ($content->isValid()) {
						if ($content->isNew()) 
							$savedContentIds[$content->getLanguage()] = $this->Add($content);
						else 
							$savedContentIds[$content->getLanguage()] = $this->Update($content);
					}
					else
						throw new Exception('Erreur !!');
				}

				$this->db->commit();
				$this->contentId = null;

                return [
					'mainId'=>$this->mainId,
					'contentLangIds'=>$savedContentIds
				];
			} 
			catch (Exception $e) {
				$this->db->rollBack();
		        
		        throw new Exception("Erreur à la sauvegarde - ". $e);
			}
		}
	}
?>