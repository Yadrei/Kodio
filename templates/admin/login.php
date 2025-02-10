<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    
	    <!-- Inclusion des fichiers CSS de Bootstrap -->
	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	    <link type="text/css" rel="stylesheet" href="<?php echo BASE_URL ?>public/css/cms-admin-login.css" crossorigin="anonymous">

	    <title>CMS - Login administration</title>
	</head>
	<body class="text-center">
		    <?php
		        echo $content;
		    ?>

		<!-- Inclusion des fichiers JavaScript de Bootstrap (facultatif) -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	</body>
</html>