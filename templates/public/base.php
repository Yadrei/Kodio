<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <title>Kodio</title>

	    <!-- Inclusion des fichiers CSS de Bootstrap -->
	    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	    <link type="text/css" rel="stylesheet" href="<?php echo BASE_URL ?>public/css/feather.css" crossorigin="anonymous">

	    <!-- Feather Icons -->
    	<script src="https://unpkg.com/feather-icons"></script>
	</head>
	<body class="d-flex flex-column min-vh-100">
		<header>
			<img src="<?php echo BASE_URL ?>public/images/logo/logo-admin.png" alt="" class="mt-5 mx-auto d-block" width="250" height="150">
		    <nav class="navbar navbar-expand-lg navbar-light text-uppercase">
		        <div class="container border-secondary-subtle border-2 border-top border-bottom">
		            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
		                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		                <span class="navbar-toggler-icon"></span>
		            </button>
		            <div class="collapse navbar-collapse" id="navbarNav">
		                <ul class="navbar-nav me-auto mb-2 mb-lg-0 py-2">
							<li class="nav-item">
								<a href="<?php echo BASE_URL.strtolower($currentLanguage->GetClef()); ?>/home" class="nav-link">Accueil</a>
							</li>
		                	<?php
		                		foreach ($mainMenu as $main) {
		                			$hasSub = array_filter($subMenu, function($subItem) use ($main) {
		                				return $subItem->getParent() === $main->getId();
		                			});

		                			if (!empty($hasSub)) {
		                				echo '
		                			<li class="nav-item dropdown">
		                				<a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">'.$main->getLabel().'
		                				</a>
		                				<ul class="dropdown-menu">';

		                				foreach ($hasSub as $sub) {
		                					echo '
		                					<li>
		                						<a href="'.BASE_URL.'/#" class="dropdown-item">'.$sub->getLabel().'</a>
		                					</li>';
		                				}

		                				echo '
		                				</ul>
		                			</li>';
		                			}
		                			else {
		                				echo '
		                			<li class="nav-item">
		                				<a href="'.BASE_URL.strtolower($main->getLanguage()).'/'.$main->getContent()->getSlug().'" class="nav-link">'.$main->getLabel().'</a>
		                			</li>';
		                			}
		                		}
		                	?>
							<li class="nav-item">
								<a href="<?php echo BASE_URL.strtolower($currentLanguage->GetClef()); ?>/contact" class="nav-link">Contact</a>
							</li>
		                </ul>
						<?php
							if ($countLanguages > 1) {
								echo '
						<div class="navbar-text" id="choose-language">
					    	<div class="dropdown">
								<button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $currentLanguage->GetClef(); ?></button>
							  	<ul class="dropdown-menu">';

							  		foreach($otherLanguages as $language) {
							  			echo '
									<li><a href="'.BASE_URL.strtolower($language->getclef()).'" class="dropdown-item">'.$language->GetClef().'</a></li>';
							  		}

							    echo '
							  	</ul>
							</div>
					    </div>';
							}
		                ?>
		            </div>
		        </div>
		    </nav>
		</header>

		<!-- Espace Contenu -->
		<main class="container mt-5 clearfix">
			<aside class="col-3 px-4 shadow rounded rounded-4 float-end">
				<h6 class="text-center text-uppercase text-secondary border-bottom border-2 p-3">A propos</h6>
				<img src="<?php echo BASE_URL; ?>/public/images/profil.jpg" class="img-thumbnail img-fluid rounded-circle w-50 mx-auto d-block my-3" alt="Image de profil de l'utilisateur du blog">
				<p><small>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean ut libero nulla. Donec a malesuada elit, pulvinar porttitor dolor.</small></p>
			</aside>
			<div class="row gx-5">
				<div class="col-11">
					<?php
						echo $content;
					?>
				</div>
			</div>
		</main>

		<footer class="footer mt-auto py-3 bg-light">
		    <div class="container">
		        <span class="text-muted text-left">© 2025 Shoku Studio</span>
				<div class="float-end">
					<a href="#" class="text-decoration-none">
						<span class="mx-2 text-secondary" data-feather="facebook"></span>
					</a>
					<a href="#" class="text-decoration-none">
						<span class="mx-2 text-secondary" data-feather="twitter"></span>
					</a>
					<a href="#" class="text-decoration-none">
						<span class="mx-2 text-secondary" data-feather="instagram"></span>
					</a>
				</div>
		    </div>
		</footer>

		<!-- Inclusion des fichiers JavaScript de Bootstrap -->
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

		<!-- Feather Icons -->
	    <script>
	      feather.replace()
	    </script>
	</body>
</html>