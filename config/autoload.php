<?php
	/* 
		Autoload pour les différenets classes
    	@Author Yves P.
	    @Version 1.0
	    @Date création: 14/08/2023
	    @Dernière modification: 27/09/2023
    */

	spl_autoload_register(function ($className) {
		$exceptionPathFile = 'src/controllers/ExceptionHandler.php';
	    $frontFilePath = 'src/controllers/front/'.$className.'.php';
	    $backFilePath = 'src/controllers/back/'.$className.'.php';
	    $genericFilePath = 'src/models/'.$className.'.php';
	    $databaseFilePath = 'src/models/database/'.$className.'.php';
	    
	    if (file_exists($exceptionPathFile))
	    	require_once($exceptionPathFile);
	    
	    if (file_exists($frontFilePath)) {
	        require_once $frontFilePath;
	    }
	    elseif (file_exists($backFilePath)) {
	    	require_once $backFilePath;
	    }
	    elseif (file_exists($genericFilePath)) {
	    	require_once $genericFilePath;
	    }
	    elseif(file_exists($databaseFilePath)) {
			require_once $databaseFilePath;
		}
	});
?>