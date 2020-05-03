<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoitureOptionRepository")
 */
class VoitureOption
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Voiture", inversedBy="voitureOptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $voiture;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Option", inversedBy="voitureOptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $option;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombre;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getOption(): ?Option
    {
        return $this->option;
    }

    public function setOption(?Option $option): self
    {
        $this->option = $option;

        return $this;
    }
}
