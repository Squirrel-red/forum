<?php
    $category = $result["data"]['category']; 
    $topics = $result["data"]['topics']; 
?>

<h1>
    <?= $category->getName() ?>
</h1>

<?php

if (isset($topics)) {

    foreach ($topics as $topic) { ?>
        <div class="d-flex bg-light p-2 my-5 border border-dark-subtle rounded" style="min-width: 35%; max-width: fit-content;">
            <div class="d-flex flex-column">
                <p><a href="index.php?ctrl=forum&action=listPostsByTopic&id=<?= $topic->getId() ?>">
                        <?= $topic->getTitle() ?>
                    </a> par
                    <a href="index.php?ctrl=forum&action=userProfile&id=<?= $topic->getUser()->getId() ?>">
                        <?= $topic->getUser() ?>
                </p>

                </a>publié le
                <?= $topic->displayDateCreation(); ?> à
                <?= $topic->displayHeureCreation(); ?>
                <div class="d-flex">
                    <?php
                    if (isset($_SESSION['user'])) {
                        $user = $_SESSION['user'];
                        if (serialize($user) == serialize($topic->getUser())) { ?>
                            <a href="index.php?ctrl=forum&action=updateTopic&id=<?= $topic->getId() ?>"
                                class="btn btn-outline-dark m-2">Modifier</a>
                            <a class='delete-btn btn btn-outline-danger m-2'
                                href="index.php?ctrl=forum&action=deleteTopic&id=<?= $topic->getId(); ?>">Supprimer</i></a>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php }
} else { ?>
    <p>Aucun Topic</p>
<?php }
if (isset($_SESSION['user'])) {
    ?>

    <p>Ajouter un topic : </p>

    <div class="container-fluid">
        <div class="col align-self-center">
            <form action="index.php?ctrl=forum&action=addTopic&id=<?= $category->getId() ?>" method="POST"
                enctype="multipart/form-data" class="mb-3 mx-auto">
                <div>
                    <label class="form-label">
                        Titre :
                        <input type="text" name="title" class="form-control">
                    </label>
                </div>

                <div>
                    <label class="form-label">
                        Contenu :
                        <textarea class="form-control" name="contenu" cols="30" rows="10"></textarea>
                    </label>
                </div>

                <div>
                    <label class="form-label">
                        <input class="btn btn-outline-dark" type="submit" name="submit" value="Poster">
                    </label>
                </div>
            </form>
        </div>
    </div>
<?php }
