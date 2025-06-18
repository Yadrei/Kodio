<?php
	ob_start();
?>

<h1>Étiquettes</h1>

<?php
	if ($permissionsLogged->getAllowAdd()) {
		echo '
		<button type="button" class="btn btn-light-blue btn-sm my-3" data-bs-toggle="modal" data-bs-target="#addTag-modal">Ajouter une étiquette</button>
		<div class="modal fade" id="addTag-modal" tabindex="-1" aria-labelledby="add-modal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="add-modal">Ajouter une étiquette</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="addTag-form">
						<div class="modal-body">
							<div class="mb-3 form-floating">
								<input type="text" class="form-control" name="label" id="addLabel" minlength="2" maxlength="20" required>
								<label for="addLabel">Libellé</label>
							</div>
							<div class="mb-3 form-floating">
								<input type="color" class="form-control" name="color" id="addColor">
								<label for="addColor">Couleur</label>
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
				<th scope="col">Couleur texte</th>
				<th scope="col">Couleur background</th>
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
						<form class="row row-cols-2-auto g-1" id="newTextColor" action="'.BASE_URL.'private/tags/action/updateTextColor" method="post">
							<div class="col-2">
								<input type="color" name="textColor" value="'.$tag->getTextColor().'">
							</div>
							<div class="col-1">
								<input type="hidden" name="id" value="'.$tag->getId().'">
								<button type="'.($permissionsLogged->getAllowUpdate() ? 'submit' : 'button').'" class="btn btn-sm" name="changeTextColor" id="changeTextColor" data-toggle="tooltip" title="Valider la nouvelle couleur">
									<span class="feather-15 '.($permissionsLogged->getAllowUpdate() ? 'green' : 'disabled').'" data-feather="check"></span>
								</button>
							</div>
						</form>
					</td>
					<td>
						<form class="row row-cols-2-auto g-1" id="newColor" action="'.BASE_URL.'private/tags/action/updateColor" method="post">
							<div class="col-2">
								<input type="color" name="color" value="'.$tag->getTextColor().'">
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
		<div class="modal fade" id="edit-'.$tag->getId().'" tabindex="-1" aria-labelledby="update-modal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="update-modal">Modifier le tag</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form action="'.BASE_URL.'private/tags/action/update" method="post">
						<div class="modal-body">
							<div class="mb-3 form-floating">
								<input type="text" class="form-control" name="label" id="updateLabel" value="'.$tag->getLabel().'" minlength="2" maxlength="20" required>
									<label for="updateLabel">Libellé</label>
							</div>
							<div class="mb-3 form-floating">
								<input type="color" class="form-control" name="color" id="updateColor" value="'.$tag->getTextColor().'">
								<label for="updateColor">Couleur</label>
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