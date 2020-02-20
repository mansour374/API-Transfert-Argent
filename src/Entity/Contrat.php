<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ContratRepository")
 */
class Contrat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Partenaire", cascade={"persist", "remove"})
     */
    private $contrats;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TermeContrat", inversedBy="contrats")
     */
    private $closes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateCreation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContrats(): ?Partenaire
    {
        return $this->contrats;
    }

    public function setContrats(?Partenaire $contrats): self
    {
        $this->contrats = $contrats;

        return $this;
    }

    public function getCloses(): ?TermeContrat
    {
        return $this->closes;
    }

    public function setCloses(?TermeContrat $closes): self
    {
        $this->closes = $closes;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->DateCreation;
    }

    public function setDateCreation(\DateTimeInterface $DateCreation): self
    {
        $this->DateCreation = $DateCreation;

        return $this;
    }
}
