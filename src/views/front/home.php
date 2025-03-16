<?php
	ob_start();
?>
<?php
	foreach ($contentsList as $content) {
		echo '
		<div class="card mb-3 border border-0">
			<div class="row g-0">
				<div class="col-md-5">
					<img src="public/images/heading/'.$content->getImage().'" class="img-fluid rounded-4" alt="...">
				</div>
				<div class="col-md-7">
					<div class="card-body">
						<small class="text-body-secondary">Par '.$content->getAuthor()->getNickname().'</small>
						<h5 class="card-title">'.$content->getTitle().'</h5>
						<p class="card-text">'.substr(strip_tags(html_entity_decode($content->getContent())), 0, 150).'...</p>
						<a href="'.BASE_URL.strtolower($content->getLanguage()).'/'.$content->getSlug().'" class="btn btn-sm btn-primary">Lire plus</a>
					</div>
				</div>
			</div>
		</div>
		<hr>';
	}
?>
<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/public/base.php';
?>