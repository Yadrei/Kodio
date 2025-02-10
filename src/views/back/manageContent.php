<?php
	ob_start();

	switch ($action) {
		case 'add':
			echo '<h1>Ajouter du contenu</h1>';
			break;

		case 'update':
			echo '<h1>Modifier le contenu</h1>';
			break;
		
		default:
			throw new Exception("Erreur !");
			break;
	}
?>

<nav>
	<ul class="nav nav-tabs" role="tablist">
	<?php
		foreach ($langues as $langue) {
			if ($langue->getClef() === "FR") {
				echo '
			<li class="nav-item" role="presentation">
				<a href="#" class="nav-link active" id="'.$langue->getClef().'-tab" data-bs-toggle="tab" data-bs-target="#'.$langue->getClef().'-pane" type="button" role="tab" aria-controls="'.$langue->getClef().'-pane" aria-selected="true">'.$langue->getLabel().'</a>
			</li>';
			}
			else {
				echo '
			<li class="nav-item" role="presentation">
				<a href="#" class="nav-link" id="'.$langue->getClef().'-tab" data-bs-toggle="tab" data-bs-target="#'.$langue->getClef().'-pane" type="button" role="tab" aria-controls="'.$langue->getClef().'-pane" aria-selected="true">'.$langue->getLabel().'</a>
			</li>';
			}
			
		}
	?>
	</ul>
