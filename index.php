<?php
require_once 'app/controller/controller.php';
require __DIR__ . '/vendor/autoload.php';

session_start();

try { // On essaie de faire des choses
    if (isset($_GET['action'])) {
        if ($_GET['action'] == 'listPosts') {
            listPosts();
        }
        elseif ($_GET['action'] == 'post') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                post();
            }
            else {
                // Erreur ! On arrête tout, on envoie une exception, donc au saute directement au catch
                throw new Exception('Aucun identifiant de billet envoyé');
            }
        }
        elseif ($_GET['action'] == 'addComment') {
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
        elseif ($_GET['action'] == 'addPost') {
            if (!empty($_POST['inputTitle']) && !empty($_POST['inputContent']) && !empty($_FILES['postimg']['tmp_name'])) {
                $postimg = $_FILES['postimg'];
                addPost(strip_tags($_SESSION['id']), strip_tags($_POST['inputTitle']), strip_tags($_POST['inputContent']), $postimg);
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }

        }
        elseif ($_GET['action'] == 'register'){
            register();
        }
        elseif ($_GET['action'] == 'login'){
            logIn();
        }
        elseif ($_GET['action'] == 'registerAccount'){
            if(!empty($_POST['prenom']) && !empty($_POST['nom']) &&  !empty($_POST['username']) && !empty($_POST['email'])&& !empty($_POST['password']) && !empty($_POST['password_retype'])){
                registerAction(strip_tags($_POST['prenom']), strip_tags($_POST['nom']), strip_tags($_POST['username']), $_POST['email'], $_POST['password'], $_POST['password_retype']);
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }
        }
        elseif ($_GET['action'] == 'sendemail'){
            if(!empty($_POST['inputName'])&& !empty($_POST['inputLastName'])&& !empty($_POST['inputEmail'])&& !empty($_POST['inputSubject'])&& !empty($_POST['inputMessage'])){
                emailController(strip_tags($_POST['inputName']), strip_tags($_POST['inputLastName']), $_POST['inputEmail'], strip_tags($_POST['inputSubject']), strip_tags($_POST['inputMessage']));
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }
        }
        elseif ($_GET['action'] == 'logInAccount'){
            if(!empty($_POST['email'])&& !empty($_POST['password'])){
                logInAction($_POST['email'], $_POST['password']);
            }
            else {
                // Autre exception
                throw new Exception('Tous les champs ne sont pas remplis !');
            }
        }
        elseif ($_GET['action'] == 'logout'){
            logOutAction();
        }
        elseif ($_GET['action'] == 'account'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                accountPage($_GET['id']);
            }
        }
        elseif ($_GET['action'] == 'admin'){
            if($_SESSION['is_admin']){
                adminPannel();
            }
            else{
                throw new Exception("Vous n'avez pas la permission d'accéder à cette page !");
            }
        }
        elseif ($_GET['action'] == 'deletecom'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                deleteComController($_GET['id']);
            }
        }
        elseif ($_GET['action'] == 'deletepost'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                deletePostController($_GET['id']);
            }
        }
        elseif ($_GET['action'] == 'validate'){
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                validateComController($_GET['id']);
            }
        }
        elseif ($_GET['action'] == 'changePP') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                if(!empty($_FILES['profilepic']['tmp_name'])){
                    $profilepicture = $_FILES['profilepic'];
                    changePP($_GET['id'], $profilepicture);
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
        elseif ($_GET['action'] == 'changeBio') {
            if (isset($_GET['id']) && $_GET['id'] > 0) {
                if (!empty($_POST['userbio'])){
                    $bio = strip_tags($_POST['userbio']);
                    changeBio($_GET['id'], $bio);
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
    echo 'Erreur : ' . $e->getMessage();
}
