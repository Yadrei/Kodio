<?php
    ob_start();
?>

<h1>Médiathèque</h1>
<?php
    if (!isset($folder)) {
        if ($permissionsLogged->getAllowAdd()) {
            echo '
            <button type="button" class="btn btn-light-blue btn-sm my-3" data-bs-toggle="modal" data-bs-target="#addImage-modal">Ajouter une image</button>
            <div class="modal fade" id="addImage-modal" tabindex="-1" aria-labelledby="image-modal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="image-modal">Ajouter une image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addImage-form" action="'.BASE_URL.'private/library/addImage" method="post" enctype="multipart/form-data">';

                            echo CSRF::Field();

                            echo '
                            <div class="modal-body">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="folder" id="folder" aria-describedby="folderHelp">
                                    <label for="folder">Dossier</label>
                                    <div id="folderHelp" class="form-text">
                                      Laisser vide pour envoyer les images à la racine du dossier Medias
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="images" class="form-label">Choisir les images à ajouter</label>
                                  <input type="file" class="form-control" name="images[]" id="images" multiple accept="image/*">
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
    }
    else {
        echo '
        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'8\' height=\'8\'%3E%3Cpath d=\'M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z\' fill=\'%236c757d\'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="'.BASE_URL.'private/library">Médiathèque</a></li>
                <li class="breadcrumb-item active" aria-current="page">'.$folder.'</li>
            </ol>
        </nav>';
    }
?>

<div class="row row-cols-6">
    <?php
        if (is_dir($initialFolder)) {
        
        if ($handle = opendir($initialFolder)) {

            while (($entry = readdir($handle)) !== false) {

                if ($entry != "." && $entry != "..") {

                    $path = $initialFolder . '/' . $entry;

                    if (is_file($path)) {

                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        
                        if (in_array($extension, $allowedExtensions)) {
                            echo '
                            <div class="col">
                                <figure class="figure">
                                    <img src="'.$urlImage.$entry.'" class="figure-img img-fluid">
                                    <figcaption class="figure-caption">
                                        <button type="button" class="btn btn-sm btn-url-img">     
                                            <span class="me-2" data-feather="copy"></span>Copier l\'URL
                                        </button>
                                        <a href="'.BASE_URL.'private/library/deleteImage/'.$entry.'" class="btn btn-sm btn-del-img">     
                                            <span class="me-2 red" data-feather="x"></span>
                                        </a>
                                    </figcaption>
                                </figure>
                            </div>';
                        }
                    }

                    if (is_dir($path)) {
                        echo '
                        <div class="col">
                            <div class="text-center">
                                <a href="'.BASE_URL.'private/library/'.$entry.'">
                                    <span class="feather-90" data-feather="folder"></span><p>'.$entry.'</p>
                                </a>
                            </div>
                        </div>';
                    }
                }
            }

            closedir($handle);
        }
    } 
    ?>
</div>

<?php
    $content = ob_get_clean();

    require_once __DIR__.'/../../../templates/admin/base.php';
?>