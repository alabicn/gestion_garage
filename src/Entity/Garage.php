<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GarageRepository")
 */
class Garage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $code_postal;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $pays;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Voiture", mappedBy="garage")
     */
    private $voitures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vendeur", mappedBy="garage", orphanRemoval=true)
     */
    private $vendeurs;

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
        $this->vendeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(?string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * @return Collection|Voiture[]
     */
    public function getVoitures(): Collection
    {
        return $this->voitures;
    }

    public function addVoiture(Voiture $voiture): self
    {
        if (!$this->voitures->contains($voiture)) {
            $this->voitures[] = $voiture;
            $voiture->setGarage($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): self
    {
        if ($this->voitures->contains($voiture)) {
            $this->voitures->removeElement($voiture);
            // set the owning side to null (unless already changed)
            if ($voiture->getGarage() === $this) {
                $voiture->setGarage(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vendeur[]
     */
    public function getVendeurs(): Collection
    {
        return $this->vendeurs;
    }

    public function addVendeur(Vendeur $vendeur): self
    {
        if (!$this->vendeurs->contains($vendeur)) {
            $this->vendeurs[] = $vendeur;
            $vendeur->setGarage($this);
        }

        return $this;
    }

    public function removeVendeur(Vendeur $vendeur): self
    {
        if ($this->vendeurs->contains($vendeur)) {
            $this->vendeurs->removeElement($vendeur);
            // set the owning side to null (unless already changed)
            if ($vendeur->getGarage() === $this) {
                $vendeur->setGarage(null);
            }
        }

        return $this;
    }
}
