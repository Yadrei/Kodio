<?php
	/*
		Class Manager pour les relations avec la DB sur la vue V_CONTENT_LANG
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 14/09/2023
		@Last update: 20/12/2023
	*/

	class V_Content_LangManager 
	{
		private $db;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		public function getTranslations($content) {
			$query = 'SELECT ID id, FK_CONTENT contentId, LANGUAGE language, AUTHOR author, TITLE title, SLUG slug, DATE_CRE dateCre, DATE_MOD dateMod, IS_PUBLISHED published FROM V_CONTENT_LANG WHERE FK_CONTENT = :content ORDER BY DATE_CRE DESC';

		  	$query = $this -> db -> prepare($query);
		  	$query -> bindParam(':content', $content, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'V_Content_Lang');
			$query->execute();

			$content = $query->fetchAll();

			$query->closeCursor();

			return $content;
		}
	}
?>