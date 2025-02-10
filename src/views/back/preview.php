<?php
    ob_start();
?>

<div class="alert alert-warning" role="alert">
  <span class="feather-80 me-4" data-feather="alert-triangle"></span><span class="text-uppercase">Attention ! Il s'agit d'une prévisualisation du contenu !!</span>
</div>

<h1><?php echo $content->getTitle(); ?></h1>
<?php
    echo htmlspecialchars_decode($content->getcontent());
?>

<?php
    $content = ob_get_clean();

    require_once __DIR__.'/../../../templates/public/base.php';
?>