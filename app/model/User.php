<?php

namespace Model;
use Datetime;

class User
{

    protected $idUser;
    protected $isAdmin;
    protected $email;
    protected $password;
    protected $username;
    protected $prenom;
    protected $nom;
    protected $description;
    protected $profilePicture;
    protected $dateInscription;

    public function __construct()
    {
        $this->dateInscription = new Datetime();
    }

  
    // GETTERS //

    public function getIdUser(): int
    {

        return $this->idUser;
    }

    public function getIsAdmin(): bool
    {

        return $this->isAdmin;
    }

    public function getEmail(): string
    {

        return $this->email;
    }

    public function getPassword(): string
    {

        return $this->password;
    }

    public function getPrenom(): string
    {

        return $this->prenom;
    }

    public function getNom(): string
    {

        return $this->nom;
    }

    public function getDateInscription(): \Datetime
    {

        return $this->dateInscription;
    }


    public function getUsername(): string
    {

        return $this->username;
    }

    public function getDescription(): string
    {

        return $this->description;
    }

    public function getProfilePicture(): string
    {

        return $this->profilePicture;
    }



    // SETTERS //

    public function setIdUser(int $idUser)
    {

        $idUser = (int) $idUser;

        if ($idUser > 0) {
            $this->idUser = $idUser;
        }
    }

    public function setIsAdmin(bool $isAdmin)
    {

        $isAdmin = (bool) $isAdmin;

        $this->isAdmin = $isAdmin;
    }


    public function setEmail(string $email)
    {

        if (is_string($email)) {
            $this->email = $email;
        }
    }

    public function setPassword(string $password)
    {

        if (is_string($password)) {
            $this->password = $password;
        }
    }

    public function setPrenom(string $prenom)
    {

        if (is_string($prenom)) {
            $this->prenom = $prenom;
        }
    }

    public function setNom(string $nom)
    {

        if (is_string($nom)) {
            $this->nom = $nom;
        }
    }

    public function setDateInscription(\DateTime $dateInscription)
    {

        $this->dateInscription = $dateInscription->format('d.m.Y');
    }


    public function setUsername(string $username)
    {

        if (is_string($username)) {
            $this->username = $username;
        }
    }


    public function setDescription(string $description)
    {

        if (is_string($description)) {
            $this->description = $description;
        }
    }

}