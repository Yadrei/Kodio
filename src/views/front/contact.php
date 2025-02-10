<?php
	ob_start();
?>

<h1>Contact</h1>
<form action="contact/send" method="post">
	<div class="form-floating mb-3">
		<input type="text" class="form-control" name="name" id="name" placeholder="<?php echo $name ?>" minlength="5"  maxlength="50" required>
		<label for="name"><?php echo $name ?></label>
	</div>
	<div class="form-floating mb-3">
		<input type="email" class="form-control" name="email" id="email" placeholder="<?php echo $email ?>" required>
		<label for="email"><?php echo $email ?></label>
	</div>
	<div class="form-floating mb-3">
		<select class="form-select" name="subject" id="subject" required>
		    <option value="" selected><?php echo $subjectList ?></option>
		    <option value="Missions">Missions</option>
		    <option value="Corps">Corps Européen de solidatiré</option>
		    <option value="JDE">Journal des Enfants</option>
		</select>
		<label for="subject"><?php echo $subject ?></label>
	</div>
	<div class="form-floating mb-3">
		<textarea class="form-control" name="text" id="text" style="height: 200px" placeholder="<?php echo $message ?>" minlength="100" maxlength="1000" required></textarea>
		<label for="text"><?php echo $message ?></label>
	</div>
	<label class="mark d-none">Mark</label>
	<input class="mark d-none" name="mark" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="nom@dom.com">
	<button type="submit" name="submit" class="btn btn-primary"><?php echo $send ?></button>
</form>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/public/base.php';
?>