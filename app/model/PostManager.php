<?php

namespace Model;

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
        $req->execute([$postId]);
        $post = $req->fetch();

        return $post;
    }

    public function postPost(Post $post)
    {

        $db = $this->dbConnect();
        $postPDO = $db->prepare('INSERT INTO posts( title, content, img, user_id, creation_date) VALUES(?, ?, ?, ?, NOW())');
        $affectedLines = $postPDO->execute([$post->getTitle(), $post->getContent(), $post->getImg(), $post->getIdUser()]);

        return $affectedLines;
    }

    public function getUserPosts(int $userid)
    {
        $db = $this->dbConnect();
        $sql = 'SELECT id, title, content, img, user_id, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%i\') AS creation_date_fr FROM posts WHERE user_id ='.$userid.' ORDER BY creation_date_fr DESC';
        $req = $db->query($sql);

        return $req;
    }
}
