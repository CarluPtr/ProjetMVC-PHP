<?php

namespace Model;

class Comment
{

    protected $idPost;
    protected $idUser;
    protected $comment;
    protected $isValid;
    protected $dateCreation;

    public function __construct()
    {
        $this->dateCreation = new Datetime();
    }

  
    // GETTERS //

    public function getIdPost(): int
    {

        return $this->idPost;
    }

    public function getIdUser(): int
    {

        return $this->idUser;
    }


    public function getIsValid(): bool
    {

        return $this->isValid;
    }

    public function getComment(): string
    {

        return $this->comment;
    }


    public function getDateCreation(): \Datetime
    {

        return $this->dateCreation;
    }






    // SETTERS //

    public function setIdPost(int $idPost)
    {

        $idPost = (int) $idPost;

        if ($idPost > 0) {
            $this->idPost = $idPost;
        }
    }

    public function setIdUser(int $idUser)
    {

        $idUser = (int) $idUser;

        if ($idUser > 0) {
            $this->idUser = $idUser;
        }
    }


    public function setIsValid(bool $isValid)
    {

        $isValid = (bool) $isValid;

        $this->isValid = $isValid;
    }


    public function setComment(string $comment)
    {

        if (is_string($comment)) {
            $this->comment = $comment;
        }
    }

    public function setDateCreation(\DateTime $dateCreation)
    {

        $this->dateCreation = $dateCreation->format('d.m.Y');
    }


}