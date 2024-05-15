<?php
namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use App\DAO;
use App\Session;
use Model\Managers\TopicManager;
use Model\Managers\UserManager;
use Model\Managers\PostManager;

class SecurityController extends AbstractController
{
    // contiendra les méthodes liées à l'authentification : register, login et logout

    public function register () 
    {
        if (isset($_POST["submit"])) {

            $userManager = new UserManager;

            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_SPECIAL_CHARS);
            $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_SPECIAL_CHARS);

            // verifying if username, email & both password passed the filter input
            if ($username && $email && $password1 && $password2) {

                // verifying if email is already taken
                if (!$userManager->findOneByEmail($email)) {

                    // verifying if username is already taken
                    if (!$userManager->findOneByUsername($username)) {

                        //verifying if password is the same on both inputs & if password is at least 5 characters long
                        if ($password1 == $password2 && strlen($password1) >= 4) {

                            // hashing password to store it secure in database
                            $passwordHash = password_hash($password1, PASSWORD_DEFAULT);

                            // adding data to table / creating user
                            $userManager->add([
                                "username" => $username,
                                "email" => $email,
                                "password" => $passwordHash
                            ]);

                            Session::addFlash('success', 'Compte créé !');
                            $this->redirectTo('security', 'login');
                            exit;

                        } else {
                            Session::addFlash('error', 'Mot de passe invalide !');
                            $this->redirectTo('security', 'register');
                        }
                    } else {
                        Session::addFlash('error', "Nom d'utilisateur déjà pris !");
                        $this->redirectTo('security', 'register');
                    }
                } else {
                    Session::addFlash('error', "Cet adresse email est déjà utilisée !");
                    $this->redirectTo('security', 'register');
                }
            }
        }

        return [
            "meta_description" => "Inscription au forum",
            "view" => VIEW_DIR . "security/register.php",
        ];

    }
    public function login () {
        if (isset($_POST["submit"])) {

            $userManager = new UserManager;

            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

            // honey pot field
            $honeypot = $_POST["firstname"];

            // if the field is completed, the login is cancelled
            if (!empty($honeypot)) {
                $this->redirectTo("security", "login");
            } else {

                // verifying if username & password passed the filter input
                if ($username && $password) {

                    $user = $userManager->findOneByUsername($username);

                    // verifying if the username exists in database
                    if ($user) {
                        $hash = $user->getPassword();

                        // checkq the stored hashed password with the typed password
                        if (password_verify($password, $hash)) {
                            // if the user is not banned
                            if ($user->getStatus() == 0) {
                                $_SESSION["user"] = $user;
                                $this->redirectTo('home', 'index');
                                // if the user is banned
                            } elseif ($user->getStatus() == 1) {
                                Session::addFlash('error', 'Vous êtes banni');
                                $this->redirectTo('security', 'login');
                                // if the user deleted their account
                            } elseif ($user->getStatus() == 2) {
                                $this->redirectTo('security', 'login');
                            }
                        } else {
                            Session::addFlash('error', 'Mot de passe invalide!');
                            $this->redirectTo('security', 'login');
                        }

                    } else {
                        Session::addFlash("error", "Nom d'utilisateur invalide!");
                        $this->redirectTo('security', 'login');
                    }

                } else {
                    Session::addFlash("error", "Nom d'utilisateur ou mot de passe invalide!");
                    $this->redirectTo('security', 'login');
                }
            }

        }
        return [
            "meta_description" => "Connection au forum",
            "view" => VIEW_DIR . "security/login.php",
        ];


    }

    public function updatePassword()
    {

        $userManager = new UserManager();
        $user = $_SESSION['user'];
        $id = $user->getId();

        if (isset($_POST['submit'])) {

            $current = filter_input(INPUT_POST, 'current', FILTER_SANITIZE_SPECIAL_CHARS);
            $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_SPECIAL_CHARS);
            $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_SPECIAL_CHARS);

            if (password_verify($current, $user->getPassword())) {
                if ($password1 == $password2) {

                    $passwordHash = password_hash($password1, PASSWORD_DEFAULT);

                    $data = "password = '" . $passwordHash . "'";

                    $userManager->updateUser($data, $id);

                    $userUpdated = $userManager->findOneById($id);
                    $_SESSION['user'] = $userUpdated;

                    Session::addFlash('success', 'Mot de passe modifié !');
                    $this->redirectTo('forum', 'userProfile', $id);
                } else {
                    Session::addFlash('error', 'Mot de passe trop court ou incorrect');
                    $this->redirectTo('forum', 'updatePassword', $id);
                }
            } else {
                Session::addFlash('error', 'Mauvais mot de passe');
                $this->redirectTo('forum', 'updatePassword', $id);
            }

        }
        return [
            "view" => VIEW_DIR . "security/updatePassword.php",
            "meta_description" => "Modification de mot de passe",
            "data" => [
                "user" => $user,
            ]
        ];

    }

    public function userProfile($id)
    {
        $userManager = new UserManager();
        $topicManager = new TopicManager();
        $postManager = new PostManager();
        $user = $userManager->findOneById($id);
        $topics = $topicManager->findTopicsByUser($id);
        $posts = $postManager->findPostsByUser($id);

        return [
            "view" => VIEW_DIR . "security/profil.php",
            "meta_description" => "Profil de " . $user,
            "data" => [
                "user" => $user,
                "topics" => $topics,
                "posts" => $posts,
            ]
        ];

    }

    public function updateProfil()
    {
        $userManager = new UserManager();
        $user = $_SESSION['user'];
        $id = $user->getId();


        if (isset($_POST['submit'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            $avatarChanged = false;
            $avatar = $user->getAvatar();

            if (!empty($_FILES['file']['name'])) {
                $tmpName = $_FILES['file']['tmp_name'];
                $name = $_FILES['file']['name'];
                $size = $_FILES['file']['size'];
                $error = $_FILES['file']['error'];

                $tabExtension = explode('.', $name);
                $extension = strtolower(end($tabExtension));
                //Tableau des extensions que l'on accepte
                $extensions = ['jpg', 'png', 'jpeg', 'gif', 'webp'];
                //Taille max que l'on accepte
                $maxSize = 1500000;

                // verifying if the file extension is one of the accepted file extensions
                if (in_array($extension, $extensions)) {

                    // if the file is under the max accepted size
                    if ($size <= $maxSize) {

                        // verifying if there is no error in the file
                        if ($error == 0) {

                            $uniqueName = uniqid('', true);
                            //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
                            $file = imagewebp(imagecreatefromstring(file_get_contents($tmpName)), "public/img/avatar/$uniqueName.webp");
                            //imagewebp donne au fichier un format webp

                            // verifying if the user had an avatar and it wasn't the default one
                            if ($avatar !== 'User-avatar.png' && file_exists("public/img/avatar/" . $avatar)) {
                                unlink("public/img/avatar/" . $avatar);
                            }

                            $avatarChanged = true;

                        } else {
                            Session::addFlash('error', "Erreur de fichier");
                            $this->redirectTo('forum', 'updateProfil', $id);

                        }
                    } else {
                        Session::addFlash('error', "Fichier trop lourd");
                        $this->redirectTo('forum', 'updateProfil', $id);

                    }
                } else {
                    Session::addFlash('error', "Type du fichier non accepté");
                    $this->redirectTo('forum', 'updateProfil', $id);

                }
            }

            // verifying if username & email both passed the filter
            if ($username && $email) {

                // verifying if email is already taken
                if ($user->getEmail() != $email && $userManager->findOneByEmail($email) != null) {
                    Session::addFlash("error", "Adresse email déjà utilisée");
                    return [
                        "view" => VIEW_DIR . "security/updateProfil.php",
                        "meta_description" => "Modification du profil",
                        "data" => [
                            "user" => $user
                        ]
                    ];
                } else {
                    $data[] = "email = '" . $email . "'";
                }

                // verifying if username is already taken
                if ($user->getUsername() != $username && $userManager->findOneByUsername($username) != null) {
                    Session::addFlash("error", "Nom d'utilisateur déjà utilisé");
                    return [
                        "view" => VIEW_DIR . "security/updateProfil.php",
                        "meta_description" => "Modification du profil",
                        "data" => [
                            "user" => $user
                        ]
                    ];
                } else {
                    $data[] = "username = '" . $username . "'";
                }

                // verifying if avatar has been modified
                if ($avatarChanged) {
                    $data[] = "avatar = '" . $uniqueName . ".webp'";
                }


                if (!empty($data)) {
                    $dataToUpdate = implode(', ', $data);
                    $userManager->updateUser($dataToUpdate, $id);

                    $userUpdated = $userManager->findOneById($id);
                    $_SESSION['user'] = $userUpdated;

                    Session::addFlash('success', 'Profil modifié !');
                    $this->redirectTo('forum', 'userProfile', $id);
                    exit;
                } else {
                    //no data to update, back to profile page
                    $this->redirectTo('forum', 'userProfile', $id);
                }
            }
        }
        return [
            "view" => VIEW_DIR . "security/updateProfil.php",
            "meta_description" => "Modification de profil",
            "data" => [
                "user" => $user,
            ]
        ];

    }

    

    public function deleteUser()
    {
        $userManager = new UserManager();

        $user = $_SESSION['user'];
        $id = $user->getId();

        $data = "username = 'Utilisateur supprimé',
                email = 'Utilisateur supprimé',
                avatar = 'User-avatar.png',
                status = '2'";

        $userManager->updateUser($data, $id);
        unset($_SESSION['user']);
        Session::addFlash('success', 'Compte supprimé !');
        $this->redirectTo('home', 'index');
        return [
            "view" => VIEW_DIR . "security/profil.php",
        ];


    }


    public function logout () {
        unset($_SESSION['user']);
        $this->redirectTo('home', 'index');
    }

    public function ban($id)
    {
        if (Session::isAdmin()) {
            $userManager = new userManager();

            $data = 1;

            $userManager->banUser($data, $id);
            Session::addFlash('success', 'Utilisateur banni !');

            $this->redirectTo('home', 'users');
        }
    }
    public function unBan($id)
    {
        if (Session::isAdmin()) {

            $userManager = new userManager();

            $data = 0;

            $userManager->banUser($data, $id);
            Session::addFlash('success', 'Utilisateur banni !');

            $this->redirectTo('home', 'users');
        }
    }

    public function closeTopic($id)
    {

        if (Session::isAdmin()) {
            $topicManager = new TopicManager();

            $data = "closed = '1'";

            $topicManager->updateTopic($data, $id);
            Session::addFlash('success', 'Topic fermé');

            $this->redirectTo('forum', 'listPostsByTopic', $id);
        }
    }

    public function openTopic($id)
    {

        if (Session::isAdmin()) {
            $topicManager = new TopicManager();

            $data = "closed = '0'";

            $topicManager->updateTopic($data, $id);
            Session::addFlash('success', 'Topic fermé');

            $this->redirectTo('forum', 'listPostsByTopic', $id);
        }
    }    
}