<?php
$category = $result['data']['category'];
?>
<p>Modifier un categorie : </p>

<div class="col align-self-center">
    <form action="index.php?ctrl=forum&action=updateCategory&id=<?= $category->getId() ?>" method="POST"
        enctype="multipart/form-data" class="mb-3 mx-auto">
        <div>
            <label class="form-label">
                Nom :
                <input type="text" name="name" class="form-control" value="<?= $category->getName() ?>">
            </label>
        </div>

        <div>
            <label class="form-label">
                <input class="btn btn-dark" type="submit" name="submit" value="Modifier">
            </label>
        </div>
    </form>
</div>