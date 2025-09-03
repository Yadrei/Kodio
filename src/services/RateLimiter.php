<?php
// Fichier: src/services/RateLimiter.php

    class RateLimiter {
        private static $storageDir = 'storage/rate_limit/';
        
        /**
         * Vérifie et enregistre une tentative
         * 
         * @param string $action Type d'action (login, api, etc.)
         * @param string $identifier IP ou identifiant unique
         * @param int $maxAttempts Nombre max de tentatives
         * @param int $timeWindow Fenêtre de temps en secondes
         * @return bool True si autorisé, False si limite atteinte
         */
        public static function check($action, $identifier = null, $maxAttempts = 5, $timeWindow = 300) {
            // Créer le dossier si nécessaire
            if (!is_dir(self::$storageDir)) {
                mkdir(self::$storageDir, 0755, true);
                file_put_contents(self::$storageDir . '.htaccess', 'Deny from all');
            }
            
            // Identifier par IP si non spécifié
            if ($identifier === null)
                $identifier = self::getClientIp();
            
            $filename = self::$storageDir . md5($action . '_' . $identifier) . '.json';
            $now = time();
            
            // Charger les tentatives existantes
            $attempts = [];
            if (file_exists($filename)) {
                $data = json_decode(file_get_contents($filename), true);

                if ($data && isset($data['attempts'])) {
                    // Nettoyer les vieilles tentatives
                    $attempts = array_filter($data['attempts'], function($timestamp) use ($now, $timeWindow) {
                        return ($now - $timestamp) < $timeWindow;
                    });
                }
            }
            
            // Vérifier si limite atteinte
            if (count($attempts) >= $maxAttempts)
                return false;
            
            // Ajouter nouvelle tentative
            $attempts[] = $now;
            
            // Sauvegarder
            file_put_contents($filename, json_encode([
                'attempts' => array_values($attempts),
                'last_attempt' => $now
            ]), LOCK_EX);
            
            // Nettoyer les vieux fichiers (1% de chance)
            if (rand(1, 100) === 1)
                self::cleanup();
            
            return true;
        }
        
        /**
         * Réinitialise le compteur pour un identifiant
         */
        public static function reset($action, $identifier = null) {
            if ($identifier === null)
                $identifier = self::getClientIp();
            
            $filename = self::$storageDir . md5($action . '_' . $identifier) . '.json';
            
            if (file_exists($filename))
                unlink($filename);
        }
        
        /**
         * Obtient le temps d'attente restant
         */
        public static function getWaitTime($action, $identifier = null, $maxAttempts = 5, $timeWindow = 300) {
            if ($identifier === null)
                $identifier = self::getClientIp();
            
            $filename = self::$storageDir . md5($action . '_' . $identifier) . '.json';
            
            if (!file_exists($filename))
                return 0;
            
            $data = json_decode(file_get_contents($filename), true);

            if (!$data || !isset($data['attempts']))
                return 0;
            
            $now = time();

            $attempts = array_filter($data['attempts'], function($timestamp) use ($now, $timeWindow) {
                return ($now - $timestamp) < $timeWindow;
            });
            
            if (count($attempts) < $maxAttempts)
                return 0;
            
            // Calculer le temps avant la prochaine tentative
            $oldestAttempt = min($attempts);
            $waitTime = $timeWindow - ($now - $oldestAttempt);
            
            return max(0, $waitTime);
        }
        
        /**
         * Récupère l'IP du client
         */
        private static function getClientIp() {
            // Attention sur serveur mutualisé avec proxy/CDN
            $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
            
            foreach ($ipKeys as $key) {
                if (isset($_SERVER[$key])) {
                    $ip = $_SERVER[$key];
                    
                    // Si plusieurs IPs (proxy), prendre la première
                    if (strpos($ip, ',') !== false)
                        $ip = explode(',', $ip)[0];
                    
                    $ip = trim($ip);
                    
                    // Valider l'IP
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE))
                        return $ip;
                }
            }
            
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
        
        /**
         * Nettoie les vieux fichiers
         */
        private static function cleanup($maxAge = 3600) {
            $files = glob(self::$storageDir . '*.json');
            $now = time();
            $cleaned = 0;
            
            foreach ($files as $file) {
                if (is_file($file) && ($now - filemtime($file)) > $maxAge) {
                    unlink($file);
                    $cleaned++;
                    
                    if ($cleaned > 50) // Limiter pour ne pas surcharger
                        break;
                }
            }
            
            return $cleaned;
        }
    }
?>