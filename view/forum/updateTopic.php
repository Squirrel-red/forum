<?php
$topic = $result['data']['topic'];
?>
<h2>Modifier un topic</h2>

<div class="container-fluid">
    <div class="col align-self-center">
        <form action="index.php?ctrl=forum&action=updateTopic&id=<?= $topic->getId() ?>" method="POST"
            enctype="multipart/form-data" class="mb-3 mx-auto">
            <div>
                <label class="form-label">
                    Titre :
                    <input type="text" name="title" class="form-control" value="<?= $topic->getTitle() ?>">
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