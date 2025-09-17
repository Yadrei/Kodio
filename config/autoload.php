<?php
    /*
    Autoload pour les différentes classes
    @Author Yves P.
    @Version 1.1
    @Date création: 14/08/2023
    @Dernière modification: 17/09/2025
    */

    spl_autoload_register(function ($className) {
        $basePath = 'src/';
        
        // Chemins possibles pour la classe
        $paths = [
            'controllers/' . $className . '.php',
            'controllers/front/' . $className . '.php',
            'controllers/back/' . $className . '.php',
            'models/' . $className . '.php',
            'models/database/' . $className . '.php',
            'services/' . $className . '.php',
        ];
        
        // Parcourir les chemins et charger SEULEMENT le fichier qui correspond à la classe
        foreach ($paths as $relativePath) {
            $file = $basePath . $relativePath;
            
            if (file_exists($file)) {
                require_once $file;

                return; // ← Important : arrêter dès qu'on trouve la classe
            }
        }
    });
?>