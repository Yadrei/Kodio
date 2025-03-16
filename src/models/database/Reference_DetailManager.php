<?php
	/*
		Class Manager pour les relations avec la DB sur la table REFERENCES_D
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 10/09/2023
		@Last update: 16/03/2025
	*/

	class Reference_DetailManager 
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		public function Delete(Reference_Detail $ref) {
			$query = $this->db->prepare('DELETE FROM REFERENCES_D WHERE CLEF = :clef AND FK_REF = :ref');

			$query->bindValue(':clef', $ref->getClef(), PDO::PARAM_STR);
			$query->bindValue(':ref', $ref->getRef(), PDO::PARAM_STR);

			$query->execute();
		}

		public function getDetails($ref) {
			$query = 'SELECT CLEF clef, LABEL label FROM REFERENCES_D WHERE FK_REF = :ref';

			$query = $this->db->prepare($query);
			$query->bindParam(':ref', $ref, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Reference_Detail');
			$query->execute();

			$references = $query->fetchAll();

			$query->closeCursor();

			return $references;
		}

		public function CountLanguages() {
			$query = $this->db->prepare('SELECT COUNT(*) FROM REFERENCES_D WHERE FK_REF = "R_LANG"');

			$query->execute();

			return $query->fetchColumn();
		}

		public function getLangue($key) {
			$query = 'SELECT CLEF clef, LABEL label FROM REFERENCES_D WHERE CLEF = :key AND FK_REF = "R_LANG"';

			$query = $this->db->prepare($query);
			$query->bindParam(':key', $key, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Reference_Detail');
			$query->execute();

			$language = $query->fetch();

			$query->closeCursor();

			return $language;
		}

		public function getRole($key) {
			$query = 'SELECT LABEL label FROM REFERENCES_D WHERE CLEF = :key AND FK_REF = "R_ROLE"';

			$query = $this->db->prepare($query);
			$query->bindParam(':key', $key, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Reference_Detail');
			$query->execute();

			$role = $query->fetch();

			$query->closeCursor();

			return $role;
		}

		public function getTranslations($language) {
			$query = 'SELECT CLEF clef, LABEL label FROM REFERENCES_D WHERE CLEF <> :language AND FK_REF = "R_LANG"';

			$query = $this->db->prepare($query);
			$query->bindParam(':language', $language, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Reference_Detail');
			$query->execute();

			$languages = $query->fetchAll();

			$query->closeCursor();

			return $languages;
		}

		public function Save(Reference_Detail $ref) {
			$query = $this->db->prepare('INSERT INTO REFERENCES_D (CLEF, FK_REF, LABEL) VALUES (:clef, :ref, :label)');

			$query->bindValue(':clef', $ref->getClef(), PDO::PARAM_STR);
			$query->bindValue(':ref', $ref->getRef(), PDO::PARAM_STR);
			$query->bindValue(':label', $ref->getLabel(), PDO::PARAM_STR);

			$query->execute();
		}
	}
?>