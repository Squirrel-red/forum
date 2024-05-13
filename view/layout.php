<!DOCTYPE html>
<html lang="en">
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?= $meta_description ?>">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <!--<script src="https://cdn.tiny.cloud/1/zg3mwraazn1b2ezih16je1tc6z7gwp5yd4pod06ae5uai8pa/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= PUBLIC_DIR ?>/css/style.css">
        <title>FORUM</title>
    </head>
    <body>
        <div class="mb-5 mx-5" id="wrapper"> 
            <div id="mainpage">
                <!-- c'est ici que les messages (erreur ou succès) s'affichent-->
                <h3 class="message" style="color: red">
                    <?= App\Session::getFlash("error") ?>
                </h3>
                <h3 class="message" style="color: green">
                    <?= App\Session::getFlash("success") ?>
                </h3>
                <header>
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <a href="index.php?ctrl=home"><img class="logo" src="public/img/logo/ForumV12.png" alt=""></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="link-dark link-offset-2 link-underline
                                    link-underline-opacity-0 link-underline-opacity-100-hover m-2 fs-3"
                                        href="index.php">Accueil</a></li>
                                <?php
                                // si l'utiliseur connecté est un admin
                                if (App\Session::isAdmin()) {
                                    ?>
                                    <li class="nav-item"><a class="link-dark link-offset-2 link-underline
                                        link-underline-opacity-0 link-underline-opacity-100-hover m-2 fs-3"
                                            href="index.php?ctrl=home&action=users">Utilisateurs</a></li>
                                <?php } ?>
                                <li class="nav-item"><a
                                        class="link-dark link-offset-2 link-underline link-underline-opacity-0 link-underline-opacity-100-hover m-2 fs-3"
                                        href="index.php?ctrl=forum&action=index">Catégories</a></li>
                                <!-- <a id="btnSwitch" class="link-dark fs-5"><i class="fa-regular fa-moon"></i></a> -->
                            </ul>

                            <div class="nav navbar-nav me-auto">
                                <?php if (App\Session::getUser()) { ?>
                                    <ul class="navbar-nav me-auto">
                                        <li class="nav-item dropdown">
                                            <a class="dropdown-toggle nav-link" role="button" data-bs-toggle="dropdown"
                                                href="#"><span class="fs-3"><?= App\Session::getUser() ?></span></a>
                                            <ul class="dropdown-menu">
                                                <?php
                                                // si l'utilisateur est connecté 
                                                ?>
                                                <li class="nav-item"><a
                                                        class="link-dark link-offset-2 link-underline link-underline-opacity-0 link-underline-opacity-100-hover m-2 fs-3"
                                                        href="index.php?ctrl=forum&action=userProfile&id=<?= App\Session::getUser()->getId() ?>">Profil</a>
                                                </li>

                                                <li class="nav-item"><a
                                                        class="link-dark link-offset-2 link-underline link-underline-opacity-0 link-underline-opacity-100-hover m-2 fs-3"
                                                        href="index.php?ctrl=security&action=logout">Déconnexion</a></li>
                                            </ul>
                                    </ul>
                                <?php } else { ?>
                                    <ul class="navbar-nav">
                                        <li class="nav-item"><a
                                                class="link-dark link-offset-2 link-underline link-underline-opacity-0 link-underline-opacity-100-hover m-2 fs-3"
                                                href="index.php?ctrl=security&action=login">Connexion</a></li>
                                        <li class="nav-item"><a
                                                class="link-dark link-offset-2 link-underline link-underline-opacity-0 link-underline-opacity-100-hover m-2 fs-3"
                                                href="index.php?ctrl=security&action=register">Inscription</a></li>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </nav>
                </header>

            <a href="#" class="btn btn-outline-dark btn-floating btn-lg"
                style="position: fixed; bottom: 20px; right: 20px; z-index: 1;">
                <i class="fas fa-arrow-up"></i>
            </a> 

                <main id="forum" class = "mt-5 mb-5>
                    <?= $page ?>
                </main>
            </div>
            <footer>
            <p>
                &copy;
                <?= date_create("now")->format("Y") ?> - <a class="p-1 rounded"
                    href="index.php?ctrl=home&action=rules">Règlement du forum</a>
                - <a class="p-1 rounded" href="index.php?ctrl=home&action=legal">Mentions légales</a>
            </p>
            </footer>
        </div>
        <script
            src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous">
        </script>
        <script>
            $(document).ready(function(){
                // $(".message").each(function(){
                    // if($(this).text().length > 0){
                        // $(this).slideDown(500, function(){
                            // $(this).delay(3000).slideUp(500)
                        // })
                    // }
                // })
                $(".delete-btn").on("click", function(){
                    return confirm("Etes-vous sûr de vouloir supprimer?")
                })
                $(".delete-user-btn").on("click", function () {
                    return confirm("Êtes-vous sûr de vouloir supprimer vote compte ? Cette action est définitive.")
                })
                $(".ban-btn").on("click", function () {
                    return confirm("Êtes-vous sûr de vouloir bannir cet utilisateur ?")
                })
                $(".unban-btn").on("click", function () {
                    return confirm("Êtes-vous sûr de vouloir débannir cet utilisateur ?")
                })
                // tinymce.init({
                    // selector: '.post',
                    // menubar: false,
                    // plugins: [
                        // 'advlist autolink lists link image charmap print preview anchor',
                        // 'searchreplace visualblocks code fullscreen',
                        // 'insertdatetime media table paste code help wordcount'
                    // ],
                    // toolbar: 'undo redo | formatselect | ' +
                    // 'bold italic backcolor | alignleft aligncenter ' +
                    // 'alignright alignjustify | bullist numlist outdent indent | ' +
                    // 'removeformat | help',
                    // content_css: '//www.tiny.cloud/css/codepen.min.css'
                // });
            })
        </script>
        <script src="https://kit.fontawesome.com/19a031a4c5.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
        <script src="<?= PUBLIC_DIR ?>/js/script.js"></script>
    </body>
</html>