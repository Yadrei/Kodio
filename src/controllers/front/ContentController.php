<?php
	/* 
		Contrôleur pour le contenu à afficher
	    @Author Yves P.
	    @Version 1.2
	    @Date création: 08/10/2023
	    @Dernière modification: 18/06/2025
  	*/

	class ContentController 
	{
		private $contentManager, $commentManager, $menuManager, $referenceDetailManager, $settingManager;

		public function __construct()
		{
			$this->menuManager = new MenuManager();
			$this->contentManager = new Content_LangManager();
			$this->commentManager = new CommentManager();
			$this->referenceDetailManager = new Reference_DetailManager();
			$this->settingManager = new SettingManager();
		}

		public function AddReaction() 
		{
			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				$response = array('status' => false, 'message' => BAD_REQUEST_METHOD);

			if (!isset($_POST['nickname']) || !isset($_POST['text']) || !isset($_POST['email']))
				$response = array('status' => false, 'message' => FIELD_NOT_FOUND);

			if (empty($_POST['nickname']))
				$response = array('status' => false, 'message' => NICKNAME_EMPTY);

			if (empty($_POST['email']))
				$response = array('status' => false, 'message' => EMAIL_EMPTY);

			if (empty($_POST['text']))
			$response = array('status' => false, 'message' => TEXT_EMPTY);

			$contentId = $_POST['contentId'];
			$nickname = Sanitize($_POST['nickname']);
			$email = Sanitize($_POST['email']);
			$text = Sanitize($_POST['text']);

			$token = bin2hex(random_bytes(32));

			if (empty($response)) {
				$comment = new Comment (
				[
					'nickname' => $nickname,
					'fkContent' => $contentId,
					'text' => $text,
					'status' => 'PENDING',
					'token' => $token
				]);

				try {
					$content = '
					<p>Vous recevez ce message car vous avez poster un commentaire. Veuillez cliquer sur le lien ci-dessous afin de le valider</p>
					<a href="'.BASE_URL.'reaction/validate/'.$token.'">Je valide mon commentaire</a>';

					$mail = new Mail('Validation de commentaire', $content, $email, '', '');

					if ($mail->Send()) {
						$this->commentManager->Save($comment);

						// Pour rediriger vers la page où on était
						$content = $this->contentManager->GetContentById($contentId);

						header("Location: ".BASE_URL.strtolower($content->getLanguage()).'/'.$content->getSlug());
						exit;
					}
					else {
						throw new Exception("Erreur");
					}
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
			$cookies = (bool)$this->settingManager->CheckCookies();
			$com = (bool)$this->settingManager->CheckComments();
			$facebook = $this->settingManager->GetSocial("SOC_FB");
			$twitter = $this->settingManager->GetSocial("SOC_TWT");
			$instagram = $this->settingManager->GetSocial("SOC_INST");

		    require_once 'src/views/front/displayContent.php';
		}

		public function ValidateReaction($token) {
			$countLanguages = $this->referenceDetailManager->CountLanguages();
			$currentLanguage = $this->referenceDetailManager->getLangue(strtoupper('fr'));
			$otherLanguages = $this->referenceDetailManager->getTranslations(strtoupper('fr'));
			$mainMenu = $this->menuManager->GetMainMenuByLang('fr');
			$subMenu = $this->menuManager->GetSubMenuByLang('fr');
			$cookies = (bool)$this->settingManager->CheckCookies();
			$facebook = $this->settingManager->GetSocial("SOC_FB");
			$twitter = $this->settingManager->GetSocial("SOC_TWT");
			$instagram = $this->settingManager->GetSocial("SOC_INST");
			$rowCount = $this->commentManager->ValidateComment($token);

			$message = ($rowCount === 1) ? "Commentaire validé" : "Lien invalide ou déjà utilisé";

			require_once 'src/views/front/feedback.php';
		}
	}
?>