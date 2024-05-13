<h1>S'inscrire :</h1>

<div class="container-fluid">
    <div class="col align-self-center">
        <form action="index.php?ctrl=security&action=register" method="POST" enctype="multipart/form-data"
            class="mb-3 mx-auto">
            <p>
                <label class="form-label">
                    Nom d'utilisateur :
                    <input type="text" name="username" class="form-control">
                </label>
            </p>

            <p>
                <label class="form-label">
                    Email :
                    <input type="email" name="email" placeholder="user@domain.extension" class="form-control">
                </label>
            </p>

            <p>
                <label class="form-label">
                    Mot de passe :
                    <input type="password" name="password1" class="form-control">
                </label>
            </p>

            <p>
                <label class="form-label">
                    Confirmer le mot de passe :
                    <input type="password" name="password2" class="form-control">
                </label>
            </p>

            <p>
                <label class="form-label">
                    <input class="btn btn-dark" type="submit" name="submit" value="S'inscrire">
                </label>
            </p>
        </form>
    </div>
</div>