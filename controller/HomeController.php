<?php
namespace Controller;

use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\CategoryManager;
use Model\Managers\TopicManager;
use Model\Managers\PostManager;
use Model\Managers\UserManager;

class HomeController extends AbstractController implements ControllerInterface 
{

    public function index()
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->findAll(["id_category", "ASC"]);
        return [
            "view" => VIEW_DIR."home/home.php",
            "meta_description" => "Page d'accueil du forum",
            "data" => [
                "categories" => $categories
            ]
        ];
    }
        
    public function users()
    {
        $this->restrictTo("ROLE_USER");

        $userManager = new UserManager();

        if (isset($_POST['submit'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);

            if ($username) {
                $users = $userManager->findAllByUsername($username, ['username', 'ASC']);
            }

        } else {
            $users = $userManager->findAll(['registerDate', 'DESC']);
        }

        return [
            "view" => VIEW_DIR."security/users.php",
            "meta_description" => "Liste des utilisateurs du forum",
            "data" => [ 
                "users" => $users 
            ]
        ];
    }

    public function rules()
    {
        return [
            "view" => VIEW_DIR . "home/rules.php",
            "meta_description" => "Règles du forum",

        ];
    }

    public function legal()
    {
        return [
            "view" => VIEW_DIR . "home/legal.php",
            "meta_description" => "Mentions légales",

        ];
    }
}
