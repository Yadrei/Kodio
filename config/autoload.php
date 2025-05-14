<?php
    /*
        Autoload pour les différentes classes
        @Author Yves P.
        @Version 1.1
        @Date création: 14/08/2023
        @Dernière modification: 14/05/2025
    */

    spl_autoload_register(function ($className) {
        $basePath = __DIR__ . '/src/';

        $paths = [
            'controllers/ExceptionHandler.php', // chargement unique
            'controllers/front/' . $className . '.php',
            'controllers/back/' . $className . '.php',
            'models/' . $className . '.php',
            'models/database/' . $className . '.php',
            'services/' . $className . '.php', // ← ajouté pour Mail
        ];

        // Charger ExceptionHandler explicitement si présent
        $exceptionHandlerPath = $basePath . 'controllers/ExceptionHandler.php';
        if (file_exists($exceptionHandlerPath)) {
            require_once $exceptionHandlerPath;
        }

        // Parcourir les chemins et charger le fichier correspondant à la classe
        foreach ($paths as $relativePath) {
            $file = $basePath . $relativePath;
            if (file_exists($file)) {
                require_once $file;
                break;
            }
        }
    });
?>