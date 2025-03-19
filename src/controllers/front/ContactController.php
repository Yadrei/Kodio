<?php
	/* 
		Contrôleur pour la page de contact (affichage + envoi)
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 14/08/2023
	    @Dernière modification: 19/03/2025
  	*/

	class ContactController 
	{
		private $contentManager, $menuManager, $referenceDetailManager, $settingManager;

		public function __construct()
		{
			$this->menuManager = new MenuManager();
			$this->contentManager = new Content_LangManager();
			$this->referenceDetailManager = new Reference_DetailManager();
			$this->settingManager = new SettingManager();
		}
		
		public function Index($language = 'FR') 
		{
			$countLanguages = $this->referenceDetailManager->CountLanguages();
			$currentLanguage = $this->referenceDetailManager->getLangue(strtoupper($language));
			$otherLanguages = $this->referenceDetailManager->getTranslations(strtoupper($language));
			$contentsList = $this->contentManager->GetContentByLanguage(strtoupper($language));
			$mainMenu = $this->menuManager->GetMainMenuByLang($language);
			$subMenu = $this->menuManager->GetSubMenuByLang($language);
			$cookies = (bool)$this->settingManager->CheckCookies();
			$facebook = $this->settingManager->GetSocial("SOC_FB");
			$twitter = $this->settingManager->GetSocial("SOC_TWT");
			$instagram = $this->settingManager->GetSocial("SOC_INST");

			switch (strtoupper($language))
			{
				case 'FR':
					$name = "Nom & Prénom";
					$email = "Adresse Email";
					$subject = "Sujet du message";
					$subjectList = "Sélectionnez dans la liste";
					$message = "Votre message";
					$send = "Envoyer";
					break;
				default:
					$name="Firstname & lastname";
					$email = "e-mail address";
					$subject = "Subject";
					$subjectList = "Select from list";
					$message = "Your message";
					$send = "Send";
					break;
			}

		    require_once 'src/views/front/contact.php';
		}

		public function Send() 
		{
			if ($_SERVER['REQUEST_METHOD'] !== 'POST')
				throw new Exception('Erreur !');

			if (!empty($_POST['mark'])) {
				header("Location: ".BASE_URL);
				exit;
			}

			if (!isset($_POST['submit']) || !isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['subject']) || !isset($_POST['text'])) {
				header("Location: ".BASE_URL);
				exit;
			}

			$name = htmlspecialchars($_POST['name']);
			$email = htmlspecialchars($_POST['email']);
			$subject = htmlspecialchars($_POST['subject']);
			$text = htmlspecialchars($_POST['text']);

			// Vérification qu'aucun champ n'est vide
			if (empty($name) || empty($email) || empty($subject) || empty($text))
				throw new Exception("Les éléments du formulaire de contact ne peuvent pas être vides");

			$name = trim($name);
			$name = stripslashes($name);
			$email = trim($email);
			$email = stripslashes($email);
			$subject = trim($subject);
			$subject = stripslashes($subject);
			$text = trim($text);
			$text = stripslashes($text);

			if (strlen($name) < 5 || strlen($name) > 50)
				throw new Exception("Nom et prénom invalide (entre 5 et 50 caractères autorisés)");

			if (strlen($email) < 10 || strlen($email) > 50)
				throw new Exception("Adresse email invalide (entre 10 et 50 caractères autorisés)");

			if (strlen($text) < 100 || strlen($text) > 1000)
				throw new Exception("Le texte n'est pas valide (entre 100 et 1000 caractères autorisés)");

			// On prend en compte les sauts de ligne pour la description
		    $text = nl2br($text);

			$subject = 'Contact à propos de '.$subject;

			$content = '
			<h4>Nom et prénom du client</h4>
			<p>'.$name.'</p>
			<h4>Adresse email</h4>
			<p>'.$email.'</p>
			<h4>Message</h4>
			<p>'.$text.'</p>';

			$mail = new Mail('info@shoku.be', $subject, $content);

			if (!$mail -> Send()) 
				throw new Exception("Erreur !<br>Un problème est survenu lors de l'envoi de votre message'. Merci de contacter un administrateur");
			
			header("Location: ".BASE_URL."contact");
			exit;
		}
	}
?>