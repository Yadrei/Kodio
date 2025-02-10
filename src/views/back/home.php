<?php
	ob_start();
?>

<h1>Dashboard</h1>
<?php if ($maintenance): ?>

<div class="alert alert-warning" role="alert">
  <span class="feather-80 me-4" data-feather="alert-triangle"></span><span class="text-uppercase">Attention ! Le site est actuellement en mode Maintenance</span>
</div>

<?php endif ?>
<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>