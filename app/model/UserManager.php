<?php

namespace Model;

class UserManager extends Manager
{
    public function registerAccount(User $user)
    {
        $username_lenght = strlen($user->getUsername());
        $password = strip_tags($user->getPassword());
        $password_retype = strip_tags($_POST['password_retype']);
        $email = $user->getEmail();

        if ($username_lenght <= 255) {
            $db = $this->dbConnect();
            $reqemail = $db->prepare('SELECT * FROM user WHERE email = ?');
            $reqemail->execute([$email]);
            $email_exist = $reqemail->rowCount();

            if ($email_exist == 0) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // Si l'email est de la bonne forme
                    if ($password === $password_retype) { // si les deux mdp saisis sont bon
                    $cost = ['cost' => 12];
                        $password = password_hash($password, PASSWORD_BCRYPT, $cost);

                        $db = $this->dbConnect();
                        $comments = $db->prepare('INSERT INTO user(prenom, nom, username, email, password, is_admin, date_inscription) VALUES(?, ?, ?, ?, ?, ?, NOW())');
                        $affectedLines = $comments->execute([$user->getPrenom(), $user->getNom(), $user->getUsername(), $email, $password, $user->getIsAdmin()]);

                        return $affectedLines;
                    } else {
                        throw new Exception('Mot de passe non correspondant.');
                    }
                }
            } else {
                throw new Exception('Cet email est déjà pris.');
            }
        } else {
            throw new Exception('Le nom d\'utilisateur est trop long. (maximum 255 caractères)');
        }
    }

    public function logInAccount($email, $password)
    {
        if (!empty($_POST['email']) && !empty($_POST['password'])) { // Si il existe les champs email, password et qu'il sont pas vident
            $password = strip_tags($password);
            $email = strtolower($email);

            // On regarde si l'utilisateur est inscrit dans la table utilisateurs
            $db = $this->dbConnect();
            $check = $db->prepare('SELECT id, username, email, is_admin, password FROM user WHERE email = ?');
            $check->execute([$email]);
            $data = $check->fetch();
            $row = $check->rowCount();

            // Si > à 0 alors l'utilisateur existe
            if ($row > 0) {
                // Si le mail est bon niveau format
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Si le mot de passe est le bon
                    if (password_verify($password, $data['password'])) {
                        session_start();
                        $_SESSION['username'] = $data['username'];
                        $_SESSION['id'] = $data['id'];
                        $_SESSION['is_admin'] = $data['is_admin'];
                    } else {
                        throw new Exception('Mauvais mot de passe');
                    }
                } else {
                    throw new Exception('Format email non correspondant');
                }
            } else {
                throw new Exception("Le user n'existe pas");
            }
        }
    }

    public function logout()
    {
        session_start();

        try {
            session_destroy();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUser(int $id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, prenom, nom, username, email, profile_picture, description, is_admin, DATE_FORMAT(date_inscription, \'%d/%m/%Y\') AS date_inscription FROM user WHERE id = ?');
        $req->execute([$id]);
        $userInfos = $req->fetch();

        return $userInfos;
    }

    public function editPP($id, $profilepicture)
    {
        $image = $profilepicture['tmp_name'];
        $data = file_get_contents($image);

        $db = $this->dbConnect();
        $stmt = $db->prepare("UPDATE user SET profile_picture = ? WHERE id = '$id'");
        $affectedLines = $stmt->execute([$data]);

        return $affectedLines;
    }

    public function editBio(int $id, $bio)
    {
        $db = $this->dbConnect();
        $stmt = $db->prepare("UPDATE user SET description = ? WHERE id = '$id'");
        $affectedLines = $stmt->execute([$bio]);

        return $affectedLines;
    }
}
