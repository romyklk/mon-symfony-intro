<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * La classe est liée à une table en BDD
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * La clé primaire est noté Id()
     *
     * l'auto-incrémentantion est notée GeneratedValue()
     *
     * le type de la variable est noté Column(type="integer")
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Ici Column(type="string", length=20) veut dire que c'est un VARCHAR 20 non nul
     *
     * @ORM\Column(type="string", length=20)
     */
    private $pseudo;

    /**
     * ici (type="string", length=255) c'est un VARCHAR 255 non nul
     *
     * unique=true est une contrainte d'unicité ce qui veut dire
     * que 1 mail correspond à un user donc impossible d'avoir 2 user avec le même mail
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * ici la date est nullable=true car la date peut être nul
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthdate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }
}
