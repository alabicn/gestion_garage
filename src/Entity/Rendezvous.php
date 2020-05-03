<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RendezvousRepository")
 */
class Rendezvous
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateRDV;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $estVenu;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Garage", inversedBy="rendezvouses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Garage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="rendezvouses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vendeur", inversedBy="rendezvouses")
     */
    private $vendeur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRDV(): ?\DateTimeInterface
    {
        return $this->dateRDV;
    }

    public function setDateRDV(\DateTimeInterface $dateRDV): self
    {
        $this->dateRDV = $dateRDV;

        return $this;
    }

    public function getEstVenu(): ?bool
    {
        return $this->estVenu;
    }

    public function setEstVenu(?bool $estVenu): self
    {
        $this->estVenu = $estVenu;

        return $this;
    }

    public function getGarage(): ?Garage
    {
        return $this->Garage;
    }

    public function setGarage(?Garage $Garage): self
    {
        $this->Garage = $Garage;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getVendeur(): ?Vendeur
    {
        return $this->vendeur;
    }

    public function setVendeur(?Vendeur $vendeur): self
    {
        $this->vendeur = $vendeur;

        return $this;
    }
}
