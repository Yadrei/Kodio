<?php
	/* 
		Contrôleur pour la page de contenu de l'admin
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 16/08/2023
	    @Dernière modification: 17/09/2025
  	*/

	class ContentsController 
	{
		private $v_contentMainManager, 
				$v_contentLangManager, 
				$contentLangManager, 
				$contentHistoManager, 
				$contentSEOManager,
				$referenceDetailManager, 
				$permissionManager, 
				$tagManager, 
				$contentTagManager, 
				$commentManager, 
				$menuManager, 
				$settingManager;

		public function __construct()
		{
			$this->v_contentMainManager = new V_Content_MainManager();
			$this->v_contentLangManager = new V_Content_LangManager();
	        $this->contentLangManager = new Content_LangManager();
	        $this->contentHistoManager = new Content_HManager();
			$this->contentSEOManager = new Content_Lang_SEOManager();
	        $this->referenceDetailManager = new Reference_DetailManager();
	        $this->permissionManager = new PermissionManager();
	        $this->tagManager = new TagManager();
			$this->commentManager = new CommentManager();
            $this->menuManager = new MenuManager();
            $this->contentTagManager = new J_Content_TagManager();
			$this->settingManager = new SettingManager();
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
				$comments[$contentId] = $this->commentManager->GetCommentsFromContent($content->getId());
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

			$id = Validator::integer($_POST['id']);

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

			foreach ($contents as $content) {
				$id = $content->getId();

				$seo[$id] = $this->contentSEOManager->GetSEOByFkContentLang($id);
			}

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

			$countLanguages = $this->referenceDetailManager->CountLanguages();
            $currentLanguage = $this->referenceDetailManager->getLangue(strtoupper($language));
            $otherLanguages = $this->referenceDetailManager->getTranslations(strtoupper($language));
            $mainMenu = $this->menuManager->GetMainMenuByLang($language);
            $subMenu = $this->menuManager->GetSubMenuByLang($language);
            $content = $this->contentLangManager->GetPreview($slug);
			$cookies = (bool)$this->settingManager->CheckCookies();
			$facebook = $this->settingManager->GetSocial("SOC_FB");
			$twitter = $this->settingManager->GetSocial("SOC_TWT");
			$instagram = $this->settingManager->GetSocial("SOC_INST");

			$seo = $this->contentSEOManager->GetSEOByFkContentLang($content->getId());

			$index = ($seo->getRobotsIndex()) ? "index" : "noindex";
			$follow = ($seo->getRobotsFollow()) ? "follow" : "nofollow";

			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
			$domain = $_SERVER['HTTP_HOST'];
			$fullBaseUrl = $protocol.$domain.BASE_URL;

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

			CSRF::Check();

			/*
				Seul le français est obligatoire, comme c'est la langue de base. On commence donc par checker tous ces éléments là en premier
			*/

			if (!isset($_POST['author']) || !isset($_POST['title']['FR']) || !isset($_POST['category']) || !isset($_POST['content']['FR']) || !isset($_POST['language']['FR']) 
				|| !isset($_POST['metaTitle']['FR']) || !isset($_POST['metaDescription']['FR']) || !isset($_POST['ogTitle']['FR']) || !isset($_POST['ogDescription']['FR']) || !isset($_POST['schemaType']['FR']) || !isset($_POST['schemaDescription']['FR']))
				throw new Exception(ERROR_MAIN_LANGUAGE);

			$author = Validator::sanitize($_POST['author']);

			/*
				On parcourt tous les champs passé mais on ne récupère que ceux qui nous intéressent :

					- C'est à dire ceux où le titre n'est pas vide, car le titre est obligatoire dans tous les cas !
			*/

			$values = []; // Stocker toutes les valeurs qu'on va récupérer

			foreach ($_POST as $fields => $base) {
				if (is_array($base)) {
					foreach ($base as $language => $value) {
				        if (!empty($_POST['title'][$language])) {
				        	$values[$fields][$language] = Validator::sanitize($value);

				        	if ($fields == 'title')
				        		$values['slug'][$language] = Validator::slug($value); 
				        }

						if (!empty($_POST['content'][$language])) {
				        	$values[$fields][$language] = Validator::sanitize($value, 'html');
				        }
                    }
				}
            }

			$category = Validator::sanitize($_POST['category']);
			
			if ($category == "DEFAULT")
				throw new Exception(CATEGORY_DEFAULT.': '.$language);

			// On vérifie que les données sont correctes
			$verifChamps = [
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
			$contentId = (isset($_POST['contentId'])) ? Validator::integer($_POST['contentId']) : null;

            // On récupère l'image d'entête
            if (!empty($_FILES['image'])){
				try {
					$image = File::upload($_FILES['image'], 'heading');
				} catch (Exception $e) {
					throw new Exception('Erreur upload image');
				}
				//$image =  ProcessImages("heading");
			}
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
					'category' => $category,
					'author' => $author,
					'title' => $values['title'][$lang],
					'content' => $values['content'][$lang],
                    'image' => $image['name'],
                    'datePublication' => (isset($_POST['datePublication'])) ? new DateTime($_POST['datePublication']) : null,
					'metaTitle' => $values['metaTitle'][$lang],
					'metaDescription' => $values['metaDescription'][$lang],
					'slug' => $values['slug'][$lang],
					'published' => (isset($values['publication'][$lang])) ? 1 : 0
				]);

				$contents[] = $object;
			}

			// Sauvegarder les contenus et récupérer les IDs
			$result = $this->contentLangManager->Save($contents);
			
			// Le résultat contient maintenant mainId et contentLangIds
			$mainId = $result['mainId'];
			$contentLangIds = $result['contentLangIds'];

            // Sauvegarde des tags liés au contenu
            if (!is_null($tags)) {
                foreach ($tags as $tag) {
                    $tag->setFkContent($mainId);
                }

                $this->contentTagManager->Save($tags);
            }

			// NOUVEAU : Sauvegarde des données SEO pour chaque langue
			if (!empty($contentLangIds)) {
				$seoManager = new Content_Lang_SEOManager();
				$seoObjects = [];

				foreach ($contentLangIds as $lang => $contentLangId) {						
					$seoObject = new Content_Lang_SEO([
						'id' => (isset($_POST['seoId'][$lang])) ? $_POST['seoId'][$lang] : null,
						'fkContentLang' => $contentLangId,
						'metaTitle' => Validator::sanitize($_POST['metaTitle'][$lang]),
						'metaDescription' => Validator::sanitize($_POST['metaDescription'][$lang]),
						'url' => null,
						'robotsIndex' => (isset($_POST['robotsIndex'][$lang])) ? 1 : 0,
						'robotsFollow' => (isset($_POST['robotsFollow'][$lang])) ? 1 : 0,
						'title' => Validator::sanitize($_POST['ogTitle'][$lang]),
						'description' => Validator::sanitize($_POST['ogDescription'][$lang]),
						'image' => $image['name'],
						'schemaType' => Validator::sanitize($_POST['schemaType'][$lang]),
						'schemaDescription' => Validator::sanitize($_POST['schemaDescription'][$lang])
					]);

					$seoObjects[] = $seoObject;
				}

				// Sauvegarder tous les objets SEO
				if (!empty($seoObjects)) {
					$seoManager->Save($seoObjects);
				}
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