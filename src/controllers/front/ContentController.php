<?php
	/* 
		Contrôleur pour le contenu à afficher
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 08/10/2023
	    @Dernière modification: 19/03/2025
  	*/

	class ContentController 
	{
		private $contentManager, $commentManager, $menuManager, $referenceDetailManager;

		public function __construct()
		{
			$this->menuManager = new MenuManager();
			$this->contentManager = new Content_LangManager();
			$this->commentManager = new CommentManager();
			$this->referenceDetailManager = new Reference_DetailManager();
		}

		public function AddReaction() 
		{
			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['nickname']) || !isset($_POST['text']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			if (empty($_POST['nickname']))
				$response = array('status' => false, 'message' => NICKNAME_EMPTY);

			if (empty($_POST['text']))
			$response = array('status' => false, 'message' => TEXT_EMPTY);

			$contentId = $_POST['contentId'];
			$nickname = Sanitize($_POST['nickname']);
			$text = Sanitize($_POST['text']);

			if (empty($response)) {
				$comment = new Comment (
				[
					'nickname' => $nickname,
					'fkContent' => $contentId,
					'text' => $text
				]);

				try {
					
					$this->commentManager->Save($comment);

				    header("Location: ".BASE_URL);
                	exit;
				}
				catch (PDOException $e) {
					$response = array('success' => false, 'message' => $e->getMessage());
				}
			}

		}

		public function Index($language, $slug) 
		{
			$countLanguages = $this->referenceDetailManager->CountLanguages();
			$currentLanguage = $this->referenceDetailManager->getLangue(strtoupper($language));
			$otherLanguages = $this->referenceDetailManager->getTranslations(strtoupper($language));
			$mainMenu = $this->menuManager->GetMainMenuByLang($language);
			$subMenu = $this->menuManager->GetSubMenuByLang($language);
			$content = $this->contentManager->GetContentBySlug($slug);
			$comments = $this->commentManager->GetCommentsFromContent($content->getId());

		    require_once 'src/views/front/displayContent.php';
		}
	}
?>