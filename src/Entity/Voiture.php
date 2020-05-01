<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoitureRepository")
 */
class Voiture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $immatriculation;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFabrication;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kilometrage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $estVendue;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $typeCarosserie;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPortes;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $srcPhotoPrincipal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $altPhotoPrincipal;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="voiture")
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modele", inversedBy="voitures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $modele;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garage", inversedBy="voitures")
     */
    private $garage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VoitureOption", mappedBy="voiture", orphanRemoval=true, cascade={"persist"})
     */
    private $voitureOptions;

    public function __construct()
    {
        $this->photo = new ArrayCollection();
        $this->voitureOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(?string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getDateFabrication(): ?\DateTimeInterface
    {
        return $this->dateFabrication;
    }

    public function setDateFabrication(?\DateTimeInterface $dateFabrication): self
    {
        $this->dateFabrication = $dateFabrication;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(?int $kilometrage): self
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getEstVendue(): ?\DateTimeInterface
    {
        return $this->estVendue;
    }

    public function setEstVendue(?\DateTimeInterface $estVendue): self
    {
        $this->estVendue = $estVendue;

        return $this;
    }

    public function getTypeCarosserie(): ?string
    {
        return $this->typeCarosserie;
    }

    public function setTypeCarosserie(?string $typeCarosserie): self
    {
        $this->typeCarosserie = $typeCarosserie;

        return $this;
    }

    public function getNbPortes(): ?int
    {
        return $this->nbPortes;
    }

    public function setNbPortes(?int $nbPortes): self
    {
        $this->nbPortes = $nbPortes;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getSrcPhotoPrincipal(): ?string
    {
        return $this->srcPhotoPrincipal;
    }

    public function setSrcPhotoPrincipal(?string $srcPhotoPrincipal): self
    {
        $this->srcPhotoPrincipal = $srcPhotoPrincipal;

        return $this;
    }

    public function getAltPhotoPrincipal(): ?string
    {
        return $this->altPhotoPrincipal;
    }

    public function setAltPhotoPrincipal(?string $altPhotoPrincipal): self
    {
        $this->altPhotoPrincipal = $altPhotoPrincipal;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhoto(): Collection
    {
        return $this->photo;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photo->contains($photo)) {
            $this->photo[] = $photo;
            $photo->setVoiture($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photo->contains($photo)) {
            $this->photo->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getVoiture() === $this) {
                $photo->setVoiture(null);
            }
        }

        return $this;
    }

    public function getModele(): ?Modele
    {
        return $this->modele;
    }

    public function setModele(?Modele $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getGarage(): ?Garage
    {
        return $this->garage;
    }

    public function setGarage(?Garage $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    /**
     * @return Collection|VoitureOption[]
     */
    public function getVoitureOptions(): Collection
    {
        return $this->voitureOptions;
    }

    public function addVoitureOption(VoitureOption $voitureOption): self
    {
        if (!$this->voitureOptions->contains($voitureOption)) {
            $this->voitureOptions[] = $voitureOption;
            $voitureOption->setVoiture($this);
        }

        return $this;
    }

    public function removeVoitureOption(VoitureOption $voitureOption): self
    {
        if ($this->voitureOptions->contains($voitureOption)) {
            $this->voitureOptions->removeElement($voitureOption);
            // set the owning side to null (unless already changed)
            if ($voitureOption->getVoiture() === $this) {
                $voitureOption->setVoiture(null);
            }
        }

        return $this;
    }
}
