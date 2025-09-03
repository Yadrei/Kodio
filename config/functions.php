<?php
    function ProcessImages($uploadFolder)
    {
        $destinationFolder = "public/images/";

        if (!empty($uploadFolder))
            $destinationFolder = $destinationFolder.$uploadFolder.'/';

        // Vérifier si le répertoire d'upload existe, sinon le créer
        if (!is_dir($destinationFolder)) {
            mkdir($destinationFolder, 0777, true);
        }

        $allowedTypes = array("image/webp", "image/jpg", "image/jpeg", "image/png");
        
        if (!empty($_FILES["image"]["tmp_name"])) {
            $tmpName = $_FILES["image"]["tmp_name"];
            $fileName = $_FILES["image"]["name"];
            $fileType = $_FILES["image"]["type"];
            $fileSize = $_FILES["image"]["size"];

            // Vérifier le type de fichier
            if (!in_array($fileType, $allowedTypes)) 
                throw New Exception( "Le fichier $fileName n'est pas une image valide.");

            // Vérifier la taille du fichier
            $maxFileSize = 2 * 1024 * 1024; // 2 Mo

            if ($fileSize > $maxFileSize)
                throw New Exception("Le fichier $fileName dépasse la taille maximale autorisée.");


            // Créer une image à partir du fichier
            $sourceImage = imagecreatefromstring(file_get_contents($tmpName));

            // Redimensionner l'image à une résolution maximale de 1920x1080 pixels
            $maxWidth = 1920;
            $maxHeight = 1080;
            $sourceWidth = imagesx($sourceImage);
            $sourceHeight = imagesy($sourceImage);

            $newWidth = min($sourceWidth, $maxWidth);
            $newHeight = min($sourceHeight, $maxHeight);

            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);

            // Sauvegarder l'image au format WebP
            $webpFileName = $destinationFolder.pathinfo($fileName, PATHINFO_FILENAME).'.webp';
            imagewebp($resizedImage, $webpFileName);

            // Libérer la mémoire
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);

            return $webpFileName;
        }

        return null;
    }

    function DeleteImage($dossier, $nomImage) 
    {
        if (is_dir($dossier)) {
            if ($handle = opendir($dossier)) {
                while (false !== ($fichier = readdir($handle))) {
                    if ($fichier != "." && $fichier != "..") {
                        $cheminFichier = $dossier . '/' . $fichier;

                        // Vérifier si c'est un dossier
                        if (is_dir($cheminFichier)) 
                            DeleteImage($cheminFichier, $nomImage);
                        else 
                            if (isImage($fichier) && $fichier == $nomImage) 
                                unlink($cheminFichier);
                    }
                }
                // Fermer le dossier
                closedir($handle);
            }

            return true;
        } 
        else 
           return false;
    }

    function isImage($fichier) 
    {
        $extensionsImage = array("webp");
        $extension = pathinfo($fichier, PATHINFO_EXTENSION);

        return in_array(strtolower($extension), $extensionsImage);
    }

    function loadEnv(string $path): void
    {
        if (!file_exists($path))
            throw new RuntimeException(".env file not found at: $path");

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#'))
                continue;

            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
?>
