<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
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
    private $src_photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $alt_photo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Voiture", inversedBy="photo")
     */
    private $voiture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSrcPhoto(): ?string
    {
        return $this->src_photo;
    }

    public function setSrcPhoto(?string $src_photo): self
    {
        $this->src_photo = $src_photo;

        return $this;
    }

    public function getAltPhoto(): ?string
    {
        return $this->alt_photo;
    }

    public function setAltPhoto(?string $alt_photo): self
    {
        $this->alt_photo = $alt_photo;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): self
    {
        $this->voiture = $voiture;

        return $this;
    }
}
