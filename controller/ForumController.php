<?php
namespace Controller;

use App\Session;
use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\CategoryManager;
use Model\Managers\TopicManager;
use Model\Managers\PostManager;
use Model\Managers\UserManager;

class ForumController extends AbstractController implements ControllerInterface
{

    public function index() {
        
        // créer une nouvelle instance de CategoryManager
        $categoryManager = new CategoryManager();
        // récupérer la liste de toutes les catégories grâce à la méthode findAll de Manager.php (triés par nom)
        $categories = $categoryManager->findAll(["name", "DESC"]);

        // le controller communique avec la vue "listCategories" (view) pour lui envoyer la liste des catégories (data)
        return [
            "view" => VIEW_DIR."forum/listCategories.php",
            "meta_description" => "Liste des catégories du forum",
            "data" => [
                "categories" => $categories
            ]
        ];
    }

    public function listTopicsByCategory($id) {

        $topicManager = new TopicManager();
        $categoryManager = new CategoryManager();
        $category = $categoryManager->findOneById($id);
        $topics = $topicManager->findTopicsByCategory($id);

        return [
            "view" => VIEW_DIR."forum/listTopics.php",
            "meta_description" => "Liste des topics par catégorie : ".$category,
            "data" => [
                "category" => $category,
                "topics" => $topics
            ]
        ];
    }
    public function listPostsByTopic($id)
    {
        $postManager = new PostManager();
        $topicManager = new TopicManager();
        $categoryManager = new CategoryManager();
        $topic = $topicManager->findOneById($id);
        $posts = $postManager->findPostsByTopic($id);
        $catId = $topic->getCategory()->getId();
        $category = $categoryManager->findOneById($catId);

        return [
            "view" => VIEW_DIR . "forum/detailsTopic.php",
            "meta_description" => "Liste des messages dans " . $topic,
            "data" => [
                "topic" => $topic,
                "posts" => $posts,
                "category" => $category,
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
            "view" => VIEW_DIR . "forum/profil.php",
            "meta_description" => "Profil de " . $user,
            "data" => [
                "user" => $user,
                "topics" => $topics,
                "posts" => $posts,
            ]
        ];

    }

    public function addTopic($id)
    {
        $userManager = new UserManager();
        $topicManager = new TopicManager();
        $postManager = new PostManager();

        $userId = $_SESSION['user']->getId();

        if (isset($_SESSION['user'])) {
            if (isset($_POST['submit'])) {

                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
                $contenu = filter_input(INPUT_POST, 'contenu', FILTER_SANITIZE_SPECIAL_CHARS);

                if ($title && $contenu) {
                    if (mb_strlen($contenu) < 500) {

                        $idtopic = $topicManager->add(['title' => $title, 'category_id' => $id, 'user_id' => $userId]);
                        $postManager->add(['contenu' => $contenu, 'user_id' => $userId, 'topic_id' => $idtopic]);
                        Session::addFlash('success', 'Topic créé !');
                        $this->redirectTo('forum', 'listPostsByTopic', $idtopic);
                    } else {
                        Session::addFlash('error', "Le message ne doit pas dépasser les 500 caractères");
                    }
                }
            }
        }
        return [
            "view" => VIEW_DIR . "forum/listTopics.php",
        ];
    }

    public function addpost($id)
    {
        $userManager = new UserManager();
        $postManager = new PostManager();

        $userId = $_SESSION['user']->getId();

        if (isset($_SESSION['user'])) {
            if (isset($_POST['submit'])) {

                $contenu = filter_input(INPUT_POST, 'contenu', FILTER_SANITIZE_SPECIAL_CHARS);

                if ($contenu) {
                    if (mb_strlen($contenu) < 500) {

                        $postManager->add(['contenu' => $contenu, 'topic_id' => $id, 'user_id' => $userId]);
                        Session::addFlash('success', 'Post créé !');
                        $this->redirectTo('forum', 'listPostsByTopic', $id);
                    } else {
                        Session::addFlash('error', "Le message ne doit pas dépasser les 500 caractères");
                        $this->redirectTo('forum', 'listPostsByTopic', $id);
                    }
                }
            }
        }
        return [
            "view" => VIEW_DIR . "forum/detailsTopic.php",
        ];
    }

    public function addCategory()
    {
        if (Session::isAdmin()) {

            $categoryManager = new CategoryManager();

            if (isset($_SESSION['user'])) {
                if (isset($_POST['submit'])) {

                    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

                    if ($name) {

                        $categoryManager->add(['name' => $name]);
                        Session::addFlash('success', 'Categorie créée !');
                        $this->redirectTo('forum', 'index');
                    }
                }
            }
            return [
                "view" => VIEW_DIR . "forum/listCategories.php",
            ];
        }
    }

    public function deleteTopic($id)
    {

        $topicManager = new TopicManager();

        $topic = $topicManager->findOneById($id);
        $catId = $topic->getCategory()->getId();

        if (Session::isAdmin() || Session::getUser() == $topic->getUser()) {

            $topicManager->delete($id);
            Session::addFlash('success', 'Topic Supprimé');
            $this->redirectTo("forum", "listTopicsByCategory", $catId);
            return [
                "view" => VIEW_DIR . "forum/listTopics.php",
            ];
        }
    }

    public function deletePost($id)
    {
        $postManager = new PostManager();

        $post = $postManager->findOneById($id);
        $topicId = $post->getTopic()->getId();

        if (Session::isAdmin() || Session::getUser() == $post->getUser()) {

            $postManager->delete($id);
            Session::addFlash('success', 'Post supprimé !');
            $this->redirectTo('forum', 'listPostsByTopic', $topicId);
            return [
                "view" => VIEW_DIR . "forum/detailsTopic.php",
            ];
        }
    }


    public function updateCategory($id)
    {
        if (Session::isAdmin()) {

            $categoryManager = new categoryManager();
            $category = $categoryManager->findOneById($id);


            if (isset($_POST['submit'])) {
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

                if ($name) {

                    $data = "name = '" . $name . "'";

                    $categoryManager->updateCategory($data, $id);
                    Session::addFlash('success', 'Categorie modifiée !');
                    $this->redirectTo('forum', 'listTopicsByCategory', $id);
                }
            }
            return [
                "view" => VIEW_DIR . "forum/updateCategory.php",
                "meta_description" => "Modification de catégorie",
                "data" => [
                    "category" => $category,
                ]
            ];
        }
    }

    public function updateTopic($id)
    {
        $topicManager = new TopicManager();
        $topic = $topicManager->findOneById($id);

        if (Session::getUser() == $topic->getUser()) {

            if (isset($_POST['submit'])) {
                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);

                if ($title) {

                    $data = "title = '" . $title . "'";

                    $topicManager->updateTopic($data, $id);
                    Session::addFlash('success', 'Topic modifié !');
                    $this->redirectTo('forum', 'listPostsByTopic', $id);
                }
            }
            return [
                "view" => VIEW_DIR . "forum/updateTopic.php",
                "meta_description" => "Modification de topic",
                "data" => [
                    "topic" => $topic,
                ]
            ];
        }
    }

