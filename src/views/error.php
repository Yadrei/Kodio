<?php
	ob_start();
?>
<div class="alert alert-danger" role="alert">
  <span class="feather-50 me-4" data-feather="alert-triangle"></span><span><?php echo $e->getMessage(); ?></span>
</div>
	
<?php
	$content = ob_get_clean();

	if (isset($_SESSION['isLog']) && $_SESSION['isLog'] === true)
		require_once __DIR__.'/../../templates/admin/base.php';
	else
		require_once __DIR__.'/../../templates/public/base.php';
?>