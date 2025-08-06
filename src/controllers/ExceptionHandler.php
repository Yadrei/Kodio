<?php
    /* 
        Contrôleur pour les exceptions
        @Author Yves P.
        @Version 1.0
        @Date création: 27/09/2023
        @Dernière modification: 04/06/2025
    */

    class ExceptionHandler {
        private static $referenceDetailManager;

        private static $menuManager, $settingManager;

        public static function init() {
            // Initialisez $referenceDetailManager une seule fois.
            if (self::$referenceDetailManager === null) {
                self::$referenceDetailManager = new Reference_DetailManager();
                self::$menuManager = new MenuManager();
			    self::$settingManager = new SettingManager();
            }
        }

        public static function HandleException($e) {
            self::init();

            self::ShowErrorPage($e);
        }

        private static function ShowErrorPage($e) {
            $data = [
                'countLanguages' => self::$referenceDetailManager->CountLanguages(),
                'currentLanguage' => self::$referenceDetailManager->getLangue("FR"),
                'otherLanguages' => self::$referenceDetailManager->getTranslations("FR"),
                'mainMenu' => self::$menuManager->GetMainMenuByLang("fr"),
                'subMenu' => self::$menuManager->GetSubMenuByLang("fr"),
                'facebook' => self::$settingManager->GetSocial("SOC_FB"),
                'twitter' => self::$settingManager->GetSocial("SOC_TWT"),
                'instagram' => self::$settingManager->GetSocial("SOC_INST"),
                'cookies' => (bool)self::$settingManager->CheckCookies(),
            ];

            extract($data);
            require_once 'src/views/error.php';
        }
    }
?>