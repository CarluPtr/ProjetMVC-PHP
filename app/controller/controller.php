<?php

use Model\Post;
use Model\PostManager;
use Model\Comment;
use Model\CommentManager;
use Model\MailManager;
use Model\User;
use Model\UserManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


function listPosts()
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $postManager = new PostManager(); // Création d'un objet
    $posts = $postManager->getPosts(); // Appel d'une fonction de cet objet

  // Render our view
    echo $twig->render('blog.html.twig', [
    'post' => $posts,
    'userid' => $_SESSION['id'] ?? null,
    'is_admin' => $_SESSION['is_admin'] ?? null,
  ]);
}

function emailController($firstname, $birthname, $email, $subject, $message)
{
    $emailManager = new MailManager();
    $email = $emailManager->sendemail($firstname, $birthname, $email, $subject, $message);
    header('Location: index.php');
}

function post()
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $postManager = new PostManager();
    $commentManager = new CommentManager();

    $post = $postManager->getPost($_GET['id']);
    $comments = $commentManager->getComments($_GET['id']);

    echo $twig->render('posts.html.twig', [
        'post' => $post,
        'comments' => $comments,
        'userid' => $_SESSION['id'] ?? null,
        'is_admin' => $_SESSION['is_admin'] ?? null,
        ]);
}

function addComment($postId, $utilisateur, $comment)
{
    $is_admin = $_SESSION['is_admin'];

    $commentManager = new CommentManager();

    $commentEntitie = new Comment();

    $commentEntitie->setIdPost($postId);
    $commentEntitie->setIdUser($utilisateur);
    $commentEntitie->setComment($comment);

    

    $affectedLines = $commentManager->postComment($commentEntitie, $is_admin);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    } else {
        header('Location: index.php?action=post&id='.$postId);
    }
}

function addPost($utilisateur, $title, $content, $postimg)
{

    $image = $postimg['tmp_name'];
    $img = file_get_contents($image);

    $postManager = new PostManager();
    $post = new Post();

    $post->setIdUser($utilisateur);
    $post->setTitle($title);
    $post->setContent($content);
    $post->setImg($img);

    $affectedLines = $postManager->postPost($post);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le post !');
    } else {
        header('Location: index.php?action=listPosts');
    }
}

function register()
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);


    echo $twig->render('register.html.twig');
}

function registerAction($prenom, $nom, $username, $email, $password)
{
    $userManager = new UserManager();
    $user = new User();

    $user->setIsAdmin(0);
    $user->setPrenom($prenom);
    $user->setNom($nom);
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPassword($password);

    $affectedLines = $userManager->registerAccount($user);

    if ($affectedLines === false) {
        throw new Exception('Impossible de créer le compte');
    } else {
        header('Location: index.php');
    }
}

function logIn()
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    echo $twig->render('login.html.twig');
}

function logInAction($email, $password)
{
    $userManager = new UserManager();

    $check = $userManager->logInAccount($email, $password);

    if ($check === false) {
        throw new Exception('Impossible de se connecter');
    } else {
        header('Location: index.php');
    }
}

function logOutAction()
{
    $userManager = new UserManager();

    $check = $userManager->logout();

    if (!$check) {
        throw new Exception('Impossible de de se déconnecter');
    } else {
        header('Location: index.php');
    }
}

function accountPage()
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $userManager = new UserManager();
    $user = $userManager->getUser($_GET['id']);

    $postManager = new PostManager();
    $posts = $postManager->getUserPosts($_GET['id']);

    $commentManager = new CommentManager();
    $comments = $commentManager->getUserComments($_GET['id']);

    echo $twig->render('profile.html.twig', [
        'userid' => $_SESSION['id'] ?? null,
        'is_admin' => $_SESSION['is_admin'] ?? null,
        'userInfos' => $user,
        'posts' => $posts,
        'comments' => $comments,
    ]);
}

function adminPannel()
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    $commentManager = new CommentManager();
    $newComments = $commentManager->getAllNewComments();
    $validedComments = $commentManager->getAllValidedComments();

    echo $twig->render('admin.html.twig', [
        'userid' => $_SESSION['id'] ?? null,
        'is_admin' => $_SESSION['is_admin'] ?? null,
        'posts' => $posts,
        'new_comments' => $newComments,
        'valided_comments' => $validedComments,
    ]);
}

function changePP($id, $profilepicture)
{
    if ($id = $_SESSION['id']) {
        $id = $_SESSION['id'];
        $userManager = new UserManager();
        $user = $userManager->editPP($id, $profilepicture);

        if ($affectedLines === false) {
            throw new Exception("Impossible de changer l'image");
        } else {
            header('Location: account/'.$id);
        }
    } else {
        echo "vous n'etes pas le bon utilisateur";
    }
}

function changeBio($id, $bio)
{
    if ($id == $_SESSION['id']) {
        $id = $_SESSION['id'];
        $userManager = new UserManager();
        $user = $userManager->editBio($id, $bio);

        if ($affectedLines === false) {
            throw new Exception('Impossible de changer la bio');
        } else {
            header('Location: account/'.$id);
        }
    } else {
        echo "vous n'etes pas le bon utilisateur";
    }
}
function deleteComController($id)
{
    $adminManager = new AdminManager();
    $admin = $adminManager->deleteComment($id);

    if ($affectedLines === false) {
        throw new Exception('Impossible de supprimer le commentaire');
    } else {
        header('Location: admin/');
    }
}

function deletePostController($id)
{
    $adminManager = new AdminManager();
    $admin = $adminManager->deletePost($id);

    if ($affectedLines === false) {
        throw new Exception('Impossible de supprimer le commentaire');
    } else {
        header('Location: admin/');
    }
}

function validateComController($id)
{
    $adminManager = new AdminManager();
    $admin = $adminManager->validateComment($id);

    if ($affectedLines === false) {
        throw new Exception('Impossible de valider le commentaire');
    } else {
        header('Location: admin/');
    }
}

function home()
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $postManager = new PostManager(); // Création d'un objet
    $posts = $postManager->getPosts(); // Appel d'une fonction de cet objet

    echo $twig->render('home.html.twig', [
        'post' => $posts,
        'userid' => $_SESSION['id'] ?? null,
        'is_admin' => $_SESSION['is_admin'] ?? null,
    ]);
}