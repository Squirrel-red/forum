<?php
$profil = $result['data']['user'];
$topics = $result['data']['topics'];
$posts = $result['data']['posts'];

if (!isset($_SESSION['user'])) { ?>
    <h1>
        <?= $profil->getUsername() ?>
    </h1>
    <img class="img-fluid img-thumbnail rounded w-5" src="public/img/avatar/<?= $profil->getAvatar() ?>" alt="">

    <p>Membre depuis le
        <?= $profil->displayRegisterDate() ?>
    </p>

<?php } else { ?>

    <div>
        <h1>
            <?= $profil->getUsername() ?>
            <?php if ($_SESSION['user'] != $profil && $_SESSION['user']->getRole() == "role_admin" && $profil->getStatus() == 0) { ?>
                <a href="index.php?ctrl=security&action=ban&id=<?= $profil->getId() ?>"
                    class="ban-btn btn btn-outline-warning text-dark">Ban</a>
            <?php } elseif ($_SESSION['user'] != $profil && $_SESSION['user']->getRole() == "role_admin" && $profil->getStatus() == 1) { ?>
                <a href="index.php?ctrl=security&action=unBan&id=<?= $profil->getId() ?>"
                    class="unban-btn btn btn-outline-warning text-dark">Unban</a>
            <?php } ?>

        </h1>
        <div class='d-flex'>
            <img class="img-fluid img-thumbnail rounded w-5" src="public/img/avatar/<?= $profil->getAvatar() ?>" alt="">
        </div>
        <p>Inscris depuis le
            <?= $profil->displayRegisterDate() ?>
        </p>
        <?php if ($_SESSION['user'] == $profil) { ?>
            <p>Email :
                <?= $profil->getEmail() ?>
            </p>

            <a href="index.php?ctrl=forum&action=updateProfil" class="btn btn-outline-dark py=1">Modifier</a>
            <a href="index.php?ctrl=forum&action=updatePassword" class="btn btn-outline-dark py=1">Modifier le mot de
                passe</a>

        </div>

    <?php } ?>

<?php }
if ($profil->getUsername() == "Utilisateur supprimé") {

} else { ?>

    <h2 class="mt-2">Topics créés</h2>

    <?php

    if (isset($topics)) {

        foreach ($topics as $topic) { ?>
            <div class="d-flex bg-light p-2 my-2 border border-dark-subtle rounded" style="min-width: 35%; max-width: fit-content;">
                <div class="d-flex flex-column">

                    <p>
                        <a href="index.php?ctrl=forum&action=listPostsByTopic&id=<?= $topic->getId() ?>">
                            <?= $topic->getTitle() ?>
                        </a> créé le
                        <?= $topic->displayDateCreation(); ?> à
                        <?= $topic->displayHeureCreation(); ?>
                    </p>
                </div>
            </div>

            <?php
        }
    } else { ?>

        <p>0 topic postés</p>

        <?php
    }

    ?>

    <h2>Posts créés</h2>

    <?php if (isset($posts)) {

        foreach ($posts as $post) { ?>
            <div class="d-flex bg-light p-2 my-2 border border-dark-subtle rounded" style="min-width: 35%; max-width: fit-content;">
                <div class="d-flex flex-column">
                    <p>
                        <?= $post->getContenu() ?> publié le
                        <?= $post->displayDateMessage() ?> à
                        <?= $post->displayHeureMessage() ?>
                        sur <a href="index.php?ctrl=forum&action=listPostsByTopic&id=<?= $post->getTopic()->getId() ?>">
                            <?= $post->getTopic() ?>
                        </a>
                    </p>
                </div>
            </div>

        <?php }

    } else { ?>

        <p>0 posts postés</p>

    <?php }
}
if (App\Session::getUser() == $profil) { ?>
    <a class="delete-user-btn btn btn-danger" href="index.php?ctrl=forum&action=deleteUser">Supprimer le compte</a>
<?php } ?>