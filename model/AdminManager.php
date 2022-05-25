<?php
require_once("model/Manager.php");

class AdminManager extends Manager
{
    public function deleteComment($id)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('DELETE FROM comments WHERE id = ?');
        $affectedLines = $comments->execute(array($id));

        return $affectedLines;
    }

    public function deletePost($id)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('DELETE FROM posts WHERE id = ?');
        $affectedLines = $comments->execute(array($id));

        return $affectedLines;
    }
}