<?php
	ob_start();
?>

<h1 class="my-3"><?php echo htmlspecialchars_decode($content->getTitle()); ?></h1>
<?php echo htmlspecialchars_decode($content->getContent()); ?>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/public/base.php';
?>