<?php
	/*
		Class Manager pour les relations avec la DB sur la table CONTENT_H
		@Author Yves Ponchelet
		@Version 1.1
		@Creation: 14/09/2023
		@Last update: 13/08/2025
	*/

	class Content_HManager 
	{
		private $db;

		public function __construct(?PDO $pdo = null) {
			$this->db = $pdo ?: (new Database())->getConnection();
		}

		public function getHistorique($id) {
			$query = 'SELECT ID id, R_LANG language, TITLE title, CONTENT content, META_TITLE metaTitle, META_DESCRIPTION metaDescription, DTE date, ACTION action FROM CONTENT_H WHERE ID_CONTENT = :id ORDER BY DTE DESC';

		  	$query = $this->db->prepare($query);
		  	$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_H');
			$query->execute();

			$listContent = $query->fetchAll();

			$query->closeCursor();

			foreach ($listContent as $content) {
				$manager = new Reference_DetailManager();
				$langue = $manager->GetLangue($content->getLanguage());

				$content->setLanguage($langue);
			}

			return $listContent;
		}

		// Snapshot d'une version de CONTENT_LANG dans CONTENT_H
		public function LogFromContentLangId(int $contentLangId, string $action, ?int $userId = null): void
		{
			$sql = "
				INSERT INTO CONTENT_H
					(ID_CONTENT, ID_CONTENT_LANG, R_LANG, R_CAT, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG, DTE, ACTION)
				SELECT
					FK_CONTENT, ID, R_LANG, R_CAT, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG, NOW(), :action
				FROM CONTENT_LANG
				WHERE ID = :id";

			$q = $this->db->prepare($sql);
			$q->bindValue(':action', $action, PDO::PARAM_STR);
			$q->bindValue(':id', $contentLangId, PDO::PARAM_INT);

			$q->execute();
		}

		// Variante bulk pour un FK_CONTENT (utile quand tu supprimes FR => toutes les langues)
		public function LogAllByMainId(int $mainContentId, string $action): void
		{
			$sql = "
				INSERT INTO CONTENT_H
					(ID_CONTENT, ID_CONTENT_LANG, R_LANG, R_CAT, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG, DTE, ACTION)
				SELECT
					FK_CONTENT, ID, R_LANG, R_CAT, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG, NOW(), :action
				FROM CONTENT_LANG
				WHERE FK_CONTENT = :fk";

			$q = $this->db->prepare($sql);
			$q->bindValue(':action', $action, PDO::PARAM_STR);
			$q->bindValue(':fk', $mainContentId, PDO::PARAM_INT);

			$q->execute();
		}

		public function Recuperation($id, $author) {
			$query = 'SELECT ID id, ID_CONTENT contentId, ID_CONTENT_LANG contentLangId, R_LANG language, R_CAT category, TITLE title, CONTENT content, META_TITLE metaTitle, META_DESCRIPTION metaDescription, SLUG slug, DTE date, ACTION action FROM CONTENT_H WHERE ID = :id ORDER BY DTE DESC';

		  	$query = $this->db->prepare($query);
		  	$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Content_H');
			$query->execute();

			$content = $query->fetch();

			$query->closeCursor();

			if ($content->getAction() === "DELETE") {
				$query = $this->db->prepare('INSERT INTO CONTENT_LANG (FK_CONTENT, R_LANG, R_CAT, FK_AUTHOR, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG slug, DATE_CRE, IS_PUBLISHED) VALUES(:contentId, :lang, :cat, :author, :title, :content, :metaTitle, :metaDescription, NOW(), :published)');

				$query->bindValue(':contentId', $content->getContentId(), PDO::PARAM_INT);
				$query->bindValue(':lang', $content->getLanguage(), PDO::PARAM_STR);
				$query->bindValue(':cat', $content->getCategory(), PDO::PARAM_STR);
				$query->bindValue(':author', $author, PDO::PARAM_INT);
				$query->bindValue(':title', $content->getTitle(), PDO::PARAM_STR);
				$query->bindValue(':content', $content->getContent(), PDO::PARAM_STR);
				$query->bindValue(':metaTitle', $content->getMetaTitle(), PDO::PARAM_STR);
				$query->bindValue(':metaDescription', $content->getMetaDescription(), PDO::PARAM_STR);
				$query->bindValue(':slug', $content->getSlug(), PDO::PARAM_STR);
				$query->bindValue(':published', false, PDO::PARAM_BOOL);
			}
			else {
				$query = $this->db->prepare('UPDATE CONTENT_LANG SET TITLE = :title, CONTENT = :content, META_TITLE = :metaTitle, META_DESCRIPTION = :metaDescription, SLUG = :slug, DATE_MOD = NOW(), IS_PUBLISHED = :published WHERE ID = :id');

				$query->bindValue(':id', $content->getContentLangId(), PDO::PARAM_INT);
				$query->bindValue(':title', $content->getTitle(), PDO::PARAM_STR);
				$query->bindValue(':content', $content->getContent(), PDO::PARAM_STR);
				$query->bindValue(':metaTitle', $content->getMetaTitle(), PDO::PARAM_STR);
				$query->bindValue(':metaDescription', $content->getMetaDescription(), PDO::PARAM_STR);
				$query->bindValue(':slug', $content->getSlug(), PDO::PARAM_STR);
				$query->bindValue(':published', false, PDO::PARAM_BOOL);
			}

			$query->execute();

			$query->closeCursor();
		}
	}
?>