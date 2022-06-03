<?php

require_once('model/PostManager.php');
require_once('model/CommentManager.php');
require_once('model/UserManager.php');
require_once('model/AdminManager.php');
require_once('model/MailManager.php');


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
    'is_admin' => $_SESSION['is_admin'] ?? null,
  ] );
}

function emailController($firstname, $birthname, $email, $subject, $message){

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
    $utilisateur = $_SESSION['id'];
    $is_admin = $_SESSION['is_admin'];

    $commentManager = new CommentManager();

    $affectedLines = $commentManager->postComment($postId, $utilisateur, $comment, $is_admin);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=post&id=' . $postId);
    }
}

function addPost($utilisateur, $title, $content, $postimg)
{
    $utilisateur = $_SESSION['id'];

    $postManager = new PostManager();

    $affectedLines = $postManager->postPost($utilisateur, $title, $content, $postimg);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le post !');
    }
    else {
        header('Location: index.php?action=listPosts');
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
        'is_admin' => $_SESSION['is_admin'] ?? null,
        'userInfos' => $user,
        'posts' => $posts,
        'comments' => $comments,
    ]);
}

function adminPannel(){

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
function deleteComController($id)
{
    
        $adminManager = new AdminManager(); 
        $admin = $adminManager->deleteComment($id);
        
        if ($affectedLines === false) {
            throw new Exception("Impossible de supprimer le commentaire");
        }
        else {
            header('Location: admin/');
        }

}

function deletePostController($id)
{
    
        $adminManager = new AdminManager(); 
        $admin = $adminManager->deletePost($id);
        
        if ($affectedLines === false) {
            throw new Exception("Impossible de supprimer le commentaire");
        }
        else {
            header('Location: admin/');
        }

}

function validateComController($id)
{
        $adminManager = new AdminManager(); 
        $admin = $adminManager->validateComment($id);
        
        if ($affectedLines === false) {
            throw new Exception("Impossible de valider le commentaire");
        }
        else {
            header('Location: admin/');
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
        'is_admin' => $_SESSION['is_admin'] ?? null,
    ]);
}



