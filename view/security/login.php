<h1>Se connecter :</h1>

<div class="container-fluid">
    <div class="col align-self-center">
        <form action="index.php?ctrl=security&action=login" method="POST" enctype="multipart/form-data"
            class="mb-3 mx-auto">

            <!-- HoneyPot (only a bot can see the field) -->
            <p>
                <label class="form-label">
                    <input type="text" name="firstname" class="hide-robot">
                </label>
            </p>

            <p>
                <label class="form-label">
                    Nom d'utilisateur :
                    <input type="text" name="username" class="form-control">
                </label>
            </p>

            <p>
                <label class="form-label">
                    Mot de passe :
                    <input type="password" name="password" class="form-control">
                </label>
            </p>

            <p>
                <label class="form-label">
                    <input class="btn btn-dark" type="submit" name="submit" value="Se Connecter">
                </label>
            </p>
        </form>
    </div>
</div>