<?php
	ob_start();
?>

<h1>Utilisateurs</h1>

<?php
	if ($permissionsLogged->getAllowAdd()) {
		echo '
		<button type="button" class="btn btn-light-blue btn-sm my-3" data-bs-toggle="modal" data-bs-target="#addUser-modal">Ajouter un utilisateur</button>
		<div class="alert alert-success alert-dismissible fade" id="alert-success" role="alert">
			<button type="button" class="btn-close close" data-dismiss="alert" aria-label="Fermer"></button>
			<span id="alert-text"></span>
		</div>
		<div class="modal fade" id="addUser-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Ajouter un utilisateur</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="addUser-form">
						<div class="modal-body">
							<div class="mb-3 form-floating">
								<input type="text" class="form-control" name="nickname" id="nickname" minlength="4" maxlength="20" required>
								<label for="nickname">Nom d\'utilisateur</label>
							</div>
							<div class="mb-3 form-floating">
								<input type="password" class="form-control" name="password" id="password" minlength="8" required>
								<label for="password">Mot de passe</label>
							</div>
							<div class="mb-3 form-floating">
								<input type="email" class="form-control" name="email" id="email" required>
								<label for="email">Email</label>
							</div>
							<div class="mb-3 form-floating">
								<select class="form-select" name="addUser-role" id="addUser-role" required>
									<option value="DEFAULT" selected>Choisir un rôle</option>';

									foreach($references as $ref)
									{
										echo '
										<option value="'.$ref->getClef().'">'.$ref->getLabel().'</option>';
									}

								echo '
								</select>
								<label for="addUser-role">Rôle</label>
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
			<tr>
				<th scope="col">Utilisateur</th>
				<th scope="col">Adresse e-mail</th>
				<th scope="col">Role actuel</th>
				<th scope="col">Nouveau rôle</th>
				<th scope="col">Action</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
		<?php
			foreach($listUsers as $user)
			{
				echo '
				<tr>
					<td>
						<a href="#permissions-'.$user->getId().'" class="text-reset me-1" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="permissions-'.$user->getId().'">'.$user->getNickname().'</a>
					</td>
					<td>'.$user->getEmail().'</td>
					<td>'.$user->getRole()->getLabel().'</td>
					<td>
						<form class="row row-cols-2-auto g-1" id="newRole">
							<div class="col-10">
								<select class="form-select form-select-sm" name="updatedRole" id="updatedRole" required>
									<option value="DEFAULT" selected>Choisir un nouveau rôle</option>';
									foreach($references as $ref)
									{
										echo '
										<option value="'.$ref->getClef().'">'.$ref->getLabel().'</option>';
									}
								echo '
								</select>
							</div>
							<div class="col-2">
								<input type="hidden" name="user-id" value="'.$user->getId().'">
								<button type="'.($permissionsLogged->getAllowUpdate() ? 'submit' : 'button').'" class="btn btn-sm" name="changeRole" id="changeRole" data-toggle="tooltip" title="Valider le nouveau rôle">
									<span class="feather-15 '.($permissionsLogged->getAllowUpdate() ? 'green' : 'disabled').'" data-feather="check"></span>
								</button>
							</div>
						</form>
					</td>
					<td>';

					if ($permissionsLogged->getAllowUpdate())
						echo '<a href="#" class="new-Password" data-parametre="'.$user->getId().'" data-toggle="tooltip" title="Générer un nouveau mot de passe" role="button"><span class="feather-15" data-feather="rotate-cw"></span></a>';
					else
						echo '<span class="feather-15 disabled" data-feather="rotate-cw"></span>';

					echo '
					</td>
				</tr>
				<tr class="collapse" id="permissions-'.$user->getId().'">
					<td colspan="5">
						<form action="'.BASE_URL.'private/users/action/permissions" method="post">
							<table class="table table-sm table-info">
								<thead>
									<tr>
										<th colspan="4">
											Permissions de l\'utilisateur
										</th>
									</tr>
									<tr>
										<th>Accès</th>
										<th>Ajouter</th>
										<th>Modifier</th>
										<th>Supprimer</th>
									</tr>
								</thead>
								<tbody>
									<tr>';

								foreach ($permissions as $perm) {

									if ($perm->getUser() === $user->getId()) {
										echo '
									<td>
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" name="allow[access]" id="allow-access" '.($perm->getAllowAccess() ? 'checked' : '').'>
										</div>
									</td>
									<td>
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" name="allow[add]" id="allow-add" '.($perm->getAllowAdd() ? 'checked' : '').'>
										</div>
									</td>
									<td>
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" name="allow[update]" id="allow-update" '.($perm->getAllowUpdate() ? 'checked' : '').'>
										</div>
									</td>
									<td>
										<div class="form-check form-switch">
											<input class="form-check-input" type="checkbox" role="switch" name="allow[delete]" id="allow-delete" '.($perm->getAllowDelete() ? 'checked' : '').'>
										</div>
									</td>';
									}
								}

								echo '
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="4">
											<input type="hidden" name="user" value="'.$user->getId().'">
											<button type="'.($permissionsLogged->getAllowUpdate() ? 'submit' : 'button').'" class="btn btn-sm btn-light-blue">Mettre à jour</button>
										</td>
									</tr>
								</tfoot>
							</table>
						</form>
					</td>
				</tr>';
			}
		?>
		</tbody>
	</table>
</div>
<?php
	echo $pagination -> Display();

	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>