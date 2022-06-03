<?php
require_once("model/Manager.php");

class CommentManager extends Manager
{
    public function getComments(int $postId)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT username, profile_picture, user_id, comment, is_valid, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%i\') AS comment_date_fr FROM comments LEFT JOIN user ON comments.user_id = user.id WHERE post_id = ? ORDER BY comment_date DESC');
        $comments->execute(array($postId));

        return $comments;
    }
    public function getAllNewComments()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT comments.id, username, profile_picture, user_id, comment, is_valid, post_id, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%i\') AS comment_date_fr FROM comments LEFT JOIN user ON comments.user_id = user.id WHERE is_valid = 0 ORDER BY comment_date DESC');

        return $req;
    }

    public function getAllValidedComments()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT comments.id, username, profile_picture, user_id, comment, is_valid, post_id, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%i\') AS comment_date_fr FROM comments LEFT JOIN user ON comments.user_id = user.id WHERE is_valid = 1 ORDER BY comment_date DESC');

        return $req;
    }


    public function postComment($postId, $utilisateur, $comment, $is_admin)
    {
        // Admin comments are valid by default
        $db = $this->dbConnect();
        $comments = $db->prepare("INSERT INTO comments(post_id, user_id, comment, comment_date, is_valid) VALUES(?, ?, ?, NOW(), $is_admin)");
        $affectedLines = $comments->execute(array($postId, $utilisateur, $comment));

        return $affectedLines;
    }

    public function getUserComments(int $userid)
    {
        $db = $this->dbConnect();
        $sql = 'SELECT id, user_id, comment, post_id, is_valid, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%i\') AS comment_date_fr FROM comments WHERE user_id =' . $userid . ' ORDER BY comment_date_fr DESC';
        $req = $db->query($sql);

        return $req;
    }

}