<?php
    /* 
        Contrôleur pour les exceptions
        @Author Yves P.
        @Version 1.0
        @Date création: 27/09/2023
        @Dernière modification: 27/09/2023
    */

    class ExceptionHandler 
    {
        private static $referenceDetailManager;

       public static function init() {
            // Initialisez $referenceDetailManager une seule fois.
            if (self::$referenceDetailManager === null) {
                self::$referenceDetailManager = new Reference_DetailManager();
            }
        }

        public static function HandleException($e) 
        {
            self::init();
            $currentLanguage = self::$referenceDetailManager->getLangue("FR");
            $otherLanguages = self::$referenceDetailManager->getTranslations("FR");

            self::ShowErrorPage($e);
        }

        private static function ShowErrorPage($e) 
        {
            require_once 'src/views/error.php';
        }
    }
?>