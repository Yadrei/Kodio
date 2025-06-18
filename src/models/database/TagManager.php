<?php
	/*
		Class Manager pour les relations avec la DB sur la table SETTINGS
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 17/11/2023
		@Last update: 18/06/2025
	*/

	class TagManager
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		// Méthodes privées
		private function Add(Tag $tag) {
			$query = $this->db->prepare('INSERT INTO TAGS (LABEL, TXT_COLOR) VALUES(:label, :textColor)');

			$query->bindValue(':label', $tag->getLabel(), PDO::PARAM_STR);
			$query->bindValue(':textColor', $tag->getTextColor(), PDO::PARAM_STR);

			$query->execute();

			$query->closeCursor();
		}

		private function Update(Tag $tag) {
			if (!is_null($tag->getLabel())) {
				$query = $this->db->prepare('UPDATE TAGS SET LABEL = :label, TXT_COLOR = :textColor, BG_COLOR = :bgColor WHERE ID = :id');

				$query->bindValue(':id', $tag->getId(), PDO::PARAM_INT);
				$query->bindValue(':label', $tag->getLabel(), PDO::PARAM_STR);
				$query->bindValue(':textColor', $tag->getTextColor(), PDO::PARAM_STR);
				$query->bindValue(':bgColor', $tag->getBgColor(), PDO::PARAM_STR);
			} 
			else if (!is_null($tag->getTextColor())){
				$query = $this->db->prepare('UPDATE TAGS SET TXT_COLOR = :textColor WHERE ID = :id');

				$query->bindValue(':id', $tag->getId(), PDO::PARAM_INT);
				$query->bindValue(':textColor', $tag->getTextColor(), PDO::PARAM_STR);
			} 
			else if (!is_null($tag->getBgColor())){
				$query = $this->db->prepare('UPDATE TAGS SET BG_COLOR = :bgColor WHERE ID = :id');

				$query->bindValue(':id', $tag->getId(), PDO::PARAM_INT);
				$query->bindValue(':bgColor', $tag->getBgColor(), PDO::PARAM_STR);
			}

			$query->execute();
		}

		// Méthodes publiques
		public function Delete($id) {
			$query = $this->db->prepare('DELETE FROM TAGS WHERE ID = :id');

			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
		}

		public function Save(Tag $tag) {
			if ($tag->isValid()) {
				if ($tag->isNew())
					$this->Add($tag);
				else
					$this->Update($tag);
			}
			else
				throw new Exception($tag -> getErrors());
		}

		public function SaveTextColor(Tag $tag) {
			if (!$tag->isNew())
				$this->Update($tag);
			else
				throw new Exception('ERROR');
		}

		public function SaveBgColor(Tag $tag) {
			if (!$tag->isNew())
				$this->Update($tag);
			else
				throw new Exception('ERROR');
		}

		public function GetAllTags() {
			$query = 'SELECT ID id, LABEL label, TXT_COLOR textColor, BG_COLOR bgColor FROM TAGS ORDER BY LABEL';

		  	$query = $this->db->prepare($query);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Tag');
			$query->execute();

			$listTags = $query->fetchAll();

			$query->closeCursor();

			return $listTags;
		}
	}
?>