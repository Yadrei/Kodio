<?php
	ob_start();
?>

<img src="<?php echo BASE_URL; ?>/public/images/heading/<?php echo $content->getImage(); ?>" class="img-fluid rounded-4" alt="...">
<h1 class="text-secondary my-5"><?php echo htmlspecialchars_decode($content->getTitle()); ?></h1>
<?php echo htmlspecialchars_decode($content->getContent()); ?>
<div id="commentaries">
	<p class="text-uppercase border-bottom border-2 mt-5 pb-1">Vos réaction(s)</p>
	<?php
		foreach ($comments as $comment) 
		{
			echo '
	<div class="comment mb-3 p-2 bg-light">
		<p>
			<small>Par '.$comment->getNickname().', Le '.$comment->getDateComment().'</small>
		</p>
		<p>
		'.$comment->getText().'
		</p>
	</div>';
		}
	?>
</div>
<div id="react">
	<p class="text-uppercase border-bottom border-2 mt-5 pb-1">Réagir</p>
	<form action="<?php echo BASE_URL ?>reaction/action/add" method="post">
		<div class="form-floating mb-3">
			<input type="text" class="form-control" name="nickname" id="nickname" placeholder="Votre pseudo" required>
			<label for="nickname">Votre pseudo</label>
		</div>
		<div class="mb-3">
			<textarea class="form-control" name="text" id="text" rows="5" placeholder="Votre texte (minimum 20 caractères)" minlength="20" required></textarea>
		</div>
		<input type="hidden" name="contentId" id="contentId" value="<?php echo $content->getId(); ?>">
		<label class="mark d-none">Mark</label>
		<input class="mark d-none" name="mark" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="nom@dom.com">
		<div class="mb-3">
			<button type="submit" class="btn btn-primary">Envoyer</button>
		</div>
	</form>
</div>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/public/base.php';
?>