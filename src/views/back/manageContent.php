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
	<ul class="nav nav-tabs border border-0" id="contentLangTabs" role="tablist">
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
<form class="mb-3" action="<?php echo BASE_URL ?>private/content/action/save" method="post">
	<?php echo CSRF::Field(); ?>
	<input type="hidden" name="author" id="author" value="<?php echo $_SESSION['id'] ?>">
	<?php
		echo ($action === "update") ? '<input type="hidden" name="contentId" value="'.$contentId.'">' : null;
	?>
	<div class="row">
		<div class="col-8">
			<div class="tab-content" id="tabForms">
			<?php
				foreach ($langues as $langue) {

					// Détermine l'onglet actif (AKA sur la langue principale => FR)
					$main = ($langue->getClef() === "FR") ? " show active" : null;
					$required = ($langue->getClef() === "FR") ? "required" : null;

					// Recherche des données correspondantes à la langue de l'onglet qu'on génère
					$data = null;
					$checked = null;
					$contentPublished = null;
					$schemaType = 'WebPage';

					if (isset($contents)) {
						foreach ($contents as $contentLang) {
							if ($contentLang->getLanguage() === $langue->getClef()) {
								$checked = $contentLang->getPublished();
								$contentPublished = $contentLang->getPublished();
								$data = $contentLang;
								break; // Une fois trouvées, on sort de la boucle
							}
						}

						if (!empty($seo[$data->getId()])) {
							$seoId = $seo[$data->getId()]->getId();
							$metaTitle = $seo[$data->getId()]->getMetaTitle();
							$metaDescription = $seo[$data->getId()]->getMetaDescription();
							$robotsIndex = $seo[$data->getId()]->getRobotsIndex();
							$robotsFollow = $seo[$data->getId()]->getRobotsFollow();
							$title = $seo[$data->getId()]->getTitle();
							$description = $seo[$data->getId()]->getDescription();
							$schemaType = $seo[$data->getId()]->getSchemaType();
							$schemaDescription = $seo[$data->getId()]->getSchemaDescription();
						}
					}

					echo '
					<div class="tab-pane fade'.$main.' p-3 border rounded-end rounded-bottom" id="'.$langue->getClef().'-pane" role="tabpanel" aria-labelledby="'.$langue->getClef().'-tab" tabindex="0">
						<div class="mb-3">
							<label class="form-label" for="title-'.$langue->getClef().'">Titre</label>
							<input type="text" class="form-control" name="title['.$langue->getClef().']" id="title-'.$langue->getClef().'" value="'.($data ? htmlspecialchars_decode($data->getTitle()) : '').'" minlength="4" maxlength="100" '.$required.'>
						</div>
						<div class="mb-3">
							<label class="form-label" for="content-'.$langue->getClef().'">Texte</label>
							<textarea class="form-control editor" name="content['.$langue->getClef().']" id="content-'.$langue->getClef().'">'.($data ? htmlspecialchars_decode($data->getContent()) : '').'</textarea>
						</div>
						<div class="form-check form-switch">
							<label class="form-check-label" for="publication-'.$langue->getClef().'">Publier la page ?</label>
							<input type="checkbox" class="form-check-input" role="switch" name="publication['.$langue->getClef().']" id="publication-'.$langue->getClef().'" aria-describedby="publicationHelp" '.($contentPublished ? 'checked' : '').'>
							<div class="form-text" id="publicationHelp">
								Publier la page signifie qu\'elle sera visible sur le site par les visiteurs. Si le bouton n\'est pas activé la page reste privée et personne ne peut la voir.
							</div>
							<input type="hidden" name="language['.$langue->getClef().']" id="language-'.$langue->getClef().'" value="'.$langue->getClef().'">
						</div>
						<div class="bg-primary-subtle text-primary-emphasis rounded p-2 mt-3">
							<h2 class="fs-4">Visibilité sur Google et les réseaux sociaux</h2>
							<p>Remplissez ces champs pour que votre page soit bien présentée dans les résultats de recherche et lors des partages sur les réseaux sociaux. C\'est important pour apparaitre naturellement dans les premiers résultats de recherche.</p>
							<div class="mb-3">
								<label class="form-label" for="metaTitle-'.$langue->getClef().'">Meta titre</label>
								<input type="text" class="form-control" name="metaTitle['.$langue->getClef().']" id="metaTitle-'.$langue->getClef().'" value="'.htmlspecialchars_decode($metaTitle ?? '').'" minlength="10" maxlength="50" aria-describedby="metaTitleHelp" '.$required.'>
								<div class="form-text" id="metaTitleHelp">
									Ceci est important pour le référencement naturel de la page sur les moteurs de recherches. Soyez bref et concis, avec des mots-clés principaux si possible. Cela doit faire maximum 50 caractères
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label" for="metaDescription-'.$langue->getClef().'">Meta description</label>
								<textarea class="form-control" name="metaDescription['.$langue->getClef().']" id="metaDescription-'.$langue->getClef().'" rows="2" minlength="10" maxlength="200" aria-describedby="metaDescriptionHelp" '.$required.'>'.htmlspecialchars_decode($metaDescription ?? '').'</textarea>
								<div class="form-text" id="metaDescriptionHelp">
									Ceci est important pour le référencement naturel de la page sur les moteurs de recherche. Il s\'agit d\'un court résumé du contenu de la page, soyez concis. Cela doit faire maximum 200 caractères, environ 150-160 étant le mieux pour s\'assurer une bonne lisibilité sur tous les moteurs de recherche.
								</div>
							</div>
							<div class="row">
								<div class="col">
									<fieldset>
										<legend>Robots</legend>
										<div class="form-check form-switch">
											<label class="form-check-label" for="robotsIndex-'.$langue->getClef().'">Index</label>
											<input type="checkbox" class="form-check-input" role="switch" name="robotsIndex['.$langue->getClef().']" id="robotsIndex-'.$langue->getClef().'" aria-describedby="robotsIndexHelp" '.(($robotsIndex ?? 0) ? 'checked' : '').'>
											<div class="form-text" id="robotsIndexHelp">
												Autoriser ou non Google et les autres moteurs à afficher cette page dans les résultats de recherche.
											</div>
											<input type="hidden" name="language['.$langue->getClef().']" id="language-'.$langue->getClef().'" value="'.$langue->getClef().'">
										</div>
										<div class="form-check form-switch">
											<label class="form-check-label" for="robotsFollow-'.$langue->getClef().'">Follow</label>
											<input type="checkbox" class="form-check-input" role="switch" name="robotsFollow['.$langue->getClef().']" id="robotsFollow-'.$langue->getClef().'" aria-describedby="robotsFollowHelp" '.(($robotsFollow ?? 0) ? 'checked' : '').'>
											<div class="form-text" id="robotsFollowHelp">
												Autoriser ou non les moteurs à suivre les liens présents sur cette page vers d’autres pages.
											</div>
											<input type="hidden" name="language['.$langue->getClef().']" id="language-'.$langue->getClef().'" value="'.$langue->getClef().'">
										</div>
									</fieldset>
								</div>
								<div class="col">
									<fieldset>
										<legend>Réseaux sociaux</legend>
										<div class="mb-3">
											<label class="form-label" for="ogTitle['.$langue->getClef().']">Titre</label>
											<input type="text" class="form-control" name="ogTitle['.$langue->getClef().']" id="ogTitle['.$langue->getClef().']" value="'.htmlspecialchars_decode($title ?? '').'">
										</div>
										<div class="mb-3">
											<label class="form-label" for="ogDescription['.$langue->getClef().']">Description</label>
											<textarea class="form-control" name="ogDescription['.$langue->getClef().']" id="ogDescription['.$langue->getClef().']">'.htmlspecialchars_decode($description ?? '').'</textarea>
										</div>
									</fieldset>
								</div>
							</div>
							<div>
								<fieldset>
									<legend>Schéma</legend>
									<div class="mb-3">
										<label class="form-label" for="schemaType['.$langue->getClef().']">Type</label>
										<select class="form-select" name="schemaType['.$langue->getClef().']" id="schemaType['.$langue->getClef().']">
											<optgroup label="Pages">
												<option value="WebPage"' . ($schemaType === 'WebPage' ? ' selected' : '') . '>Page web</option>
												<option value="FAQPage"' . ($schemaType === 'FAQPage' ? ' selected' : '') . '>Page de Foire Aux Questions</option>
												<option value="ContactPage"' . ($schemaType === 'ContactPage' ? ' selected' : '') . '>Page de contact</option>
											</optgroup>
											<optgroup label="Editorial">
												<option value="Article"' . ($schemaType === 'Article' ? ' selected' : '') . '>Article</option>
												<option value="NewsArticle"' . ($schemaType === 'NewsArticle' ? ' selected' : '') . '>News d\'actualité</option>
												<option value="BlogPosting"' . ($schemaType === 'BlogPosting' ? ' selected' : '') . '>Article de blog</option>
											</optgroup>
											<optgroup label="Guides">
												<option value="HowTo"' . ($schemaType === 'HowTo' ? ' selected' : '') . '>Guide pas-à-pas</option>
												<option value="ItemList"' . ($schemaType === 'ItemList' ? ' selected' : '') . '>Liste structurée</option>
											</optgroup>
										</select>
									</div>
									<div class="mb-3">
										<label class="form-label" for="schemaDescription['.$langue->getClef().']">Description</label>
										<textarea class="form-control" name="schemaDescription['.$langue->getClef().']" id="schemaDescription['.$langue->getClef().']">'.htmlspecialchars_decode($schemaDescription ?? '').'</textarea>
									</div>
								</fieldset>
								<div class="form-text">
									Permet d’ajouter des informations cachées pour Google (et autres moteurs), afin d’expliquer clairement le contenu de la page. Ça aide à améliorer l’affichage dans les résultats de recherche (ex. étoiles, avis, prix, événements, etc.).
								</div>';

								if ($action === "update")
									echo '<input type="hidden" name="seoId['.$langue->getClef().']" value="'.($seoId ?? '').'">';

							echo '
							</div>
						</div>
					</div>';

					if ($action === "update" && !is_null($data)) 
						echo '<input type="hidden" name="id['.$langue->getClef().']" value="'.$data->getId().'">';
				}
			?>
			</div>
		</div>
		<div class="col-4">
			<div class="border rounded p-2">
				<div class="mb-3">
					<label class="form-label" for="category">Catégorie</label>
					<select class="form-select" name="category" id="category" required>';
					<?php
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
					?>
					</select>
				</div>
				<div class="mb-3">
					<label class="form-label" for="image" >Image d'entête</label>
					<input type="file" class="form-control" name="image" id="image" accept="image/*">
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
				<!--
				<div class="mb-3">
					<label class="form-label" for="datePublication">Date de publication</label>
					<input type="datetime-local" class="form-control w-25" name="datePublication" id="datePublication">
				</div>
				-->
				<input type="hidden" class="form-control w-25" name="datePublication" id="datePublication" value="">
			</div>
			<button type="submit" class="btn btn-light-blue mt-3"><?php echo ($action === "add") ? "Enregistrer" : "Mettre à jour"; ?></button>
		</div>
	</div>
</form>

<?php
	$content = ob_get_clean();

	require_once __DIR__.'/../../../templates/admin/base.php';
?>