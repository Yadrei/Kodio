<?php
	/* 
		Contrôleur pour la page de contenu de l'admin
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 16/08/2023
	    @Dernière modification: 01/01/2024
  	*/

	class ContentsController 
	{
		private $v_contentMainManager, $v_contentLangManager, $contentLangManager, $contentHistoManager, $referenceDetailManager, $permissionManager, $tagManager, $contentTagManager, $menuManager;

		public function __construct()
		{
			$this->v_contentMainManager = new V_Content_MainManager();
			$this->v_contentLangManager = new V_Content_LangManager();
	        $this->contentLangManager = new Content_LangManager();
	        $this->contentHistoManager = new Content_HManager();
	        $this->referenceDetailManager = new Reference_DetailManager();
	        $this->permissionManager = new PermissionManager();
	        $this->tagManager = new TagManager();
            $this->menuManager = new MenuManager();
            $this->contentTagManager = new J_Content_TagManager();
		}

		public function Index($page = 1) 
		{
			$currentItems = ($page - 1 ) * $GLOBALS['itemsPerPages'];

			$listContent = $this->v_contentMainManager->getAllContent($currentItems, $GLOBALS['itemsPerPages']);
			$count = $this->v_contentMainManager->count();

			foreach($listContent as $content) {
				$contentId = $content->getContentId();
				$translations[$contentId] = $this->v_contentLangManager->getTranslations($contentId);
				$historiques[$contentId] = $this->contentHistoManager->getHistorique($contentId);
			}

			$pagination = new Pagination(BASE_URL."private/content/%s", $page, $count, $GLOBALS['options']);

			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']); // Pour récupérer les permissions de l'utilisateur connecté

			require_once 'src/views/back/content.php';
		}

		public function ChangeStatus() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['id']))
				$response = array('status' => false, 'message' => ID_NOT_FOUND);

			$id = Sanitize($_POST['id']);

			if (!is_numeric($id))
				$response = array('status' => false, 'message' => ID_NOT_NUMERIC);

			if (empty($response)) {
				try {
					$this->contentLangManager->ChangeStatus($id);

					$response = array('status' => true, 'message' => STATUS_UPDATED);

				}
				catch (PDOException $e) {
					$response = array('status' => false, 'message' => $e->getMessage());
				}
			} 

			header('Content-Type: application/json');
			echo json_encode($response);
		}

		public function Delete($id) 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowDelete())
				throw new Exception(NOT_ALLOWED);

			$this->contentLangManager->Delete($id);
			
			header("Location: ".BASE_URL."private/content");
			exit;
		}

		public function ManageAdd() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowAdd())
				throw new Exception(NOT_ALLOWED);

			$langues = $this->referenceDetailManager->getDetails("R_LANG");
			$categories = $this->referenceDetailManager->getDetails("R_CAT");
			$tags = $this->tagManager->getAllTags();

			$action = 'add';

			require_once 'src/views/back/manageContent.php';
		}

		public function ManageUpdate($contentId) 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			$langues = $this->referenceDetailManager->getDetails("R_LANG");
			$categories = $this->referenceDetailManager->getDetails("R_CAT");
			$contents = $this->contentLangManager->GetContentByMainContentId($contentId);
            $tags = $this->tagManager->getAllTags();
            $relatedTags = $this->contentTagManager->GetRelatedTags($contentId);

			$action = 'update';

			require_once 'src/views/back/manageContent.php';
		}

        public function Preview($language, $slug)
        {
            if (!isset($_SESSION['isLog']))
                throw new Exception('Not logged !');

            $permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

            if (!$permissionsLogged->getAllowAccess())
                throw new Exception(NOT_ALLOWED);

            $currentLanguage = $this->referenceDetailManager->getLangue(strtoupper($language));
            $otherLanguages = $this->referenceDetailManager->getTranslations(strtoupper($language));
            $mainMenu = $this->menuManager->GetMainMenuByLang($language);
            $subMenu = $this->menuManager->GetSubMenuByLang($language);
            $content = $this->contentLangManager->GetPreview($slug);

            require_once 'src/views/back/preview.php';
        }

		public function Recuperation($id) 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			if (!$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			$user = $_SESSION['id'];

			$this->contentHistoManager->Recuperation($id, $user);
			
			header("Location: ".BASE_URL."private/content");
			exit;
		}

		public function Save() 
		{
			$permissionsLogged = $this->permissionManager->getPermissions($_SESSION['id']);

			// Il faut obligatoirement les 2 comme on ne sait pas d'avance si le contenu sera mis à jour ou ajouter
			if (!$permissionsLogged->getAllowAdd() || !$permissionsLogged->getAllowUpdate())
				throw new Exception(NOT_ALLOWED);

			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				throw new Exception(BAD_REQUEST_METHOD);

			/*
				Seul le français est obligatoire, comme c'est la langue de base. On commence donc par checker tous ces éléments là en premier
			*/

			if (!isset($_POST['author']) || !isset($_POST['title']['FR']) || !isset($_POST['cat']['FR']) || !isset($_POST['content']['FR']) || !isset($_POST['metaTitle']['FR']) || !isset($_POST['metaDescription']['FR']) || !isset($_POST['language']['FR']))
				throw new Exception(ERROR_MAIN_LANGUAGE);

			$author = Sanitize($_POST['author']);

			/*
				On parcourt tous les champs passé mais on ne récupère que ceux qui nous intéressent :

					- C'est à dire ceux où le titre n'est pas vide, car le titre est obligatoire dans tous les cas !
			*/

			$values = []; // Stocker toutes les valeurs qu'on va récupérer

			foreach ($_POST as $fields => $base) {
				if (is_array($base)) {
					foreach ($base as $language => $value) {
				        if (!empty($_POST['title'][$language])) {
				        	$values[$fields][$language] = Sanitize($value);

				        	if ($fields == 'title')
				        		$values['slug'][$language] = Slugify($value); 
				        }
                    }
				}
            }

			foreach ($values['cat'] as $value)
				if ($value == "DEFAULT")
						throw new Exception(CATEGORY_DEFAULT.': '.$language);

			// On vérifie que les données sont correctes
			$verifChamps = [
				"title" => ["min" => 4, "max" => 100],
				"metaTitle" => ["min" => 10, "max" => 50],
				"metaDescription" => ["min" => 10, "max" => 200]
			];

			foreach ($verifChamps as $champs => $lengths) {
				foreach ($values[$champs] as $language => $value) {
					if (empty($value))
						throw new Exception(EMPTY_FIELD_TRANSLATE.': '.$language);

					$length = strlen($value);

					if ($length < $lengths['min'] || $length > $lengths['max'])
						throw new Exception(WRONG_LENGTH_TRANSLATE.': '.$language.' (entre '.$lengths['min'].' et '.$lengths['max'].' caractères)');
				}
			}

			// Si on est en mode update, le contentId existe donc on le récupère
			$contentId = (isset($_POST['contentId'])) ? Sanitize($_POST['contentId']) : null;

            // On récupère l'image d'entête
            if (!empty($_FILES['images']['tmp_name'][0]))
                $image = ProcessImages("heading");
            else
                $image = null;

            // Tableau qui contiendra les tags
            $tags = [];

            // Récupération des tags
            if (isset($_POST['tag']) && is_array($_POST['tag'])) {
                foreach ($_POST['tag'] as $tagLabel => $tagId) {
                    // Vérifier si la case associée est cochée
                    $isChecked = isset($_POST['checkHidden'][$tagLabel]) && $_POST['checkHidden'][$tagLabel] == '1';

                    // Ajouter le tag seulement si la case est cochée
                    if ($isChecked) {
                        $tags[] = new J_Content_Tag([
                            'fkTag' => $tagId,
                        ]);
                    }
                }
            } 
            /*
            else {
                throw new Exception(NO_TAGS);
            }
            */

			// Tableau qui contiendra les objets à sauvegarder en DB
			$contents = [];

			foreach ($values['language'] as $lang) {
				$object = new Content_Lang ([
					'id' => (isset($values['id'][$lang])) ? $values['id'][$lang] : null,
					'contentId' => $contentId,
					'language' => $values['language'][$lang],
					'category' => $values['cat'][$lang],
					'author' => $author,
					'title' => $values['title'][$lang],
					'content' => $values['content'][$lang],
                    'image' => (!is_null($image)) ? $image[0] : $image,
                    'datePublication' => (isset($_POST['datePublication'])) ? new DateTime($_POST['datePublication']) : null,
					'metaTitle' => $values['metaTitle'][$lang],
					'metaDescription' => $values['metaDescription'][$lang],
					'slug' => $values['slug'][$lang],
					'published' => (isset($values['publication'][$lang])) ? 1 : 0
				]);

				$contents[] = $object;
			}

			$mainId = $this->contentLangManager->Save($contents);

            // Sauvegarde des tags liés au contenu
            if (!is_null($tags)) {
                foreach ($tags as $tag) {
                    $tag->setFkContent($mainId);
                }

                $this->contentTagManager->Save($tags);
            }
			
            if (isset($values['id']['FR'])) {
                header("Location: ".BASE_URL."private/content/manage/update/".$contentId);
                exit;
            }
            else {
                header("Location: ".BASE_URL."private/content");
                exit;
            }
		}
	}
?>