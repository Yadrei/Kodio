<?php
	ob_start();
?>

<h1>Étiquettes</h1>

<?php
	if ($permissionsLogged->getAllowAdd()) {
		echo '
		<button type="button" class="btn btn-light-blue btn-sm my-3" data-bs-toggle="modal" data-bs-target="#addTag-modal">Ajouter une étiquette</button>
		<div class="modal fade" id="addTag-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Ajouter une étiquette</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="addTag-form">
						<div class="modal-body">
							<div class="mb-3 form-floating">
								<input type="text" class="form-control" name="label" id="label" minlength="2" maxlength="20" required>
								<label for="label">Libellé</label>
							</div>
							<div class="mb-3 form-floating">
								<input type="color" class="form-control" name="color" id="color" required>
								<label for="color">Couleur</label>
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
				<th scope="col">Label</th>
				<th scope="col">Couleur</th>
				<th scope="col">Actions</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
		<?php
			foreach ($tags as $tag) 
			{
				echo '
				<tr>
					<td>'.$tag->getLabel().'</td>
					<td>
						<form class="row row-cols-2-auto g-1" id="newColor" action="'.BASE_URL.'private/tags/action/updateColor" method="post">
							<div class="col-2">
								<input type="color" name="color" value="'.$tag->getColor().'" required>
							</div>
							<div class="col-1">
								<input type="hidden" name="id" value="'.$tag->getId().'">
								<button type="'.($permissionsLogged->getAllowUpdate() ? 'submit' : 'button').'" class="btn btn-sm" name="changeTagColor" id="changeTagColor" data-toggle="tooltip" title="Valider la nouvelle couleur">
									<span class="feather-15 '.($permissionsLogged->getAllowUpdate() ? 'green' : 'disabled').'" data-feather="check"></span>
								</button>
							</div>
						</form>
					</td>
					<td>';

					if ($permissionsLogged->getAllowUpdate())
						echo '<a href="#" class="m-1" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#edit-'.$tag->getId().'" title="Éditer" role="button"><span class="feather-15" data-feather="edit"></span></a>';
					else
						echo '<span class="feather-15 m-1 disabled" data-feather="edit"></span>';

					if ($permissionsLogged->getAllowDelete())
						echo '<a href="'.BASE_URL.'private/tags/action/delete/'.$tag->getId().'" class="m-1 delete-tag" data-toggle="tooltip" title="Supprimer" role="button"><span class="feather-15 red" data-feather="trash-2"></span></a>';
					else
						echo '<span class="feather-15 m-1 disabled" data-feather="trash-2"></span>';

					echo '
					</td>
				</tr>
				';
			}
		?>
		</tbody>
	</table>
</div>

<?php
	
	// Modals pour édition
	foreach($tags as $tag) {
		echo '
		<div class="modal fade" id="edit-'.$tag->getId().'" tabindex="-1" aria-labelledby="blabla" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Modifier le tag</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form action="'.BASE_URL.'private/tags/action/update" method="post">
						<div class="modal-body">
							<div class="mb-3 form-floating">
								<input type="text" class="form-control" name="label" id="label" value="'.$tag->getLabel().'" minlength="2" maxlength="20" required>
									<label for="label">Libellé</label>
							</div>
							<div class="mb-3 form-floating">
								<input type="color" class="form-control" name="color" id="color" value="'.$tag->getColor().'" required>
								<label for="color">Couleur</label>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="id" value="'.$tag->getId().'">
						    <button type="submit" name="submit" class="btn btn-sm btn-light-blue">Mettre à jour</button>
						</div>
					</form>
				</div>
			</div>
		</div>';
	}
?>

<?php
	//echo $pagination -> Display();

	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>