<?php
require_once 'app/controller/controller.php';
require __DIR__ . '/vendor/autoload.php';

if(!isset($_SESSION)){session_start();}

try { // On essaie de faire des choses
    $action = $_GET['action']?? null;
    if (isset($action)) {
        if ($action == 'listPosts') {
            listPosts();
        }
        elseif ($action == 'post') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                post(strip_tags($_GET['id']));
            }
            else {
                // Erreur ! On arrête tout, on envoie une exception, donc au saute directement au catch
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        elseif ($action == 'addComment') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                if (!empty($_POST['comment'])) {
                    addComment(strip_tags($_GET['id']), strip_tags($_SESSION['id']), strip_tags($_POST['comment']));
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
                addPost(strip_tags($_SESSION['id']), strip_tags($_POST['inputTitle']), strip_tags($_POST['inputContent']), $postimg);
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
                registerAction(strip_tags($_POST['prenom']), strip_tags($_POST['nom']), strip_tags($_POST['username']), $_POST['email'], $_POST['password'], $_POST['password_retype']);
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }
        }
        elseif ($action == 'sendemail'){
            if(!empty($_POST['inputName'])&& !empty($_POST['inputLastName'])&& !empty($_POST['inputEmail'])&& !empty($_POST['inputSubject'])&& !empty($_POST['inputMessage'])){
                emailController(strip_tags($_POST['inputName']), strip_tags($_POST['inputLastName']), $_POST['inputEmail'], strip_tags($_POST['inputSubject']), strip_tags($_POST['inputMessage']));
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }
        }
        elseif ($action == 'logInAccount'){
            if(!empty($_POST['email'])&& !empty($_POST['password'])){
                logInAction($_POST['email'], $_POST['password']);
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
                accountPage(strip_tags($_GET['id']));
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
                deleteComController(strip_tags($_GET['id']));
            }
        }
        elseif ($action == 'deletepost'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                deletePostController(strip_tags($_GET['id']));
            }
        }
        elseif ($action == 'validate'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                validateComController(strip_tags($_GET['id']));
            }
        }
        elseif ($action == 'changePP') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                if(!empty($_FILES['profilepic']['tmp_name'])){
                    $profilepicture = $_FILES['profilepic'];
                    changePP(strip_tags($_GET['id']), $profilepicture);
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
                    changeBio(strip_tags($_GET['id']), $bio);
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
