<?php
    $categories = $result["data"]['categories']; 
?>

<h1>Liste des catégories</h1>

<?php
foreach($categories as $category ){ ?>
    <div class="d-flex col-md-auto fs-2">
        <p><a class='link-dark link-offset-2 link-underline link-underline-opacity-0 link-underline-opacity-100-hover'
                href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>">
                <?= $category->getName() ?>
            </a>
            <?php
            if (App\Session::isAdmin()) { ?>

                <a href="index.php?ctrl=forum&action=updateCategory&id=<?= $category->getId() ?>"
                    class="btn btn-outline-dark py=1">Modifier</a>
            <?php } ?>
        </p>
    </div>
<?php }


if (App\Session::isAdmin()) {
    ?>

    <div>
        <h2>Ajouter un categorie : </h2>

        <div class="col align-self-center">
            <form action="index.php?ctrl=forum&action=addCategory" method="POST" enctype="multipart/form-data"
                class="mb-3 mx-auto">
                <div>
                    <label class="form-label">
                        Nom :
                        <input type="text" name="name" class="form-control">
                    </label>
                </div>

                <div>
                    <label class="form-label">
                        <input class="btn btn-dark" type="submit" name="submit" value="Créer">
                    </label>
                </div>
            </form>
        </div>
    <?php }
  
