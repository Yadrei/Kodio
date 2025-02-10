<?php
	/*
		Class Manager pour les relations avec la DB sur la table MENU
		@Author Yves Ponchelet
		@Version 1.0
		@Creation: 12/10/2023
		@Last update: 19/10/2023
	*/

	class MenuManager 
	{
		private $db, $contentId = null;

		public function __construct() {
			$this->db = (new Database())->getConnection();
		}

		// Méthodes privées
		private function Add(Menu $menu) {
			$query = $this->db->prepare('INSERT INTO MENU (PARENT_ID, R_LANG, FK_CONTENT, LABEL, ORDRE) VALUES(:parent, :language, :content, :label, :ordre)');

			$query->bindValue(':parent', $menu->getParent(), PDO::PARAM_INT);
			$query->bindValue(':language', $menu->getLanguage(), PDO::PARAM_STR);
			$query->bindValue(':content', $menu->getContent(), PDO::PARAM_INT);
			$query->bindValue(':label', $menu->getLabel(), PDO::PARAM_STR);
			$query->bindValue(':ordre', $menu->getOrdre(), PDO::PARAM_INT);

			$query->execute();

			$query->closeCursor();
		}

		private function Update(Menu $menu) {
			$query = $this->db->prepare('UPDATE MENU SET PARENT_ID = :parent, R_LANG = :lang, FK_CONTENT = :content, LABEL = :label, ORDRE = :ordre WHERE ID = :id');

			$query->bindValue(':id', $menu->getId(), PDO::PARAM_INT);
			$query->bindValue(':parent', $menu->getParent(), PDO::PARAM_INT);
			$query->bindValue(':lang', $menu->getLanguage(), PDO::PARAM_STR);
			$query->bindValue(':content', $menu->getContent(), PDO::PARAM_INT);
			$query->bindValue(':label', $menu->getLabel(), PDO::PARAM_STR);
			$query->bindValue(':ordre', $menu->getOrdre(), PDO::PARAM_INT);

			$query->execute();
		}

		// Méthodes publiques
		public function Delete($id) {
			$query = $this->db->prepare('DELETE FROM MENU WHERE ID = :id');

			$query->bindParam(':id', $id, PDO::PARAM_INT);

			$query->execute();
		}

		public function GetMainMenuByLang($language) {
			$query = $this->db->prepare('SELECT ID id, PARENT_ID parent, R_LANG language, FK_CONTENT content, LABEL label, ORDRE ordre FROM MENU WHERE PARENT_ID IS NULL AND R_LANG = :language ORDER BY R_LANG, ORDRE ASC');

			$query->bindParam(':language', $language, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Menu');
			$query->execute();

			$listMenu = $query->fetchAll();

			$query->closeCursor();

			foreach ($listMenu as $menu) {
				$manager = new Content_LangManager();
				$content = $manager->GetContentById($menu->getContent());
				$menu->setContent($content);
			}

			return $listMenu;
		}

		public function GetSubMenuByLang($language) {
			$query = $this->db->prepare('SELECT ID id, PARENT_ID parent, R_LANG language, FK_CONTENT content, LABEL label, ORDRE ordre FROM MENU WHERE PARENT_ID IS NOT NULL AND R_LANG = :language ORDER BY R_LANG, ORDRE ASC');

			$query->bindParam(':language', $language, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Menu');
			$query->execute();

			$listMenu = $query->fetchAll();

			$query->closeCursor();

			foreach ($listMenu as $menu) {
				$manager = new Content_LangManager();
				$content = $manager->GetContentById($menu->getContent());
				$menu->setContent($content);
			}

			return $listMenu;
		}

		public function GetAllMainMenu() {
			$query = $this->db->prepare('SELECT ID id, PARENT_ID parent, R_LANG language, FK_CONTENT content, LABEL label, ORDRE ordre FROM MENU WHERE PARENT_ID IS NULL ORDER BY R_LANG, ORDRE ASC');

			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Menu');
			$query->execute();

			$listMenu = $query->fetchAll();

			$query->closeCursor();

			return $listMenu;
		}

		public function GetMenuById($id) {
			$query = $this->db->prepare('SELECT ID id, PARENT_ID parent, R_LANG language, LABEL label FROM MENU WHERE ID = :id');

		  	$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Menu');
			$query->execute();

			$menu = $query->fetch();

			$query->closeCursor();

			return $menu;
		}

		public function GetMenuByLanguage($language) {
			$query = $this->db->prepare('SELECT ID id, PARENT_ID parent, R_LANG language, FK_CONTENT content, LABEL label, ORDRE ordre FROM MENU WHERE R_LANG = :language ORDER BY ORDRE ASC');

		  	$query->bindParam(':language', $language, PDO::PARAM_STR);
			$query->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Menu');
			$query->execute();

			$listMenu = $query->fetchAll();

			$query->closeCursor();

			foreach ($listMenu as $menu) {
				$manager = new MenuManager();
				$parent = $manager->GetMenuById($menu->getParent());
				$menu->setParent($parent);

				$manager = new Content_LangManager();
				$content = $manager->GetContentById($menu->getContent());
				$menu->setContent($content);
			}

			return $listMenu;
		}

		public function Save(Menu $menu) {
			if ($menu->isValid()) {
				if ($menu->isNew())
					$this->Add($menu);
				else
					$this->Update($menu);
			}
			else
				throw new Exception($menu->getErrors());
		}
	}
?>