</nav>
<form class="mb-3" action="<?php echo BASE_URL ?>private/content/action/save" method="post" enctype="multipart/form-data">
	<input type="hidden" name="author" id="author" value="<?php echo $_SESSION['id'] ?>">
	<?php
		echo ($action === "update") ? '<input type="hidden" name="contentId" value="'.$contentId.'">' : null;
	?>
	<div class="tab-content border border-top-0 rounded-bottom" id="tabForms">
	<?php
		foreach ($langues as $langue) {

			// Détermine l'onglet actif (AKA sur la langue principale => FR)
			$main = ($langue->getClef() === "FR") ? " show active" : null;
			$required = ($langue->getClef() === "FR") ? "required" : null;

			// Recherche des données correspondantes à la langue de l'onglet qu'on génère
			$data = null;
			$checked = null;

			if (isset($contents)) {
				foreach ($contents as $contentLang) {
	                if ($contentLang->getLanguage() === $langue->getClef()) {
	                	$checked = $contentLang->getPublished();
	                    $data = $contentLang;
	                    break; // Une fois trouvées, on sort de la boucle
	                }
	            }
			}

			echo '
			<div class="tab-pane fade'.$main.' p-3" id="'.$langue->getClef().'-pane" role="tabpanel" aria-labelledby="'.$langue->getClef().'-tab" tabindex="0">
				<div class="mb-3">
					<label class="form-label" for="title-'.$langue->getClef().'">Titre</label>
					<input type="text" class="form-control" name="title['.$langue->getClef().']" id="title-'.$langue->getClef().'" value="'.($data ? htmlspecialchars_decode($data->getTitle()) : '').'" minlength="4" maxlength="100" '.$required.'>
				</div>
				<div class="mb-3">
					<label class="form-label" for="cat-'.$langue->getClef().'">Catégorie</label>
					<select class="form-select" name="cat['.$langue->getClef().']" id="cat-'.$langue->getClef().'" '.$required.'>';

						if ($action == "add") {
							echo '
							<option value="DEFAULT" selected>Choisissez une catégorie</option>';

							foreach ($categories as $cat)
									echo '
								<option value="'.$cat->getClef().'">'.$cat->getLabel().'</option>';
						}
						else {
							if ($data) {
								foreach ($categories as $cat)
								{
									if ($cat->getClef() == $data->getCategory())
										echo '<option value="'.$cat->getClef().'" selected>'.$cat->getLabel().'</option>';
									else
										echo '<option value="'.$cat->getClef().'">'.$cat->getLabel().'</option>';
								}
							}
							else {
								echo '
								<option value="DEFAULT" selected>Choisissez une catégorie</option>';

								foreach ($categories as $cat)
										echo '
									<option value="'.$cat->getClef().'">'.$cat->getLabel().'</option>';
							}
						}
							
					echo '
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label" for="content-'.$langue->getClef().'">Texte</label>
					<textarea class="form-control editor" name="content['.$langue->getClef().']" id="content-'.$langue->getClef().'">'.($data ? htmlspecialchars_decode($data->getContent()) : '').'</textarea>
				</div>
				<div class="mb-3">
					<label class="form-label" for="metaTitle-'.$langue->getClef().'">Meta titre</label>
					<input type="text" class="form-control" name="metaTitle['.$langue->getClef().']" id="metaTitle-'.$langue->getClef().'" value="'.($data ? htmlspecialchars_decode($data->getMetaTitle()) : '').'" minlength="10" maxlength="50" aria-describedby="metaTitleHelp" '.$required.'>
					<div id="metaTitleHelp" class="form-text">
					  Ceci est important pour le référencement naturel de la page sur les moteurs de recherches. Soyez bref et concis, avec des mots-clés principaux si possible. Cela doit faire maximum 50 caractères
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label" for="metaDescription-'.$langue->getClef().'">Meta description</label>
					<textarea class="form-control" name="metaDescription['.$langue->getClef().']" id="metaDescription-'.$langue->getClef().'" rows="2" minlength="10" maxlength="200" aria-describedby="metaDescriptionHelp" '.$required.'>'.($data ? htmlspecialchars_decode($data->getMetaDescription()) : '').'</textarea>
					<div id="metaDescriptionHelp" class="form-text">
					  Ceci est important pour le référencement naturel de la page sur les moteurs de recherche. Il s\'agit d\'un court résumé du contenu de la page, soyez concis. Cela doit faire maximum 200 caractères, environ 150-160 étant le mieux pour s\'assurer une bonne lisibilité sur tous les moteurs de recherche.
					</div>
				</div>
				<div class="form-check form-switch">
					<label class="form-check-label" for="publication-'.$langue->getClef().'">Publier la page ?</label>
					<input type="checkbox" class="form-check-input" role="switch" name="publication['.$langue->getClef().']" id="publication-'.$langue->getClef().'" aria-describedby="publicationHelp" '.($checked ? 'checked' : '').'>
					<div class="form-text" id="metaDescriptionHelp">
						Publier la page signifie qu\'elle sera visible sur le site par les visiteurs. Si le bouton n\'est pas activé la page reste privée et personne ne peut la voir.
					</div>
					<input type="hidden" name="language['.$langue->getClef().']" id="language-'.$langue->getClef().'" value="'.$langue->getClef().'">
				</div>
			</div>';

			if ($action === "update" && !is_null($data)) {
				echo '<input type="hidden" name="id['.$langue->getClef().']" value="'.$data->getId().'">';
			}
		}
	?>
	</div>
    <div class="border rounded mt-3 p-2">
        <div class="mb-3">
            <label class="form-label" for="images" >Image d'entête</label>
            <input type="file" class="form-control" name="images[]" id="images" accept="image/*">
        </div>
        <div class="mb-3">
            <p class="form-label">Étiquettes</p>
            <div class="row row-cols-10 ps-3">
                <?php

                    foreach($tags as $tag) {
                        $isChecked = false;
                        $value = 0;

                        if (isset($relatedTags) && !is_null($relatedTags)) {

                            // Parcours des éléments du premier tableau
                            foreach ($relatedTags as $relatedTag) {
                               
                                if ($relatedTag->getFkTag() == $tag->getId()) {
                                    $isChecked = true;
                                    $value = 1;

                                    break;
                                }
                            }
                        }

                        echo '
                        <div class="col-1 form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="tag['.$tag->getLabel().']" id="tag-'.$langue->getClef().'-'.$tag->getLabel().'" value="'.$tag->getId().'" '.($isChecked ? 'checked' : '').'>
                            <input type="hidden" class="check-hidden" name="checkHidden['.$tag->getLabel().']" value="'.$value.'">
                            <label class="form-check-label" for="tag-'.$langue->getClef().'-'.$tag->getLabel().'">'.$tag->getLabel().'</label>
                        </div>';
                    }
                ?>

            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="datePublication">Date de publication</label>
            <input type="datetime-local" class="form-control w-25" name="datePublication" id="datePublication-'.$langue->getClef().'">
        </div>
    </div>
	<button type="submit" class="btn btn-light-blue mt-3"><?php echo ($action === "add") ? "Enregistrer" : "Mettre à jour"; ?></button>
</form>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>