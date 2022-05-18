<?php

require_once('model/PostManager.php');
require_once('model/CommentManager.php');
require_once('model/UserManager.php');


require_once 'vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;


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
  ] );
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
        ]);
}

function addComment($postId, $utilisateur, $comment)
{
    $utilisateur = $_SESSION['id'];

    $commentManager = new CommentManager();

    $affectedLines = $commentManager->postComment($postId, $utilisateur, $comment);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=post&id=' . $postId);
    }
}

function register(){
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    echo $twig->render('register.html.twig');
}

function registerAction($prenom, $nom, $username, $email, $password){

    $userManager = new UserManager();
    
    $affectedLines = $userManager->registerAccount($prenom, $nom, $username, $email, $password);

    if ($affectedLines === false) {
        throw new Exception('Impossible de créer le compte');
    }
    else {
        header('Location: index.php');
    }
}

function logIn(){
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    echo $twig->render('login.html.twig');
}

function logInAction($email, $password){

    $userManager = new UserManager();
    
    $check = $userManager->logInAccount($email, $password);

    if ($check === false) {
        throw new Exception('Impossible de se connecter');
    }
    else {
        header('Location: index.php');
    }
}

function logOutAction(){
    $userManager = new UserManager();
    
    $check = $userManager->logout();

    if (!$check) {
        throw new Exception('Impossible de de se déconnecter');
    }
    else {
        header('Location: index.php');
    }
}

function accountPage(){

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
        'userInfos' => $user,
        'posts' => $posts,
        'comments' => $comments,
    ]);
}

function changePP($id, $profilepicture)
{
    if($id = $_SESSION['id']){
        $id = $_SESSION['id'];
        $userManager = new UserManager(); 
        $user = $userManager->editPP($id, $profilepicture);
        
        if ($affectedLines === false) {
            throw new Exception("Impossible de changer l'image");
        }
        else {
            header('Location: account/' . $id);
        }
    }
    else{
        echo("vous n'etes pas le bon utilisateur");
    }

}

function changeBio($id, $bio)
{
    if($id = $_SESSION['id']){
        $id = $_SESSION['id'];
        $userManager = new UserManager(); 
        $user = $userManager->editBio($id, $bio);
        
        if ($affectedLines === false) {
            throw new Exception("Impossible de changer la bio");
        }
        else {
            header('Location: account/' . $id);
        }
    }
    else{
        echo("vous n'etes pas le bon utilisateur");
    }

}


function home(){
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $postManager = new PostManager(); // Création d'un objet
    $posts = $postManager->getPosts(); // Appel d'une fonction de cet objet

    echo $twig->render('home.html.twig', [
        'post' => $posts,
        'userid' => $_SESSION['id'] ?? null,
    ]);
}



