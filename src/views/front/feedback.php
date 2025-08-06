<?php
	ob_start();
?>
<div class="alert alert-primary" role="alert">
  <span class="me-4"></span><span><?php echo $message; ?></span>
</div>
<a href="<?php echo BASE_URL; ?>">Retour à l'accueil</a>
<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/public/base.php';
?>