<?php
	/* 
		Contrôleur pour la page d'accueil et les autres pages simples
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 14/08/2023
	    @Dernière modification: 02/09/2025
  	*/

	class HomeController 
	{
		private $contentManager, $contentSEOManager, $menuManager, $referenceDetailManager, $settingManager;

		public function __construct()
		{
			$this->menuManager = new MenuManager();
			$this->contentManager = new Content_LangManager();
			$this->contentSEOManager = new Content_Lang_SEOManager();
			$this->referenceDetailManager = new Reference_DetailManager();
			$this->settingManager = new SettingManager();
		}

		public function Index($language = 'FR') {
			$countLanguages = $this->referenceDetailManager->CountLanguages();
			$currentLanguage = $this->referenceDetailManager->GetLangue(strtoupper($language));
			$otherLanguages = $this->referenceDetailManager->GetTranslations(strtoupper($language));
			$contentsList = $this->contentManager->GetContentByLanguage(strtoupper($language));
			$mainMenu = $this->menuManager->GetMainMenuByLang(strtoupper($language));
			$subMenu = $this->menuManager->GetSubMenuByLang(strtoupper($language));
			$cookies = (bool)$this->settingManager->CheckCookies();
			$facebook = $this->settingManager->GetSocial("SOC_FB");
			$twitter = $this->settingManager->GetSocial("SOC_TWT");
			$instagram = $this->settingManager->GetSocial("SOC_INST");
			$seo = $this->contentSEOManager->GetSEOByFkContentLang(1);

			$index = ($seo->getRobotsIndex()) ? "index" : "noindex";
			$follow = ($seo->getRobotsFollow()) ? "follow" : "nofollow";


			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
			$domain = $_SERVER['HTTP_HOST'];
			$fullBaseUrl = $protocol . $domain . BASE_URL;

		    require_once 'src/views/front/home.php';
		}
	}
?>