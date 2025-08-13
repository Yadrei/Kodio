<!DOCTYPE html>
<html lang="fr">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex, nofollow">

	    <title>Administration - Kodio 1.0</title>

	    <!-- Inclusion des fichiers CSS de Bootstrap -->
	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css">

	    <link type="text/css" rel="stylesheet" href="<?php echo BASE_URL ?>public/css/cms-admin-min.css" crossorigin="anonymous">
	    <link type="text/css" rel="stylesheet" href="<?php echo BASE_URL ?>public/css/feather.css" crossorigin="anonymous">

		<!-- Feather Icons JavaScript (pour activer les icônes) -->
		<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"></script>

	</head>
	<body>
		<header class="text-white">
			<span class="ms-3">Connecté en tant que <?php echo $_SESSION['user'] ?></span>
			<a href="logout" class="btn-light-blue px-4 float-end" role="button"><span class="align-middle">Logout</span></a>
		</header>
		<div class="row g-0">
			<div class="col-sm-2">
				<nav class="vh-100">
					<ul class="nav flex-column pt-5">
					<?php
			            foreach (MENU as $key => $value) {
			              	echo '
			            <li class="nav-item">
			            	<a href="'.$value["link"].'" class="nav-link"><span class="me-3" data-feather="'.$value["icon"].'"></span>'.$key.'</a>
			            </li>';
			            }
			        ?>
					</ul>
				</nav>
			</div>
			<div class="col-sm-10 mt-5 border-start">
				<!-- Espace Contenu -->
				<main class="container">
				    <?php
				        echo $content;
				    ?>
				</main>
			</div>
		</div>

		<!-- Inclusion des fichiers JavaScript de Bootstrap (facultatif) -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

		<!-- jquery -->
	    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/langs/fr.min.js"></script>

        <script>
            $('.editor').trumbowyg({
                lang: 'fr'
            });
        </script>

		<!-- Feather Icons -->
	    <script>
	      feather.replace()
	    </script>

	    <script src="<?php echo BASE_URL ?>public/js/ajax.js"></script>
	    <script src="<?php echo BASE_URL ?>public/js/main.js"></script>
	</body>
</html>