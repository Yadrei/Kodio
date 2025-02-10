<?php
	/*
		Class Manager pour les relations avec la DB sur la table SETTINGS
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 17/11/2023
		@Last update: 23/11/2023
	*/

	class TagManager
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		// Méthodes privées
		private function Add(Tag $tag) {
			$query = $this->db->prepare('INSERT INTO TAGS (LABEL, COLOR) VALUES(:label, :color)');

			$query->bindValue(':label', $tag->getLabel(), PDO::PARAM_STR);
			$query->bindValue(':color', $tag->getColor(), PDO::PARAM_STR);

			$query->execute();

			$query->closeCursor();
		}

		private function Update(Tag $tag) {
			if (!is_null($tag->getLabel())) {
				$query = $this->db->prepare('UPDATE TAGS SET LABEL = :label, COLOR = :color WHERE ID = :id');

				$query->bindValue(':id', $tag->getId(), PDO::PARAM_INT);
				$query->bindValue(':label', $tag->getLabel(), PDO::PARAM_STR);
				$query->bindValue(':color', $tag->getColor(), PDO::PARAM_STR);
			}
			else {
				$query = $this->db->prepare('UPDATE TAGS SET COLOR = :color WHERE ID = :id');

				$query->bindValue(':id', $tag->getId(), PDO::PARAM_INT);
				$query->bindValue(':color', $tag->getColor(), PDO::PARAM_STR);
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

		public function SaveColor(Tag $tag) {
			if (!$tag->isNew())
				$this->Update($tag);
			else
				throw new Exception('ERROR');
		}

		public function GetAllTags() {
			$query = 'SELECT ID id, LABEL label, COLOR color FROM TAGS ORDER BY LABEL';

		  	$query = $this->db->prepare($query);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Tag');
			$query->execute();

			$listTags = $query->fetchAll();

			$query->closeCursor();

			return $listTags;
		}
	}
?>