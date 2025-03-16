<?php
	ob_start();
?>

<img src="<?php echo BASE_URL; ?>/public/images/heading/<?php echo $content->getImage(); ?>" class="img-fluid rounded-4" alt="...">
<h1 class="text-secondary my-5"><?php echo htmlspecialchars_decode($content->getTitle()); ?></h1>
<?php echo htmlspecialchars_decode($content->getContent()); ?>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/public/base.php';
?>