<?php

use Model\Post;
use Model\PostManager;
use Model\Comment;
use Model\CommentManager;
use Model\AdminManager;
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
  print_r($twig->render('blog.html.twig', [
    'post' => $posts,
    'userid' => filter_var($_SESSION['id']) ?? null,
    'is_admin' => filter_var($_SESSION['is_admin']) ?? null,
  ]));
}

function emailController($firstname, $birthname, $email, $subject, $message)
{
    $emailManager = new MailManager();
    $emailManager->sendemail($firstname, $birthname, $email, $subject, $message);
    header('Location: index.php');
}

function post($postId)
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $postManager = new PostManager();
    $commentManager = new CommentManager();

    $post = $postManager->getPost($postId);
    $comments = $commentManager->getComments($postId);

    print_r($twig->render('posts.html.twig', [
        'post' => $post,
        'comments' => $comments,
        'userid' => filter_var($_SESSION['id']) ?? null,
        'is_admin' => filter_var($_SESSION['is_admin']) ?? null,
        ]));
}

function addComment($postId, $utilisateur, $comment)
{
    $is_admin = filter_var($_SESSION['iis_admin']);

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


    print_r($twig->render('register.html.twig'));
}

function registerAction($prenom, $nom, $username, $email, $password, $retypedPassword)
{
    $userManager = new UserManager();
    $user = new User();

    $user->setIsAdmin(0);
    $user->setPrenom($prenom);
    $user->setNom($nom);
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPassword($password);
    $user->setRetypedPassword($retypedPassword);

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

    print_r($twig->render('login.html.twig'));
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

function accountPage($userId)
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $userManager = new UserManager();
    $user = $userManager->getUser($userId);

    $postManager = new PostManager();
    $posts = $postManager->getUserPosts($userId);

    $commentManager = new CommentManager();
    $comments = $commentManager->getUserComments($userId);

    print_r($twig->render('profile.html.twig', [
        'userid' => filter_var($_SESSION['id']) ?? null,
        'is_admin' => filter_var($_SESSION['is_admin']) ?? null,
        'userInfos' => $user,
        'posts' => $posts,
        'comments' => $comments,
    ]));
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

    print_r($twig->render('admin.html.twig', [
        'userid' => filter_var($_SESSION['id']) ?? null,
        'is_admin' => filter_var($_SESSION['is_admin']) ?? null,
        'posts' => $posts,
        'new_comments' => $newComments,
        'valided_comments' => $validedComments,
    ]));
}

function changePP($userId, $profilepicture)
{
    if ($userId == $_SESSION['id']) {
        $userId = filter_var($_SESSION['id']);
        $userManager = new UserManager();
        $userManager->editPP($userId, $profilepicture);

        if ($affectedLines === false) {
            throw new Exception("Impossible de changer l'image");
        } else {
            header('Location: account/'.$userId);
        }
    } else {
        throw new Exception("vous n'etes pas le bon utilisateur");
    }
}

function changeBio($userId, $bio)
{
    if ($userId == $_SESSION['id']) {
        $userId = filter_var($_SESSION['id']);
        $userManager = new UserManager();
        $userManager->editBio($id, $bio);

        if ($affectedLines === false) {
            throw new Exception('Impossible de changer la bio');
        } else {
            header('Location: account/'.$userId);
        }
    } else {
        throw new Exception("Vous n'etes pas le bon utilisateur");
    }
}
function deleteComController($commentId)
{
    $adminManager = new AdminManager();
    $adminManager->deleteComment($commentId);

    if ($affectedLines === false) {
        throw new Exception('Impossible de supprimer le commentaire');
    } else {
        header('Location: admin/');
    }
}

function deletePostController($postId)
{
    $adminManager = new AdminManager();
    $adminManager->deletePost($postId);

    if ($affectedLines === false) {
        throw new Exception('Impossible de supprimer le commentaire');
    } else {
        header('Location: admin/');
    }
}

function validateComController($commentId)
{
    $adminManager = new AdminManager();
    $adminManager->validateComment($commentId);

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

    print_r($twig->render('home.html.twig', [
        'post' => $posts,
        'userid' => filter_var($_SESSION['id']) ?? null,
        'is_admin' => filter_var($_SESSION['is_admin']) ?? null,
    ]));
}
