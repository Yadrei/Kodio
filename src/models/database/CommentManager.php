<?php
	/*
		Class Manager pour les relations avec la DB sur la table COMMENTS
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 19/02/2025
		@Last update: 20/02/2025
	*/

	class CommentManager
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		// Méthodes privées
		private function Add(Comment $comment) {
			$query = $this->db->prepare('INSERT INTO COMMENTS (NICKNAME, FK_CONTENT, CONTENT, DTE) VALUES(:nickname, :content, :text, NOW())');

			$query->bindValue(':nickname', $comment->getNickname(), PDO::PARAM_STR);
			$query->bindValue(':content', $comment->getContent(), PDO::PARAM_INT);
            $query->bindValue(':text', $comment->GetText(), PDO::PARAM_STR);

			$query->execute();

			$query->closeCursor();
		}

		// Méthodes publiques
		public function Delete($id) {
			$query = $this->db->prepare('DELETE FROM COMMENTS WHERE ID = :id');

			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
		}

		public function Save(Comment $comment) {
			if ($comment->isValid()) {
				if ($comment->isNew())
					$this->Add($comment);
			}
			else
				throw new Exception($comment -> getErrors());
		}

		public function GetCommentsFromContent($id) {
			$query = 'SELECT ID id, NICKNAME nickname, CONTENT text, DTE dateComment FROM COMMENTS WHERE FK_CONTENT = :id ORDER BY DTE';

		  	$query = $this->db->prepare($query);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Comment');
			$query->execute();

			$listComments = $query->fetchAll();

			$query->closeCursor();

			return $listComments;
		}
	}
?>