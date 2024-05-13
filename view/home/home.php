<?php
$categories = $result['data']['categories'];
?>

<h1>BIENVENUE SUR LE FORUM</h1>

<p>Retrouvez vos sujets de discussion favoris, un forum plein de diversité et politiquement correct! N'hésitez pas à
    contacter notre équipe de modération pour tout soucis.</p>

<?php if (!isset($_SESSION['user'])) { ?>
    <p>
        <a class="btn btn-dark m-1" href="index.php?ctrl=security&action=login">Se connecter</a>
        <a class="btn btn-dark m-1" href="index.php?ctrl=security&action=register">S'inscrire</a>
    </p>
<?php } ?>
<div class="row row-cols-3">
    <?php foreach ($categories as $category) { ?>
        <div class="card m-2" style='width: fit-content'>
            <div class="card-body">
                <h3 class="card-title">
                    <?= $category->getName() ?>
                </h3>

                <a href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>"
                    class="btn btn-outline-dark">Voir les topics</a>
            </div>
        </div>
    <?php } ?>
</div>