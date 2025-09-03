<?php
    /* 
        Service de protection CSRF
        @Author Yves P.
        @Version 1.0
        @Date création: 02/09/2025
        @Dernière modification: 02/09/2025
    */

    class CSRF 
    {
        private static $tokenName = 'csrf_token';
        private static $tokenLifetime = 3600; // 1 heure
        
        /**
         * Génère ou récupère le token CSRF
         */
        public static function Generate() 
        {
            if (!isset($_SESSION[self::$tokenName]) || 
                !isset($_SESSION['csrf_time']) || 
                (time() - $_SESSION['csrf_time'] > self::$tokenLifetime)) {
                
                $_SESSION[self::$tokenName] = bin2hex(random_bytes(32));
                $_SESSION['csrf_time'] = time();
            }
            
            return $_SESSION[self::$tokenName];
        }
        
        /**
         * Retourne le champ HTML hidden avec le token
         */
        public static function Field() 
        {
            return '<input type="hidden" name="csrf_token" value="' . self::Generate() . '">';
        }
        
        /**
         * Vérifie la validité du token
         */
        public static function Verify($token = null) 
        {
            if ($token === null)
                $token = $_POST['csrf_token'] ?? '';
            
            if (empty($_SESSION[self::$tokenName]) || empty($token))
                return false;
            
            return hash_equals($_SESSION[self::$tokenName], $token);
        }
        
        /**
         * Vérifie et lance une exception si invalide
         */
        public static function Check() 
        {
            if (!self::Verify())
                throw new Exception("Token de sécurité invalide. Veuillez rafraîchir la page.");
            
            return true;
        }
        
        /**
         * Régénère le token (après une action sensible)
         */
        public static function Regenerate() 
        {
            unset($_SESSION[self::$tokenName]);
            unset($_SESSION['csrf_time']);
            return self::Generate();
        }
    }
?>