<?php
namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use App\DAO;
use App\Session;
use Model\Managers\TopicManager;
use Model\Managers\UserManager;

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
                        if ($password1 == $password2 && strlen($password1) >= 5) {

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