<?php
	/* 
		Contrôleur pour le contenu à afficher
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 08/10/2023
	    @Dernière modification: 21/10/2023
  	*/

	class ContentController 
	{
		private $contentManager, $menuManager, $referenceDetailManager;

		public function __construct()
		{
			$this->menuManager = new MenuManager();
			$this->contentManager = new Content_LangManager();
			$this->referenceDetailManager = new Reference_DetailManager();
		}

		public function Index($language, $slug) {
			$currentLanguage = $this->referenceDetailManager->getLangue(strtoupper($language));
			$otherLanguages = $this->referenceDetailManager->getTranslations(strtoupper($language));
			$mainMenu = $this->menuManager->GetMainMenuByLang($language);
			$subMenu = $this->menuManager->GetSubMenuByLang($language);
			$content = $this->contentManager->GetContentBySlug($slug);

		    require_once 'src/views/front/displayContent.php';
		}
	}
?>