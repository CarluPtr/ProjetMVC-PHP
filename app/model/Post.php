<?php

namespace Model;

class Post
{

    protected $idPost;
    protected $idUser;
    protected $title;
    protected $content;
    protected $img;
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


    public function getTitle(): string
    {

        return $this->title;
    }

    public function getContent(): string
    {

        return $this->content;
    }

    public function getImg(): string
    {

        return $this->img;
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


    public function setTitle(string $title)
    {

        if (is_string($title)) {
            $this->title = $title;
        }
    }

    public function setImg(string $img)
    {

        if (is_string($img)) {
            $this->img = $img;
        }
    }

    public function setContent(string $content)
    {

        if (is_string($content)) {
            $this->content = $content;
        }
    }

    public function setDateCreation(\DateTime $dateCreation)
    {

        $this->dateCreation = $dateCreation->format('d.m.Y');
    }


}