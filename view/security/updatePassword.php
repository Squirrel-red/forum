<?php
$user = $result['data']['user'];
?>
<h2>Modifier le mot de passe</h2>

<div class="container-fluid">
    <div class="col align-self-center">
        <form action="index.php?ctrl=security&action=updatePassword&id=<?= $user->getId() ?>" method="POST"
            enctype="multipart/form-data" class="mb-3 mx-auto">
            <div>
                <label class="form-label">
                    Mot de passe actuel :
                    <input type="password" name="current" class="form-control">
                </label>
            </div>

            <div>
                <label class="form-label">
                    Nouveau mot de passe :
                    <input type="password" name="password1" class="form-control">
                </label>
            </div>

            <div>
                <label class="form-label">
                    Confirmer le nouveau mot de passe :
                    <input type="password" name="password2" class="form-control">
                </label>
            </div>

            <div>
                <label class="form-label">
                    <input class="btn btn-outline-dark" type="submit" name="submit" value="Modifier">
                </label>
                </p>
        </form>
    </div>
</div>