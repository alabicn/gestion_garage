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
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_fabrication;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $kilometrage;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $est_vendue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type_carosserie;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_portes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $src_photo_principal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alt_photo_principal;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="voiture")
     */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", mappedBy="voitures")
     */
    private $options;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Modele", inversedBy="voitures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $modele;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garage", inversedBy="voitures")
     */
    private $garage;

    public function __construct()
    {
        $this->photo = new ArrayCollection();
        $this->options = new ArrayCollection();
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
        return $this->date_fabrication;
    }

    public function setDateFabrication(?\DateTimeInterface $date_fabrication): self
    {
        $this->date_fabrication = $date_fabrication;

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
        return $this->est_vendue;
    }

    public function setEstVendue(?\DateTimeInterface $est_vendue): self
    {
        $this->est_vendue = $est_vendue;

        return $this;
    }

    public function getTypeCarosserie(): ?string
    {
        return $this->type_carosserie;
    }

    public function setTypeCarosserie(?string $type_carosserie): self
    {
        $this->type_carosserie = $type_carosserie;

        return $this;
    }

    public function getNbPortes(): ?int
    {
        return $this->nb_portes;
    }

    public function setNbPortes(?int $nb_portes): self
    {
        $this->nb_portes = $nb_portes;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getSrcPhotoPrincipal(): ?string
    {
        return $this->src_photo_principal;
    }

    public function setSrcPhotoPrincipal(?string $src_photo_principal): self
    {
        $this->src_photo_principal = $src_photo_principal;

        return $this;
    }

    public function getAltPhotoPrincipal(): ?string
    {
        return $this->alt_photo_principal;
    }

    public function setAltPhotoPrincipal(?string $alt_photo_principal): self
    {
        $this->alt_photo_principal = $alt_photo_principal;

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

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addVoiture($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            $option->removeVoiture($this);
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
}
