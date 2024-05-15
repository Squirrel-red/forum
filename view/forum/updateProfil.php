<?php
$user = $result['data']['user'];
?>
<h2>Modifier un profil</h2>

<div class="container-fluid">
    <div class="col align-self-center">
        <form action="index.php?ctrl=forum&action=updateProfil&id=<?= $user->getId() ?>" method="POST"
            enctype="multipart/form-data" class="mb-3 mx-auto">
            <div>
                <label class="form-label">
                    Nom d'utilisateur :
                    <input type="text" name="username" class="form-control" value="<?= $user->getUsername() ?>">
                </label>
            </div>

            <div>
                <label class="form-label">
                    Email :
                    <input type="text" name="email" class="form-control" value="<?= $user->getEmail() ?>">
                </label>
            </div>

            <div>
                <label class="form-label">
                    Avatar :
                    <input type="file" name="file" class="form-control" value="<?= $user->getAvatar() ?>">
                </label>
            </div>

            <div>
                <label class="form-label">
                    <input class="btn btn-outline-dark" type="submit" name="submit" value="Modifier">
                </label>
            </div>
        </form>
    </div>
</div>