<?php
require_once("model/Manager.php");

class RegisterManager extends Manager
{

    public function registerAccount($username, $email, $password)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('INSERT INTO user(username, email, password) VALUES(?, ?, ?)');
        $affectedLines = $comments->execute(array($username, $email, $password));

        return $affectedLines;
    }

}