<?php
	/* 
		Contrôleur pour la page d'accueil et les autres pages simples
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 14/08/2023
	    @Dernière modification: 19/03/2025
  	*/

	class HomeController 
	{
		private $contentManager, $menuManager, $referenceDetailManager, $settingManager;

		public function __construct()
		{
			$this->menuManager = new MenuManager();
			$this->contentManager = new Content_LangManager();
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

		    require_once 'src/views/front/home.php';
		}
	}
?>