<?php
	ob_start();
?>

<h1>Contenu</h1>

<?= ($permissionsLogged->getAllowAdd()) ? '<a href="'.BASE_URL.'private/content/manage/add" class="btn btn-sm btn-light-blue mt-3 mb-5" role="button">Ajouter du contenu</a>' : null; ?>

<div class="table-responsive">
	<table class="table table-sm align-middle">
		<thead>
			<tr class="d-flex">
				<th class="col-5" scope="col">Titre</th>
				<th class="col-1" scope="col">Catégorie</th>
				<th class="col-1" scope="col">Auteur</th>
				<th class="col-1" scope="col">État</th>
				<th class="col-1" scope="col">Date de création</th>
				<th class="col-1" scope="col">Dernière modification</th>
				<th class="col-2" scope="col">Action</th>
			</tr>
		</thead>
		<tbody class="table-group-divider">
		<?php
			foreach($listContent as $content) {
				echo '
			<tr class="d-flex">
				<td class="col-5">';

					if (count($translations[$content->getContentId()]) != 0)
						echo '<a href="#detail-languages-'.$content->getContentId().'" class="text-reset me-1" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="detail-languages-'.$content->getContentId().'"><span class="" data-feather="chevron-down"></span></a>'.$content->getTitle();
					else
						echo '<span class="ms-4">'.$content->getTitle().'</span>';

				echo '
				</td>
				<td class="col-1">'.$content->getCategory().'</td>
				<td class="col-1">'.$content->getAuthor().'</td>
				<td class="col-1">'.$content->getPublished().'</td>
				<td class="col-1">'.$content->getDateCre().'</td>
				<td class="col-1">'.$content->getDateMod().'</td>
				<td class="col-2">';

					if ($content->getCategory() !== "Système") {
						echo '<a href="'.BASE_URL.'preview/fr/'.$content->getSlug().'" class="m-1" target="_blank" data-toggle="tooltip" title="Prévisualiser" role="button"><span class="feather-15" data-feather="eye"></span></a>';

						if ($permissionsLogged->getAllowUpdate()) {
							echo '
							<a href="#" class="m-1 changer-statut" data-content="'.$content->getId().'" data-toggle="tooltip" title="Publier / Dépublier" role="button"><span class="feather-15" data-feather="refresh-cw"></span></a>
							<a href="'.BASE_URL.'private/content/manage/update/'.$content->getContentId().'" class="m-1" data-toggle="tooltip" title="Editer cette page" role="button"><span class="feather-15" data-feather="edit-2"></span></a>';
						}
						else {
							echo '
							<span class="feather-15 disabled m-1" data-feather="refresh-cw"></span>
							<span class="feather-15 disabled m-1" data-feather="edit-2"></span>';
						}
						
						if ($permissionsLogged->getAllowDelete())
							echo '<a href="'.BASE_URL.'private/content/actions/delete/'.$content->getId().'" class="m-1" data-toggle="tooltip" title="Supprimer" role="button"><span class="feather-15 red" data-feather="trash-2"></span></a>';
						else
							echo '<span class="feather-15 disabled m-1" data-feather="trash-2"></span>';

						echo '
						<a href="#histo-'.$content->getId().'" class="m-1" data-bs-toggle="offcanvas" title="Historique" role="button" aria-controls="histo-'.$content->getId().'"><span class="feather-15 orange" data-feather="archive"></span></a>
						<a href="#comments-'.$content->getId().'" class="m-1" data-bs-toggle="offcanvas" title="Voir les commentaires" role="button" aria-controls="comments-'.$content->getId().'"><span class="feather-15" data-feather="message-square"></span></a>';
					}
					else
						echo '&nbsp;';

					echo '
					</td>
			</tr>';

			if (count($translations[$content->getContentId()]) != 0) {
				echo '
			<tr class="collapse" id="detail-languages-'.$content->getContentId().'">
				<td colspan="8">
					<table class="table table-sm table-info">
						<thead>
							<tr class="d-flex">
								<th class="col-5" scope="col">Titre</th>
								<th class="col-1" scope="col">Langue</th>
								<th class="col-1" scope="col">Auteur</th>
								<th class="col-1" scope="col">Publiée</th>
								<th class="col-1" scope="col">Créée</th>
								<th class="col-1" scope="col">Modifiée</th>
								<th class="col-1" scope="col">Action</th>
							</tr>
						</thead>
						<tbody>';

						foreach ($translations[$content->getContentId()] as $trad) {
					echo '
							<tr class="d-flex">
								<td class="col-5">'.$trad->getTitle().'</td>
								<td class="col-1">'.$trad->getLanguage().'</td>
								<td class="col-1">'.$trad->getAuthor().'</td>
								<td class="col-1">'.$trad->getPublished().'</td>
								<td class="col-1">'.$trad->getDateCre().'</td>
								<td class="col-1">'.$trad->getDateMod().'</td>
								<td class="col-1">';
									if ($permissionsLogged->getAllowUpdate())
										echo '<a href="#" class="m-1 changer-statut" data-content="'.$trad->getId().'" data-toggle="tooltip" title="Publier / Dépublier" role="button"><span class="feather-15" data-feather="refresh-cw"></span></a>';
									else
										echo '<span class="feather-15 disabled m-1" data-feather="refresh-cw"></span>';

									if ($permissionsLogged->getAllowDelete())
										echo '<a href="'.BASE_URL.'private/content/action/delete/'.$trad->getId().'" class="m-1" data-toggle="tooltip" title="Supprimer" role="button"><span class="feather-15 red" data-feather="trash-2"></span></a>';
									else
										echo '<span class="feather-15 disabled m-1" data-feather="trash-2"></span>';

								echo '
								</td>
							</tr>';
						}

						echo '
						</tbody>
					</table>
				</td>
			</tr>';
				}
			}
		?>
			
		</tbody>
	</table>
