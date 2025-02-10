<?php
	/*
		Class Manager pour les relations avec la DB sur la table USERS
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 14/09/2023
		@Last update: 20/12/2023
	*/

	class V_Content_MainManager 
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		public function Count() {
			$query = $this->db->prepare('SELECT COUNT(*) FROM V_CONTENT_MAIN');

			$query->execute();

			return $query->fetchColumn();
		}

		public function getAllContent($begin = -1, $limit = -1) {
			$query = 'SELECT ID id, FK_CONTENT contentId, CAT category, AUTHOR author, TITLE title, SLUG slug, DATE_CRE dateCre, DATE_MOD dateMod, IS_PUBLISHED published FROM V_CONTENT_MAIN ORDER BY DATE_CRE DESC';

			// Checking parameters
		    if ($begin != -1 || $limit != -1)
		      $query .= ' LIMIT '.(int) $limit.' OFFSET '.(int) $begin;

		  	$query = $this -> db -> prepare($query);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'V_Content_Main');
			$query->execute();

			$content = $query->fetchAll();

			$query->closeCursor();

			return $content;
		}
	}
?>