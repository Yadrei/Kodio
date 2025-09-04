<?php
    /* 
		Classe pour gérer l'upload de fichiers divers
		@Author Yves P.
		@Version 1.0
		@Date Création: 04/09/2025
		@Dernière modification: 04/09/2025
	*/

    class File {
        // Configuration
        private static $uploadDir = 'public/images/';
        private static $maxSize = 5242880; // 5MB
        private static $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
        private static $maxWidth = 1920;
        private static $maxHeight = 1080;
        private static $webpQuality = 85; // Qualité WebP (0-100)
        
        public static function upload($file, $folder = '') {
            // Vérifications de base
            if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name']))
                throw new Exception("Fichier invalide");
            
            // Vérifier la taille
            if ($file['size'] > self::$maxSize) {
                $maxSizeMB = self::$maxSize / 1048576;

                throw new Exception("Fichier trop volumineux (max {$maxSizeMB}MB)");
            }
            
            // Vérifier l'extension
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($extension, self::$allowedTypes))
                throw new Exception("Type de fichier non autorisé");
            
            // Créer le dossier si nécessaire
            $targetDir = self::$uploadDir;

            if ($folder) {
                $folder = preg_replace('/[^a-zA-Z0-9_\-]/', '', $folder);
                $targetDir .= $folder . '/';

                if (!is_dir($targetDir))
                    mkdir($targetDir, 0755, true);
            }
            
            // Traiter selon le type
            if ($extension === 'pdf')
                return self::uploadPDF($file['tmp_name'], $targetDir); // Les PDF ne sont pas convertis
            else 
                return self::uploadAndConvertImage($file['tmp_name'], $extension, $targetDir); // Les images sont converties en WebP
        }

        public static function uploadMultiple($files, $folder = '') {
            $results = [
                'success' => [],
                'errors' => [],
                'total' => 0
            ];
            
            // Vérifier si c'est un tableau de fichiers
            if (!isset($files['tmp_name']))
                throw new Exception("Aucun fichier à uploader");
            
            // Si c'est un seul fichier, le traiter directement
            if (!is_array($files['tmp_name'])) {
                try {
                    $result = self::upload($files, $folder);
                    $results['success'][] = $result;
                    $results['total'] = 1;
                } catch (Exception $e) {
                    $results['errors'][] = [
                        'file' => $files['name'],
                        'error' => $e->getMessage()
                    ];
                    $results['total'] = 1;
                }

                return $results;
            }
            
            // Traiter plusieurs fichiers
            $count = count($files['tmp_name']);
            $results['total'] = $count;
            
            for ($i = 0; $i < $count; $i++) {
                // Reconstruire le tableau $_FILES pour chaque fichier
                $singleFile = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                
                // Ignorer les slots vides
                if ($singleFile['error'] === UPLOAD_ERR_NO_FILE)
                    continue;
                
                // Vérifier les erreurs d'upload PHP
                if ($singleFile['error'] !== UPLOAD_ERR_OK) {
                    $results['errors'][] = [
                        'file' => $singleFile['name'],
                        'error' => null
                    ];

                    continue;
                }
                
                // Essayer d'uploader le fichier
                try {
                    $uploadResult = self::upload($singleFile, $folder);
                    $uploadResult['original_name'] = $singleFile['name']; // Garder le nom original
                    $results['success'][] = $uploadResult;
                } catch (Exception $e) {
                    $results['errors'][] = [
                        'file' => $singleFile['name'],
                        'error' => $e->getMessage()
                    ];
                }
            }
            
            return $results;
        }
        
        private static function uploadAndConvertImage($tmpPath, $originalExt, $targetDir) {
            // Vérifier que c'est vraiment une image
            $imageInfo = @getimagesize($tmpPath);

            if ($imageInfo === false)
                throw new Exception("Fichier image invalide");
            
            $sourceWidth = $imageInfo[0];
            $sourceHeight = $imageInfo[1];
            
            // Créer l'image source selon le type
            switch ($originalExt) {
                case 'jpg':
                case 'jpeg':
                    $sourceImage = imagecreatefromjpeg($tmpPath);

                    break;
                case 'png':
                    $sourceImage = imagecreatefrompng($tmpPath);

                    break;
                case 'gif':
                    $sourceImage = imagecreatefromgif($tmpPath);

                    break;
                case 'webp':
                    $sourceImage = imagecreatefromwebp($tmpPath);

                    break;
                default:
                    throw new Exception("Format d'image non supporté");
            }
            
            if (!$sourceImage)
                throw new Exception("Impossible de lire l'image");
            
            // Calculer les nouvelles dimensions (max 1920x1080)
            $ratio = min(
                self::$maxWidth / $sourceWidth,
                self::$maxHeight / $sourceHeight,
                1 // Ne pas agrandir si l'image est plus petite
            );
            
            $newWidth = round($sourceWidth * $ratio);
            $newHeight = round($sourceHeight * $ratio);
            
            // Créer l'image redimensionnée
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Préserver la transparence pour PNG/GIF
            if ($originalExt === 'png' || $originalExt === 'gif') {
                imagecolortransparent($resizedImage, imagecolorallocatealpha($resizedImage, 0, 0, 0, 127));
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
            }
            
            // Redimensionner
            imagecopyresampled(
                $resizedImage, $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $sourceWidth, $sourceHeight
            );
            
            // Générer un nom unique
            $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.webp';
            $targetPath = $targetDir . $filename;
            
            // Sauvegarder en WebP
            if (!imagewebp($resizedImage, $targetPath, self::$webpQuality)) {
                imagedestroy($sourceImage);
                imagedestroy($resizedImage);

                throw new Exception("Erreur lors de la conversion WebP");
            }
            
            // Libérer la mémoire
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);
            
            // Obtenir la taille finale
            $finalSize = filesize($targetPath);
            
            return [
                'name' => $filename,
                'path' => $targetPath,
                'url' => BASE_URL . $targetPath,
                'size' => $finalSize,
                'original_size' => filesize($tmpPath),
                'compression' => round((1 - $finalSize / filesize($tmpPath)) * 100, 1), // % de compression
                'dimensions' => $newWidth . 'x' . $newHeight,
                'format' => 'webp'
            ];
        }
        
        private static function uploadPDF($tmpPath, $targetDir) {
            $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.pdf';
            $targetPath = $targetDir . $filename;
            
            if (!move_uploaded_file($tmpPath, $targetPath))
                throw new Exception("Erreur lors de l'upload du PDF");
            
            return [
                'name' => $filename,
                'path' => $targetPath,
                'url' => BASE_URL . $targetPath,
                'size' => filesize($targetPath),
                'format' => 'pdf'
            ];
        }
        
        public static function delete($filepath) {
            // Vérifier que c'est dans le bon dossier
            $realpath = realpath($filepath);
            $uploadRealpath = realpath(self::$uploadDir);
            
            if (!$realpath || strpos($realpath, $uploadRealpath) !== 0)
                return false;
            
            if (file_exists($filepath))
                return unlink($filepath);
            
            return false;
        }
        
        /**
         * Génère des versions multiples (thumbnail, medium, large)
         * Utile pour les images responsive
         */
        public static function generateMultipleSizes($file, $folder = '') {
            $sizes = [
                'thumb' => ['width' => 150, 'height' => 150],
                'medium' => ['width' => 800, 'height' => 600],
                'large' => ['width' => 1920, 'height' => 1080]
            ];
            
            $results = [];
            
            foreach ($sizes as $sizeName => $dimensions) {
                // Modifier temporairement les dimensions max
                $oldWidth = self::$maxWidth;
                $oldHeight = self::$maxHeight;
                
                self::$maxWidth = $dimensions['width'];
                self::$maxHeight = $dimensions['height'];
                
                // Créer un sous-dossier pour cette taille
                $sizeFolder = $folder ? $folder . '/' . $sizeName : $sizeName;
                
                try {
                    $results[$sizeName] = self::upload($file, $sizeFolder);
                } catch (Exception $e) {
                    // Restaurer et propager l'erreur
                    self::$maxWidth = $oldWidth;
                    self::$maxHeight = $oldHeight;
                    throw $e;
                }
                
                // Restaurer les dimensions
                self::$maxWidth = $oldWidth;
                self::$maxHeight = $oldHeight;
            }
            
            return $results;
        }
    }
?>