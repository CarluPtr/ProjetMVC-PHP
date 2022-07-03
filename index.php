<?php
require_once 'app/controller/controller.php';
require __DIR__ . '/vendor/autoload.php';

if(!isset($_SESSION)){session_start();}

try { // On essaie de faire des choses
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? null;
    if (isset($action)) {
        if ($action == 'listPosts') {
            listPosts();
        }
        elseif ($action == 'post') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                post(
                    filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING),
                );
            }
            else {
                // Erreur ! On arrête tout, on envoie une exception, donc au saute directement au catch
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        elseif ($action == 'addComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                if (!empty($_POST['comment'])) {
                    addComment(
                        filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING),
                        filter_var($_SESSION['id']),
                        filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING),
                    );    
                }
                else {
                    // Autre exception
                    throw new Exception('Tous les champs ne sont pas remplis !');
                }
            }
            else {
                // Autre exception
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        elseif ($action == 'addPost') {
            if (!empty($_POST['inputTitle']) && !empty($_POST['inputContent']) && !empty($_FILES['postimg']['tmp_name'])) {
                $postimg = $_FILES['postimg'];
                addPost(
                    filter_var($_SESSION['id']),
                    filter_input(INPUT_POST, 'inputTitle', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'inputContent', FILTER_SANITIZE_STRING),
                    $postimg
                );
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }

        }
        elseif ($action == 'register'){
            register();
        }
        elseif ($action == 'login'){
            logIn();
        }
        elseif ($action == 'registerAccount'){
            if(!empty($_POST['prenom']) && !empty($_POST['nom']) &&  !empty($_POST['username']) && !empty($_POST['email'])&& !empty($_POST['password']) && !empty($_POST['password_retype'])){
                registerAction(
                    filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'password_retype', FILTER_SANITIZE_STRING),
                );
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }
        }
        elseif ($action == 'sendemail'){
            if(!empty($_POST['inputName'])&& !empty($_POST['inputLastName'])&& !empty($_POST['inputEmail'])&& !empty($_POST['inputSubject'])&& !empty($_POST['inputMessage'])){
                emailController(
                    filter_input(INPUT_POST, 'inputName', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'inputLastName', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'inputSubject', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'inputMessage', FILTER_SANITIZE_STRING),
                );    
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }
        }
        elseif ($action == 'logInAccount'){
            if(!empty($_POST['email'])&& !empty($_POST['password'])){
                logInAction(
                    filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING),
                    filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING),
                );
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }
        }
        elseif ($action == 'logout'){
            logOutAction();
        }
        elseif ($action == 'account'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                accountPage(
                    filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING),
                );
            }
        }
        elseif ($action == 'admin'){
            if($_SESSION['is_admin']){
                adminPannel();
            }
            else{
                throw new Exception("Vous n'avez pas la permission d'accéder à cette page !");
            }
        }
        elseif ($action == 'deletecom'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                deleteComController(
                    filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING),
                );
            }
        }
        elseif ($action == 'deletepost'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                deletePostController(
                    filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING),
                );
            }
        }
        elseif ($action == 'validate'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                validateComController(
                    filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING),
                );
            }
        }
        elseif ($action == 'changePP') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                if(!empty($_FILES['profilepic']['tmp_name'])){
                    $profilepicture = $_FILES['profilepic'];
                    changePP(
                        filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING),
                        $profilepicture);
                }
                else {
                    throw new Exception('Veuillez sélectionner une photo de profil !');
                }    
            }
            else {
                // Autre exception
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        elseif ($action == 'changeBio') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                if (!empty($_POST['userbio'])){
                    $bio = strip_tags($_POST['userbio']);
                    changeBio(
                        filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING),
                        $bio);
                }
                else {
                    throw new Exception('Veuillez écrire une description !');
                }    
            }
            else {
                // Autre exception
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }   
    }
    else {
        home();
    }
}
catch(Exception $e) { // S'il y a eu une erreur, alors...
    throw new Exception('Erreur : ' . $e->getMessage());
}
