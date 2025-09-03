<?php
	ob_start();
?>

<h1>Menu</h1>

<?php
	if ($permissionsLogged->getAllowAdd()) {
		echo '
		<button type="button" class="btn btn-light-blue btn-sm my-3" data-bs-toggle="modal" data-bs-target="#addMenu">Ajouter un élément de menu</button>
		<div class="modal fade" id="addMenu" tabindex="-1" aria-labelledby="addMenu-modal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addMenu-modal">Ajouter un menu</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<form id="addMenu-form">';

						echo CSRF::Field();

						echo '
						<div class="modal-body">
							<div class="mb-3 form-floating">
								<select class="form-select" name="parentMenu" id="parentMenu" aria-describedby="parentMenuHelp">
									<option value="0" selected>Pas de parent</option>';

								foreach($langues as $langue) {
									echo '
									<optgroup label="'.$langue->getLabel().'">';

									foreach($mainMenu as $menu) {
										if ($menu->getLanguage() == $langue->getClef()) {
											echo '
											<option value="'.$menu->getId().'">'.$menu->getLabel().'</option>';
										}
									}

									echo '</optgroup>';
								}

								echo '
								</select>
								<label for="parentMenu">Parent</label>
								<div id="parentMenuHelp" class="form-text">
								  Laissez tel quel si vous ne voulez pas faire de liaison avec un menu parent
								</div>
							</div>
							<div class="mb-3 form-floating">
								<input type="text" class="form-control" name="label" id="label" minlength="4" maxlength="20" required>
								<label for="label">Libellé</label>
							</div>
							<div class="mb-3 form-floating">
								<select class="form-select" name="language" id="language" required>
									<option value="" selected disabled>Choisir la langue</option>';

									foreach($langues as $langue) {
										echo '
										<option value="'.$langue->getClef().'">'.$langue->getLabel().'</option>';
									}

								echo '
								</select>
								<label for="language">Langue</label>
							</div>
							<div class="mb-3 form-floating">
								<select class="form-select" name="content" id="content" aria-describedby="contentMenuHelp">
									<option value="0" selected>Pas de contenu</option>';

								foreach($langues as $langue) {
									echo '
									<optgroup label="'.$langue->getLabel().'">';

									foreach($contentMenu as $content) {
										if ($content->getLanguage() == $langue->getClef())  {
											echo '
										<option value="'.$content->getId().'">'.$content->getTitle().'</option>';
										}
									}

									echo '</optgroup>';
								}

								echo '
								</select>
								<label for="content">Contenu à lier</label>
								<div id="contentMenuHelp" class="form-text">
								  Laissez tel quel si vous voulez en faire un menu parent
								</div>
							</div>
							<div class="row">
								<div class="col-sm-2">
									<div class="mb-3 form-floating">
										<input type="text" class="form-control" name="ordre" id="ordre" placeholder="0" aria-label="First name" aria-describedby="ordreMenuHelp">
										<label for="ordre">Ordre</label>
									</div>
							  	</div>
							  	<div class="col-sm-10">
							    	<div id="ordreMenuHelp" class="form-text">
							    		Chiffre uniquement. Pour déterminer l\'ordre d\'affiche, plus le chiffre est petit et plus le menu s\'affichera au début. 0 Si vous ne souhaitez pas afficher le menu
									</div>
							  	</div>
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

<nav>
	<ul class="nav nav-tabs" role="tablist">
	<?php
		foreach ($langues as $langue) {
			if ($langue->getClef() === "FR") {
				echo '
			<li class="nav-item" role="presentation">
				<a href="#" class="nav-link active" id="'.$langue->getClef().'-tab" data-bs-toggle="tab" data-bs-target="#'.$langue->getClef().'-pane" role="tab" aria-controls="'.$langue->getClef().'-pane" aria-selected="true">'.$langue->getLabel().'</a>
			</li>';
			}
			else {
				echo '
			<li class="nav-item" role="presentation">
				<a href="#" class="nav-link" id="'.$langue->getClef().'-tab" data-bs-toggle="tab" data-bs-target="#'.$langue->getClef().'-pane" role="tab" aria-controls="'.$langue->getClef().'-pane" aria-selected="true">'.$langue->getLabel().'</a>
			</li>';
			}
			
		}
	?>
	</ul>
</nav>
<div class="tab-content border border-top-0" id="tabForms">
	<?php
		foreach ($langues as $langue) {

			// Détermine l'onglet actif (AKA sur la langue principale => FR)
			$main = ($langue->getClef() === "FR") ? " show active" : null;
			$required = ($langue->getClef() === "FR") ? "required" : null;

			// Récupération des données correspondantes à la langue de l'onglet qu'on génère
			$datas = [];

			if (!empty($menus[$langue->getClef()]))
				$datas = $menus[$langue->getClef()];

			echo '
			<div class="tab-pane fade'.$main.' p-3" id="'.$langue->getClef().'-pane" role="tabpanel" aria-labelledby="'.$langue->getClef().'-tab" tabindex="0">';

			if (!empty($datas)) {
				echo '
				<div class="table-responsive">
					<table class="table table-sm align-middle">
						<thead>
							<tr class="d-flex">
								<th class="col-2" scope="col">Libellé</th>
								<th class="col-2" scope="col">Parent</th>
								<th class="col-5" scope="col">Contenu lié</th>
								<th class="col-1" scope="col">Publié</th>
								<th class="col-1" scope="col">Ordre</th>
								<th class="col-1" scope="col">Action</th>
							</tr>
						</thead>
						<tbody class="table-group-divider">';

						foreach($datas as $data) {
							echo '
							<tr class="d-flex">
								<td class="col-2">'.$data->getLabel().'</td>
								<td class="col-2">'.(($data->getParent()) ? $data->getParent()->getLabel() : '&nbsp;').'</td>
								<td class="col-5">'.(($data->getContent()) ? $data->getContent()->getTitle() : '&nbsp;').'</td>
								<td class="col-1">'.(($data->getOrdre() == 0) ? "Non" : "Oui").'</td>
								<td class="col-1">'.$data->getOrdre().'</td>
								<td class="col-1">';

								if ($permissionsLogged->getAllowUpdate()) {
									echo '
									<a href="#" class="m-1" data-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#edit-'.$data->getId().'" title="Editer ce menu" role="button"><span class="feather-15" data-feather="edit-2"></span></a>';
								}
								else {
									echo '
									<span class="feather-15 disabled m-1" data-feather="edit-2"></span>';
								}

								if ($permissionsLogged->getAllowDelete())
									echo '<a href="'.BASE_URL.'private/menu/action/delete/'.$data->getId().'" class="m-1" data-toggle="tooltip" title="Supprimer" role="button"><span class="feather-15 red" data-feather="trash-2"></span></a>';
								else
									echo '<span class="feather-15 disabled m-1" data-feather="trash-2"></span>';

								echo '
								</td>
							</tr>';
						}

					echo '
						<tbody>
					</table>
				</div>';

				// Modals pour édition
				foreach($datas as $data) {
				echo '
				<div class="modal fade" id="edit-'.$data->getId().'" tabindex="-1" aria-labelledby="editing-modal" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editing-modal">'.$data->getLabel().'</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<form action="'.BASE_URL.'private/menu/action/update" method="post">';

								echo CSRF::Field();

								echo '
								<div class="modal-body">
									<div class="mb-3 form-floating">
										<select class="form-select" name="parent" id="parent" aria-describedby="parentHelp">';

										if ($langue->getClef() == $data->getLanguage()) {
											if ($data->getParent()) {
												echo '<option value="0">Pas de parent</option>';

												foreach($mainMenu as $menu) {
													if ($menu->getLanguage() == $data->getLanguage())
														if ($menu->getId() == $data->getParent()->getId())
															echo '<option value="'.$menu->getId().'" selected>'.$menu->getLabel().'</option>';
														else
															echo '<option value="'.$menu->getId().'">'.$menu->getLabel().'</option>';
												}

											}
											else {
												echo '<option value="0" selected>Pas de parent</option>';

												foreach($mainMenu as $menu)
													if ($menu->getLanguage() == $data->getLanguage())
														echo '<option value="'.$menu->getId().'">'.$menu->getLabel().'</option>';
											}
										}

										echo '
										</select>
										<label for="parent">Parent</label>
										<div id="parentHelp" class="form-text">
										  Laissez tel quel si vous ne voulez pas faire de liaison avec un menu parent
										</div>
									</div>
									<div class="mb-3 form-floating">
										<input type="text" class="form-control" name="label" id="label" minlength="4" maxlength="20" value="'.$data->getLabel().'" required>
										<label for="label">Libellé</label>
									</div>
									<div class="mb-3 form-floating">
										<select class="form-select" name="language" id="language" required>
											<option value="DEFAULT" selected>Choisir la langue</option>';

											foreach($langues as $langue) {
												if ($langue->getClef() == $data->getLanguage())
													echo '
												<option value="'.$langue->getClef().'" selected>'.$langue->getLabel().'</option>';
												else
													echo '
												<option value="'.$langue->getClef().'">'.$langue->getLabel().'</option>';
											}

										echo '
										</select>
										<label for="language">Langue</label>
									</div>
									<div class="mb-3 form-floating">
										<select class="form-select" name="content" id="content" aria-describedby="contentMenuHelp">';

											if ($langue->getClef() == $data->getLanguage()) {
												if ($data->getContent()) {
													echo '<option value="0">Pas de contenu</option>';

													foreach($contentMenu as $content) {
														if ($content->getLanguage() == $data->getLanguage())
															if ($content->getId() == $data->getContent()->getId())
																echo '<option value="'.$content->getId().'" selected>'.$content->getTitle().'</option>';
															else
																echo '<option value="'.$content->getId().'">'.$content->getTitle().'</option>';
													}

												}
												else {
													echo '<option value="0" selected>Pas de contenu</option>';

													foreach($contentMenu as $content)
														if ($content->getLanguage() == $data->getLanguage())
															echo '<option value="'.$content->getId().'">'.$content->getTitle().'</option>';
												}
											}

										echo '
										</select>
										<label for="content">Contenu à lier</label>
										<div id="contentMenuHelp" class="form-text">
										  Laissez tel quel si vous voulez en faire un menu parent
										</div>
									</div>
									<div class="row">
										<div class="col-sm-2">
											<div class="mb-3 form-floating">
												<input type="text" class="form-control" name="ordre" id="ordre" placeholder="0" aria-label="First name" value="'.$data->getOrdre().'" aria-describedby="ordreMenuHelp">
												<label for="ordre">Ordre</label>
											</div>
									  	</div>
									  	<div class="col-sm-10">
									    	<div id="ordreMenuHelp" class="form-text">
									    		Chiffre uniquement. Pour déterminer l\'ordre d\'affiche, plus le chiffre est petit et plus le menu s\'affichera au début. 0 Si vous ne souhaitez pas afficher le menu
											</div>
									  	</div>
									</div>
								</div>
								<div class="modal-footer">
									<input type="hidden" name="id" value="'.$data->getId().'">
					        		<button type="submit" name="submit" class="btn btn-sm btn-light-blue">Mettre à jour</button>
								</div>
							</form>
						</div>
					</div>
				</div>';
				}
			}
			else {
				echo '
				<div class="alert alert-warning" role="alert">
				  <span class="feather-20 me-4" data-feather="alert-triangle"></span><span>'.NO_MENU.'</span>
				</div>';
			}

			echo '
		</div>';
		}
	?>
</div>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>