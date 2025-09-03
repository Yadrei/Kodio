<?php
    /* 
		Class générale pour des validations diverses
	    @Author Yves P.
	    @Version 1.0
	    @Date création: 03/09/2025
	    @Dernière modification: 03/09/2025
  	*/

    class Validator {
        private static $errors = [];
        
        public static function sanitize($input, $type = 'text') {
            if (is_array($input)) {
                return array_map(function($item) use ($type) {
                    return self::sanitize($item, $type);
                }, $input);
            }
            
            // Nettoyage de base
            $input = trim($input);
            
            // Traitement selon le type
            switch($type) {
                case 'html':
                    $allowed_tags = '<p><br><strong><em><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><blockquote><span><div>';
                    $input = strip_tags($input, $allowed_tags);

                    break;
                    
                case 'int':
                    $input = filter_var($input, FILTER_SANITIZE_NUMBER_INT);

                    break;
                    
                case 'email':
                    $input = filter_var($input, FILTER_SANITIZE_EMAIL);

                    break;
                    
                case 'url':
                    $input = filter_var($input, FILTER_SANITIZE_URL);

                    break;
                    
                case 'filename':
                    // Pour les noms de fichiers uploadés
                    $input = preg_replace('/[^a-zA-Z0-9._-]/', '', $input);

                    break;
                    
                default:
                    // Texte simple (pas de HTML)
                    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

                    break;
            }
            
            return $input;
        }
        
        public static function email($email, $checkDns = false) {
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                return false;
            
            // Vérification DNS optionnelle (attention sur serveur mutualisé)
            if ($checkDns) {
                $domain = substr(strrchr($email, "@"), 1);

                if (!checkdnsrr($domain, 'MX'))
                    return false;
            }
            
            return $email;
        }
        
        public static function text($text, $minLength = 1, $maxLength = 255) {
            $text = self::sanitize($text);
            $length = mb_strlen($text, 'UTF-8');
            
            if ($length < $minLength || $length > $maxLength) {
                return false;
            }
            
            return $text;
        }
        
        public static function integer($value, $min = null, $max = null) {
            if (!filter_var($value, FILTER_VALIDATE_INT))
                return false;
            
            $value = (int) $value;
            
            if ($min !== null && $value < $min)
                return false;
            
            if ($max !== null && $value > $max)
                return false;
            
            return $value;
        }
        
        public static function url($url) {
            $url = filter_var($url, FILTER_SANITIZE_URL);
            
            if (!filter_var($url, FILTER_VALIDATE_URL)) 
                return false;
            
            return $url;
        }
        
        public static function slug($text) {
            // Caractères accentués français
            $unwanted = ['à','á','â','ã','ä','å','æ','ç','è','é','ê','ë',
                        'ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø',
                        'ù','ú','û','ü','ý','þ','ÿ','À','Á','Â','Ã','Ä',
                        'Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð',
                        'Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý'];
                        
            $wanted = ['a','a','a','a','a','a','ae','c','e','e','e','e',
                    'i','i','i','i','d','n','o','o','o','o','o','o',
                    'u','u','u','u','y','th','y','A','A','A','A','A',
                    'A','AE','C','E','E','E','E','I','I','I','I','D',
                    'N','O','O','O','O','O','O','U','U','U','U','Y'];
            
            $text = str_replace($unwanted, $wanted, $text);
            $text = preg_replace('/[^a-zA-Z0-9-]/', '-', $text);
            $text = preg_replace('/-+/', '-', $text);
            $text = strtolower(trim($text, '-'));
            
            return $text;
        }

        public static function date($date, $format = 'Y-m-d') {
            $d = DateTime::createFromFormat($format, $date);

            return $d && $d->format($format) === $date ? $date : false;
        }
 
        public static function file($file, $allowedTypes = [], $maxSize = 5242880) {
            if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) 
                return false;
            
            // Vérification de la taille
            if ($file['size'] > $maxSize)
                return false;
            
            // Vérification du type MIME
            if (!empty($allowedTypes)) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
                
                if (!in_array($mimeType, $allowedTypes))
                    return false;
            }
            
            return true;
        }

        public static function password($password, $minLength = 8) {
            if (strlen($password) < $minLength)
                return false;
            
            // Au moins une majuscule, une minuscule, un chiffre
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password))
                return false;
            
            return true;
        }

        public static function RandomPassword($length = 15) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $password = '';

            for ($i = 1; $i <= $length; $i++)
                $password .= $characters[rand(0, strlen($characters) - 1)];

            return $password;
        }
    }

        // Utilisation:
    // $email = Validator::email($_POST['email']);
    // if ($email === false) {
    //     throw new Exception("Email invalide");
    // }
?>