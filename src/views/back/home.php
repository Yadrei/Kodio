<?php
	ob_start();

	// Verif si cookies activé"s ou non
	if ($cookies) {
		$textCookies = 'Les cookies sont activés';
		$bgCookies = ' bg-success-subtle';
	}
	else {
		$textCookies = 'Les cookies sont désactivés';
		$bgCookies = 'bg-warning-subtle';
	}
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
				echo '<p class="card-text">Vous avez <a href="content">'.$contentCount.' contenus</a> non publiés</p>';
			?>
			</div>
		</div>
	</div>
	<div class="col-4">
		<div class="card <?php echo $bgCookies; ?>">
			<div class="card-header">
				<span class="me-2 feather-25" data-feather="aperture"></span> Cookies
			</div>
			<div class="card-body">
				<?php
					echo '<p class="card-text">'.$textCookies.'</p>';
				?>
			</div>
		</div>
	</div><!--
	<div class="col-4">
		<div class="card">
			<div class="card-header">
				<span class="me-2 feather-25" data-feather="message-square"></span> Commentaires
			</div>
			<div class="card-body">
				<?php
					echo '<p class="card-text">'.$textCommentaires.'</p>';
				?>
			</div>
		</div>
	</div>-->
</div>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>