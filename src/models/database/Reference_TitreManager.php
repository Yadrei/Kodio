<?php
	/*
		Class for the DB requests for References Titles
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 25/11/2021
		@Last update: 29/03/2023
	*/

	class Reference_TitreManager 
	{
		// Attributes
		protected $db;

		public function __Construct(PDO $db)
		{
			$this -> db = $db;
		}

		// Private methods

		/*
		private function Update(RefTitle $ref) {
			$query = $this -> db -> prepare('UPDATE REFS_TITLE SET REF = :ref, TITLE = :title WHERE ID = :id');

			$query = bindParam(':id', $ref -> GetId(), PDO::PARAM_INT);
			$query = bindParam(':ref', $ref -> GetRef(), PDO::PARAM_STR);
			$query = bindParam(':title', $ref -> GetTitle(), PDO::PARAM_STR);

			$query -> execute();
		}
		*/

		// Public methods
		public function Count() {
			// Not used for now
			return null;
		}

		public function Save(RefTitle $ref) {
			$query = $this->db->prepare('INSERT INTO REFS_TITLE (REF, TITLE) VALUES (:ref, :title)');

			$query->bindParam(':ref', $ref->GetRef(), PDO::PARAM_STR);
			$query->bindParam(':title', $ref->GetTitle(), PDO::PARAM_STR);

			$query->execute();
		}

		/*
		public function Save(RefTitle $ref) { // PAS VALABLE COMME PLUS D'ID QUI FAIT CHIER
			if ($ref->IsValid())
				if ($ref->IsNew())
					Add($ref);
				else
					Update($ref);
			else
				throw new Exception($ref->GetErrors());
		}
		*/

		public function Delete($ref) {
			$query = $this->db->prepare('DELETE FROM REFS_TITLE WHERE REF = :ref');

			$query->bindParam(':ref', $ref, PDO::PARAM_STR);

			$query->execute();
		}

		public function GetAllReferences() {
			$query = 'SELECT REF ref, TITLE title FROM REFS_TITLE ORDER BY REF';

		  	$query = $this->db->prepare($query);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'RefTitle');
			$query->execute();

			$listeRefs = $query->fetchAll();

			$query->closeCursor();

			return $listeRefs;
		}
	}
?>