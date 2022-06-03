<?php
require_once("model/Manager.php");

class PostManager extends Manager
{
    public function getPosts()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT id, title, content, img, user_id, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%i\') AS creation_date_fr FROM posts ORDER BY creation_date DESC LIMIT 0, 5');

        return $req;
    }

    public function getPost(int $postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT posts.id, username, user_id, title, content, img, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%i\') AS creation_date_fr FROM posts LEFT JOIN user ON posts.user_id = user.id WHERE posts.id = ?');
        $req->execute(array($postId));
        $post = $req->fetch();

        return $post;
    }

    public function postPost($utilisateur, $title, $content, $postimg)
    {
        $image = $postimg['tmp_name'];
        $img = file_get_contents($image);


        $db = $this->dbConnect();
        $post = $db->prepare('INSERT INTO posts( title, content, img, user_id, creation_date) VALUES(?, ?, ?, ?, NOW())');
        $affectedLines = $post->execute(array($title, $content, $img, $utilisateur));

        return $affectedLines;
    }

    public function getUserPosts(int $userid)
    {
        $db = $this->dbConnect();
        $sql = 'SELECT id, title, content, img, user_id, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%i\') AS creation_date_fr FROM posts WHERE user_id =' . $userid . ' ORDER BY creation_date_fr DESC';
        $req = $db->query($sql);

        return $req;
    }


}