<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 * 
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type_utilisateur", type="integer")
 * @ORM\DiscriminatorMap({
 *     "0":"App\Entity\Utilisateur",
 *     "1":"App\Entity\Vendeur",
 *     "2":"App\Entity\Client",
 * })

 */
class Utilisateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $needRGPD;

    /**
     * @ORM\Column(type="boolean")
     */
    private $estActive;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function getNeedRGPD(): ?\DateTimeInterface
    {
        return $this->needRGPD;
    }

    public function setNeedRGPD(\DateTimeInterface $needRGPD): self
    {
        $this->needRGPD = $needRGPD;

        return $this;
    }

    public function getEstActive(): ?bool
    {
        return $this->estActive;
    }

    public function setEstActive(bool $estActive): self
    {
        $this->estActive = $estActive;

        return $this;
    }
}
