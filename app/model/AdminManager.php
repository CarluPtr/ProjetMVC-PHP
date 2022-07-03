<?php

namespace Model;

class AdminManager extends Manager
{
    public function deleteComment(int $id)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('DELETE FROM comments WHERE id = ?');
        $affectedLines = $comments->execute([$id]);

        return $affectedLines;
    }

    public function deletePost(int $id)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('DELETE FROM posts WHERE id = ?');
        $affectedLines = $comments->execute([$id]);

        return $affectedLines;
    }

    public function validateComment(int $id)
    {
        $db = $this->dbConnect();
        $stmt = $db->prepare('UPDATE comments SET is_valid = 1 WHERE id = ?');
        $affectedLines = $stmt->execute([$id]);

        return $affectedLines;
    }
}
