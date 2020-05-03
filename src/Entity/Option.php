<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OptionRepository")
 * @ORM\Table(name="`option`")
 */
class Option
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VoitureOption", mappedBy="option", orphanRemoval=true)
     */
    private $voitureOptions;

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
        $this->voitureOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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
            $voitureOption->setOption($this);
        }

        return $this;
    }

    public function removeVoitureOption(VoitureOption $voitureOption): self
    {
        if ($this->voitureOptions->contains($voitureOption)) {
            $this->voitureOptions->removeElement($voitureOption);
            // set the owning side to null (unless already changed)
            if ($voitureOption->getOption() === $this) {
                $voitureOption->setOption(null);
            }
        }

        return $this;
    }
}