    public function updatePost($id)
    {
        $postManager = new PostManager();
        $post = $postManager->findOneById($id);
        $topicId = $post->getTopic()->getId();

        if (Session::getUser() == $post->getUser()) {

            if (isset($_POST['submit'])) {
                $contenu = filter_input(INPUT_POST, 'contenu', FILTER_SANITIZE_SPECIAL_CHARS);

                if ($contenu) {
                    if (mb_strlen($contenu) < 500) {

                        $data = "contenu = '" . $contenu . "'";

                        $postManager->updatePost($data, $id);
                        Session::addFlash('success', 'Post modifié !');
                        $this->redirectTo('forum', 'listPostsByTopic', $topicId);
                    } else {
                        Session::addFlash('error', "Le message ne doit pas dépasser les 500 caractères");
                        $this->redirectTo('forum', 'listPostsByTopic', $topicId);
                    }

                }
            }
            return [
                "view" => VIEW_DIR . "forum/updatePost.php",
                "meta_description" => "Modification de post",
                "data" => [
                    "post" => $post,
                ]
            ];
        }
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
                        "view" => VIEW_DIR . "forum/updateProfil.php",
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
                        "view" => VIEW_DIR . "forum/updateProfil.php",
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
            "view" => VIEW_DIR . "forum/updateProfil.php",
            "meta_description" => "Modification de profil",
            "data" => [
                "user" => $user,
            ]
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
                if ($password1 == $password2 && strlen($password1) >= 5) {

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
            "view" => VIEW_DIR . "forum/updatePassword.php",
            "meta_description" => "Modification de mot de passe",
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
            "view" => VIEW_DIR . "forum/profil.php",
        ];


    }

    public function respondPost($id)
    {
        $postManager = new PostManager();
        $post = $postManager->findOneById($id);
        $topicId = $post->getTopic()->getId();

        $user = $_SESSION["user"];
        $userId = $user->getId();

        if (isset($_POST['submit'])) {
            $contenu = filter_input(INPUT_POST, 'contenu', FILTER_SANITIZE_SPECIAL_CHARS);

            if ($contenu) {
                // verifying if the content of the post is inferior to 500 characters
                if (mb_strlen($contenu) < 500) {

                    $postManager->add(['contenu' => $contenu, 'topic_id' => $topicId, 'user_id' => $userId, 'post_id' => $id]);
                    Session::addFlash('success', 'Post créé !');
                    $this->redirectTo('forum', 'listPostsByTopic', $topicId);
                } else {
                    Session::addFlash('error', "Le message ne doit pas dépasser les 500 caractères");
                    $this->redirectTo('forum', 'listPostsByTopic', $topicId);
                }
            }
        }
    }

}