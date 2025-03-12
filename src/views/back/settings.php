<?php
	ob_start();
?>

<h1>Paramètres</h1>
<nav>
	<ul class="nav nav-tabs" id="navCat" role="tablist">
		<li class="nav-item" role="presentation">
			<a href="#" class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" role="tab" aria-controls="general-pane" aria-selected="true">Général</a>
		</li>
		<li class="nav-item" role="presentation">
			<a href="#" class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories-pane" role="tab" aria-controls="categories-pane" aria-selected="true">Catégories de contenu</a>
		</li>
		<li class="nav-item" role="presentation">
			<a href="#" class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles-pane" role="tab" aria-controls="roles-pane" aria-selected="true">Rôles</a>
		</li>
		<li class="nav-item" role="presentation">
			<a href="#" class="nav-link" id="languages-tab" data-bs-toggle="tab" data-bs-target="#languages-pane" role="tab" aria-controls="languages-pane" aria-selected="true">Langues</a>
		</li>
	</ul>
</nav>
<div class="tab-content border border-top-0" id="tabForms">
	<div class="tab-pane fade show active p-3" id="general-pane" role="tabpanel" aria-labelledby="general-tab" tabindex="0">
		<h4 class="text-secondary border-bottom">Maintenance</h4>
		<form class="mb-3" action="<?php echo BASE_URL ?>private/maintenance" method="post">
			<div class="form-check form-switch mb-3">
				<label class="form-check-label" for="maintenance">Activer le mode maintenance ?</label>
				<?php if ($maintenance): ?>

				<input class="form-check-input" type="checkbox" role="switch" name="maintenance" id="maintenance" checked="checked" aria-describedby="maintenanceHelp">

				<?php else: ?>

				<input class="form-check-input" type="checkbox" role="switch" name="maintenance" id="maintenance" aria-describedby="maintenanceHelp">

				<?php endif ?>
				<div id="maintenanceHelp" class="form-text">
					Activer ce mode permet le mettre le site en "mode Maintenance". C'est à dire que les visiteurs ne pourront plus naviguer dessus et verrons une page spéciale qui indique que le site est en maintenance. Seul vous pourrez encore voir le site normalement, à condition d'être connecté.
				</div>
			</div>
			<button type="submit" class="btn btn-light-blue" name="submit">Valider</button>
		</form>
		<h4 class="text-secondary border-bottom">Commentaires</h4>
		<form class="mb-3" action="<?php echo BASE_URL ?>private/comments" method="post">
			<div class="alert alert-warning" role="alert">
			  Attention ! Cette fonctionnalité n'est pas encore complètement implémentée ! Seul la gestion est effective
			</div>
			<div class="form-check form-switch mb-3">
				<label class="form-check-label" for="comments">Activer les commentaires ?</label>
				<?php if ($comments): ?>

				<input class="form-check-input" type="checkbox" role="switch" name="comments" id="comments" checked="checked" aria-describedby="commentsHelp">

				<?php else: ?>

				<input class="form-check-input" type="checkbox" role="switch" name="comments" id="comments" aria-describedby="commentsHelp">

				<?php endif ?>
				<div id="commentsHelp" class="form-text">
					Activer les commentaires permet aux visiteurs de commenter votre contenu. Ceux-ci n'auront pas besoin de s'inscrire pour pouvoir commenter. Celà peut être dangereux, vous pouvez être spammer par des robots.
				</div>
			</div>
			<button type="submit" class="btn btn-light-blue" name="submit">Valider</button>
		</form>
		<h4 class="text-secondary border-bottom">Cookies</h4>
		<form action="<?php echo BASE_URL ?>private/cookies" method="post">
			<div class="form-check form-switch mb3">
				<label class="form-check-label" for="cookies">Activer le bandeau de cookies ?</label>
				<?php if ($cookies): ?>

				<input class="form-check-input" type="checkbox" role="switch" name="cookies" id="cookies" checked="checked" aria-describedby="cookiesHelp">

				<?php else: ?>

				<input class="form-check-input" type="checkbox" role="switch" name="cookies" id="cookies" aria-describedby="cookiesHelp">

				<?php endif ?>
				<div id="cookiesHelp" class="form-text mb-3">
					Affiche ou non un petit bandeau informatif sur le site pour indiquer aux visiteurs qu'aucuns cookies d'aucunes sortes ne sont utilisés.
				</div>
			</div>
			<button type="submit" class="btn btn-light-blue" name="submit">Valider</button>
		</form>
	</div>
	<div class="tab-pane fade p-3" id="categories-pane" role="tabpanel" aria-labelledby="categories-pane" tabindex="0">
		<?php
			if ($permissionsLogged->getAllowAdd()) {
				echo '
				<button type="button" class="btn btn-light-blue btn-sm my-3" data-bs-toggle="modal" data-bs-target="#addCategory">Ajouter une catégorie</button>
				<div class="modal fade" id="addCategory" tabindex="-1" aria-labelledby="addCategory-modal" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="addCategory-modal">Ajouter une catégorie</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<form id="addCategory-form">
								<div class="modal-body">
									<div class="mb-3 form-floating">
										<input type="text" class="form-control" name="cat" id="cat" minlength="4" maxlength="50" required>
										<label for="cat">Intitulé</label>
									</div>
									<div class="mb-3 form-floating">
										<input type="text" class="form-control" name="cat-key" id="cat-key" style="text-transform: uppercase;" minlength="2" maxlength="10" required>
										<label for="cat-key">Abbréviation</label>
									</div>
								</div>
								<div class="modal-footer">
					        		<button type="submit" name="submit" class="btn btn-sm btn-light-blue">Ajouter</button>
								</div>
							</form>
						</div>
					</div>
				</div>';
			}
		?>

		<div class="table-responsive">
			<table class="table table-sm align-middle">
				<thead>
					<tr class="d-flex">
						<th class="col-9" scope="col">Catégorie</th>
						<th class="col-3" scope="col">Action</th>
					</tr>
				</thead>
				<tbody class="table-group-divider">
				<?php
					foreach($categories as $cat) {
						echo '
					<tr class="d-flex">
						<td class="col-9">'.$cat->getLabel().'</td>
						<td class="col-3">';

							if ($permissionsLogged->getAllowDelete())
								echo '<a href="#" class="m-1 delete-category" data-key="'.$cat->getClef().'" data-toggle="tooltip" title="Supprimer" role="button"><span class="feather-15 red" data-feather="trash-2"></span></a>';
							else
								echo '<span class="feather-15 disabled m-1" data-feather="trash-2"></span>';

						echo '
						</td>
					</tr>
						';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="tab-pane fade p-3" id="roles-pane" role="tabpanel" aria-labelledby="roles-tab" tabindex="0">
		<?php
			if ($permissionsLogged->getAllowAdd()) {
				echo '
				<button type="button" class="btn btn-light-blue btn-sm my-3" data-bs-toggle="modal" data-bs-target="#addRole">Ajouter un rôle</button>
				<div class="modal fade" id="addRole" tabindex="-1" aria-labelledby="addRole-modal" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="addRole-modal">Ajouter un rôle</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<form id="addRole-form">
								<div class="modal-body">
									<div class="mb-3 form-floating">
										<input type="text" class="form-control" name="role" id="role" minlength="4" maxlength="50" required>
										<label for="role">Rôle</label>
									</div>
									<div class="mb-3 form-floating">
										<input type="text" class="form-control" name="role-key" id="role-key" style="text-transform: uppercase;" minlength="2" maxlength="10" required>
										<label for="role-key">Abbréviation</label>
									</div>
								</div>
								<div class="modal-footer">
					        		<button type="submit" name="submit" class="btn btn-sm btn-light-blue">Ajouter</button>
								</div>
							</form>
						</div>
					</div>
				</div>';
			}
		?>

		<div class="table-responsive">
			<table class="table table-sm align-middle">
				<thead>
					<tr class="d-flex">
						<th class="col-9" scope="col">Rôle</th>
						<th class="col-3" scope="col">Action</th>
					</tr>
				</thead>
				<tbody class="table-group-divider">
				<?php
					foreach($roles as $role) {
						echo '
					<tr class="d-flex">
						<td class="col-9">'.$role->getLabel().'</td>
						<td class="col-3">';

							if ($permissionsLogged->getAllowDelete())
								echo '<a href="#" class="m-1 delete-role" data-key="'.$role->getClef().'" data-toggle="tooltip" title="Supprimer" role="button"><span class="feather-15 red" data-feather="trash-2"></span></a>';
							else
								echo '<span class="feather-15 disabled m-1" data-feather="trash-2"></span>';

						echo '
						</td>
					</tr>
						';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="tab-pane fade p-3" id="languages-pane" role="tabpanel" aria-labelledby="languages-tab" tabindex="0">
		<?php
			if ($permissionsLogged->getAllowAdd()) {
				echo '
				<div class="alert alert-warning" role="alert">
				  <span class="feather-20 me-4" data-feather="alert-triangle"></span><span>Attention ! Le système ne gère que l\'alphabet latin</span>
				</div>
				<button type="button" class="btn btn-light-blue btn-sm my-3" data-bs-toggle="modal" data-bs-target="#addLanguage">Ajouter une langue</button>
				<div class="modal fade" id="addLanguage" tabindex="-1" aria-labelledby="addLanguage-modal" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="addLanguage-modal">Ajouter une langue</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<form id="addLanguage-form">
								<div class="modal-body">
									<div class="mb-3 form-floating">
										<input type="text" class="form-control" name="language" id="language" minlength="4" maxlength="50" required>
										<label for="language">Langue</label>
									</div>
									<div class="mb-3 form-floating">
										<input type="text" class="form-control" name="language-key" id="language-key" style="text-transform: uppercase;" minlength="2" maxlength="10" required>
										<label for="language-key">Abbréviation</label>
									</div>
								</div>
								<div class="modal-footer">
					        		<button type="submit" name="submit" class="btn btn-sm btn-light-blue">Ajouter</button>
								</div>
							</form>
						</div>
					</div>
				</div>';
			}
		?>

		<div class="table-responsive">
			<table class="table table-sm align-middle">
				<thead>
					<tr class="d-flex">
						<th class="col-9" scope="col">Langue</th>
						<th class="col-3" scope="col">Action</th>
					</tr>
				</thead>
				<tbody class="table-group-divider">
				<?php
					foreach($langues as $langue) {
						echo '
					<tr class="d-flex">
						<td class="col-9">'.$langue->getLabel().'</td>
						<td class="col-3">';

							if ($permissionsLogged->getAllowDelete())
								echo '<a href="#" class="m-1 delete-language" data-key="'.$langue->getClef().'" data-toggle="tooltip" title="Supprimer" role="button"><span class="feather-15 red" data-feather="trash-2"></span></a>';
							else
								echo '<span class="feather-15 disabled m-1" data-feather="trash-2"></span>';

						echo '
						</td>
					</tr>
						';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>