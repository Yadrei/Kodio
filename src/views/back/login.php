<?php
	/* 
		View from the login on the admin
    	@Author Yves P.
    	@Version 1.0
    	@Date création: 05/03/2021
    	@Dernière modification: 02/09/2025
    */

	ob_start();
?>

<form class="form-signin" action="<?php echo BASE_URL ?>private/login" method="post">
	<?php echo CSRF::Field(); ?>
    <img class="mb-4" src="<?php echo BASE_URL ?>public/images/logo/logo.png" alt="" width="400" height="153">
    <input type="text" class="form-control mb-3" name="nickname" id="nickname" placeholder="Nom d'utilisateur" minlength="4" maxlength="20" required autofocus>
    <input type="password" class="form-control" name="password" id="password" placeholder="Mot de passe" minlength="8" required>
    <button type="submit" class="btn btn-lg btn-primary btn-block" name="submit">Connexion</button>
    <p class="mt-5 mb-3 text-muted">&copy; Shoku Studio - 2023</p>
</form>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/login.php';
?>