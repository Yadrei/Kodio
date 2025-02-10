<?php
	ob_start();

	 echo password_hash("halestorm", PASSWORD_DEFAULT);
?>

<a href="<?php echo BASE_URL.'private'; ?>">Vers admin</a>
<h1 class="my-3">Contenu</h1>
<?php
	foreach ($contentsList as $content) {
		echo '<h5><a href="'.BASE_URL.strtolower($content->getLanguage()).'/'.$content->getSlug().'">'.$content->getTitle().'</a></h5>';
	}
?>
<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/public/base.php';
?>