</div>

<!-- Off Canvas pour les commentaires -->
 <?php

	foreach($listContent as $content) {
		echo '
	<div class="offcanvas offcanvas-end offcanvas-size-xl" tabindex="-1" id="comments-'.$content->getId().'" aria-labelledby="offCanvasComments-'.$content->getId().'">
		<div class="offcanvas-header">
	    	<h5 class="offcanvas-title" id="offCanvasComments-'.$content->getId().'">Commentaires</h5>
	    	<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			<h6>'.$content->getTitle().'</h6>
	    	<div class="table-responsive">
	    		<table class="table table-sm align-middle">
	    			<thead>
	    				<tr class="d-flex">
		    				<th class="col-5">Message</th>
		    				<th class="col-3">Auteur</th>
							<th class="col-2">Date</th>
		    				<th class="col-2">Action</th>
	    				</tr>
	    			</thead>
	    			<tbody>';
	    		
	    			foreach ($comments[$content->getContentId()] as $comment) {
	    				echo '
	    				<tr class="d-flex">
	    					<td class="col-5">'.substr($comment->getText(), 0, 25).'...</td>
	    					<td class="col-3">'.$comment->getNickname().'</td>
							<td class="col-2">'.$comment->getDateComment().'</td>
	    					<td class="col-2">
	    						<a href="#detail-comment-'.$comment->getId().'" class="m-1" data-toggle="tooltip" title="Voir le commentaire complet" data-bs-toggle="modal" role="button"><span class="feather-15" data-feather="eye"></span></a>
								<a href="'.BASE_URL.'private/comment/actions/delete/'.$comment->getId().'" class="m-1" data-toggle="tooltip" title="Supprimer" role="button"><span class="feather-15 red" data-feather="trash-2"></span></a>
	    					</td>
	    				</tr>';
	    			}
	    				
	    		echo '
	    			</tbody>
	    		</table>
	    	</div>
	  	</div>
	</div>';

		// Les modales pour afficher le détails des commentaires
		foreach ($comments[$content->getContentId()] as $comment) {
			echo '
		<div class="modal fade" id="detail-comment-'.$comment->getId().'" tabindex="-1" aria-labelledby="detailComment-'.$comment->getId().'-modal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="detailComment-'.$comment->getId().'-modal">Commentaire de '.$comment->getNickname().'</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p>'.htmlspecialchars_decode($comment->getText()).'</p>
					</div>
				</div>
			</div>
		</div>';
		}
	}
 ?>

<!-- Les off canvas pour l'historique -->
<?php
	foreach($listContent as $content) {
		echo '
	<div class="offcanvas offcanvas-end offcanvas-size-xl" tabindex="-1" id="histo-'.$content->getId().'" aria-labelledby="offCanvasHisto-'.$content->getId().'">
		<div class="offcanvas-header">
	    	<h5 class="offcanvas-title" id="offCanvasHisto-'.$content->getId().'">Historique</h5>
	    	<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			<h6>'.$content->getTitle().'</h6>
	    	<div>
	      		Listing de toutes les modifications
	    	</div>
	    	<div class="table-responsive">
	    		<table class="table table-sm align-middle">
	    			<thead>
	    				<tr class="d-flex">
		    				<th class="col-5">Langue</th>
		    				<th class="col-5">Date modification</th>
		    				<th class="col-2">Action</th>
	    				</tr>
	    			</thead>
	    			<tbody>';
	    		
	    			foreach ($historiques[$content->getContentId()] as $historique) {
	    				switch ($historique->getAction())
	    				{
	    					case 'ADD':
	    						$table = 'table-success ';
	    						break;
	    					case 'UPDATE':
	    						$table = 'table-warning ';
	    						break;
	    					case 'DELETE':
	    						$table = 'table-danger ';
	    						break;
	    					default:
	    						$table = '';
	    						break;
	    				}

	    				echo '
	    				<tr class="'.$table.' d-flex">
	    					<td class="col-5">'.$historique->getLanguage()->getLabel().'</td>
	    					<td class="col-5">'.$historique->getDate().'</td>
	    					<td class="col-2">
	    						<a href="#detail-histo-'.$historique->getId().'" class="m-1" data-toggle="tooltip" title="Voir cette version" data-bs-toggle="modal" role="button"><span class="feather-15" data-feather="eye"></span></a>';

	    						if ($permissionsLogged->getAllowUpdate())
	    							echo '<a href="'.BASE_URL.'private/content/action/recuperation/'.$historique->getId().'" class="m-1" data-toggle="tooltip" title="Récupérer cette version" role="button"><span class="feather-15" data-feather="refresh-cw"></span></a>';
	    						else
	    							echo '<span class="feather-15 disabled m-1" data-feather="refresh-cw"></span>';

	    					echo '
	    					</td>
	    				</tr>
	    				';
	    			}
	    				
	    		echo '
	    			</tbody>
	    		</table>
	    	</div>
	  	</div>
	</div>';

		// Les modales pour afficher le détails des historiques
		foreach ($historiques[$content->getContentId()] as $historique) {
			echo '
		<div class="modal fade" id="detail-histo-'.$historique->getId().'" tabindex="-1" aria-labelledby="detailHisto-'.$historique->getId().'-modal" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="detailHisto-'.$historique->getId().'-modal">'.$historique->getTitle().'</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<h6>'.$historique->getMetaTitle().'</h6>
				        <p>'.$historique->getMetaDescription().'</p>
				        <hr>
				        '.(!is_null($historique->getContent()) ? htmlspecialchars_decode($historique->getContent()) : null).'
				    </div>
				</div>
			</div>
		</div>';
		}
	}
?>

<?php
	echo $pagination -> Display();

	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>