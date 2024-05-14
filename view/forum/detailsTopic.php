<?php
$topic = $result["data"]['topic'];
$posts = $result["data"]['posts'];
$category = $result["data"]['category'];
?>

<h3>
    <a href="index.php?ctrl=forum&action=listTopicsByCategory&id=<?= $category->getId() ?>">
        <?= $category->getName() ?>
    </a>
</h3>
<h1>
    <?= $topic->getTitle() ?>
    <?php
    // if the current user is an admin or the author of this topic
    if (App\Session::isAdmin() || App\Session::getUser() == $topic->getUser()) { ?>

        <a href="index.php?ctrl=forum&action=updateTopic&id=<?= $topic->getId() ?>"
            class="btn btn-outline-dark py=1">Modifier</a>
        <a href="index.php?ctrl=forum&action=deleteTopic&id=<?= $topic->getId() ?>"
            class="delete-btn btn btn-outline-danger py=1">Supprimer</a>
        <!-- if the topic is open -->
        <?php if ($topic->getClosed() == 0) { ?>
            <a href="index.php?ctrl=security&action=closeTopic&id=<?= $topic->getId() ?>"
                class="delete-btn btn btn-outline-warning py=1">Fermer</a>
            <!-- if the topic is close -->
        <?php } elseif ($topic->getClosed() == 1) { ?>
            <a href="index.php?ctrl=security&action=openTopic&id=<?= $topic->getId() ?>"
                class="delete-btn btn btn-outline-warning py=1">Ouvrir</a>
        <?php }
    } ?>

</h1>

<?php
if (isset($posts)) {

    foreach ($posts as $post) { ?>
        <div class="d-flex bg-light py-2 my-5 border border-dark-subtle rounded"
            style="min-width: 35%; max-width: fit-content;">

            <div class="m-2"><img class="img-thumbnail rounded w-5" style="object-fit-cover"
                    src="public/img/avatar/<?= $post->getUser()->getAvatar() ?>" alt="">
            </div>
            <div class="d-flex flex-column">
                <p>
                    <?= $post->getContenu(); ?>
                </p>
                <p>par
                    <a href="index.php?ctrl=forum&action=userProfile&id=<?= $post->getUser()->getId() ?>">
                        <?= $post->getUser() ?>
                    </a> publié le
                    <?= $post->displayDateMessage() ?> à
                    <?= $post->displayHeureMessage() ?>
                </p>
                <?php if (isset($_SESSION['user']) && $post->getUser()->getUsername() != "Utilisateur supprimé") { ?>
                    <a class="dark-link link-underline-dark m-2 toggle-form">Répondre</a>

                    <div class="response-form">
                        <form action="index.php?ctrl=forum&action=respondPost&id=<?= $topic->getId() ?>" method="POST"
                            enctype="multipart/form-data" class="mb-3 mx-auto">
                            <div>
                                <label class="form-label">
                                    Contenu :
                                    <textarea name="contenu" rows='10' class="form-control"
                                        placeholder="500 caractères max.">@<?= $post->getUser() ?> </textarea>
                                </label>
                            </div>

                            <div>
                                <label class="form-label">
                                    <input class="btn btn-outline-dark" type="submit" name="submit" value="Poster">
                                </label>
                            </div>
                        </form>
                    </div>
                <?php }
                if (isset($_SESSION['user'])) { ?>
                    <div class="d-flex">
                        <!-- if the current user is an admin or the author of this topic -->
                        <?php if ($_SESSION['user'] == $post->getUser() || App\Session::isAdmin()) { ?>
                            <a class="delete-btn btn btn-outline-danger m-2"
                                href="index.php?ctrl=forum&action=deletePost&id=<?= $post->getId() ?>">Supprimer</i></a>
                        <?php }
                        // if the current user is the author of this post
                        if ($_SESSION['user'] == $post->getUser()) { ?>
                            <a class="btn btn-outline-dark m-2"
                                href="index.php?ctrl=forum&action=updatePost&id=<?= $post->getId() ?>">Modifier</a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php }
} else { ?>
    <p>Aucun Post</p>
<?php }

// if the topic is closed, comment form is disabled
if ($topic->getClosed() == 1) { ?>
    <p>Le topic est fermé - impossible de poster</p>
<?php } else if ($topic->getClosed() == 0) {

    if (isset($_SESSION['user'])) {
        ?>

            <p>Ajouter un post : </p>

            <div class="container-fluid">
                <div class="col align-self-center">
                    <form action="index.php?ctrl=forum&action=addPost&id=<?= $topic->getId() ?>" method="POST"
                        enctype="multipart/form-data" class="mb-3 mx-auto">
                        <div>
                            <label class="form-label">
                                Contenu :
                                <textarea name="contenu" rows='10' class="form-control" style='min-width: 100%'
                                    placeholder="500 caractères max."></textarea>
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
} ?>