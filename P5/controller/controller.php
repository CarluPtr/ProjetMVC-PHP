<?php

require_once('model/PostManager.php');
require_once('model/CommentManager.php');

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
  echo $twig->render('blog.html.twig', ['post' => $posts] );
}

function post()
{
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $postManager = new PostManager();
    $commentManager = new CommentManager();

    $post = $postManager->getPost($_GET['id']);
    $comments = $commentManager->getComments($_GET['id']);

    echo $twig->render('posts.html.twig', ['post' => $post, 'comment' => $comments] );
}

function addComment($postId, $author, $comment)
{
    $commentManager = new CommentManager();

    $affectedLines = $commentManager->postComment($postId, $author, $comment);

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    }
    else {
        header('Location: index.php?action=post&id=' . $postId);
    }
}

function home(){
    $loader = new FilesystemLoader('templates');
    $twig = new Environment($loader);

    $postManager = new PostManager(); // Création d'un objet
    $posts = $postManager->getPosts(); // Appel d'une fonction de cet objet

    echo $twig->render('home.html.twig', ['post' => $posts]);
}

