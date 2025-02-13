<?php
	ob_start();
?>

<h1>Dashboard</h1>
<?php if ($maintenance): ?>

<div class="alert alert-warning" role="alert">
  <span class="feather-80 me-4" data-feather="alert-triangle"></span><span class="text-uppercase">Attention ! Le site est actuellement en mode Maintenance</span>
</div>

<?php endif ?>

<div class="row">
	<div class="col-4">
		<div class="card bg-info-subtle">
			<div class="card-header">
				<span class="me-2 feather-25" data-feather="file-text"></span> Contenu
			</div>
			<div class="card-body">
			<?php 
				echo '<p class="card-text">Vous avez <a href="#">'.$contentCount.' contenus</a> non publiés</p>';
			?>
			</div>
		</div>
	</div>
</div>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>