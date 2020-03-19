<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Controller\AffectationController;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *          
 * normalizationContext={"groups"={"read"}},
 *          denormalizationContext={"groups"={"write"}},
 * 
*           collectionOperations={
*              "get"={"security"="is_granted('ROLE_ADMIN_PARTENAIRE')",
*                    "security_message"="vous n'etes pas autorisé à acceder à ce service",
*                    },
*                  
 *          "post"={
 *                  "controller"=AffectationController::class
 *                 }
 *  },
 *          itemOperations={
*                "get"={"security"="is_granted('ROLE_ADMIN_PARTENAIRE')",
    *                  "security_message"="vous n'etes pas autorisé à acceder à cet service",
    *                  "normalization_context"={"groups"={"read"}}
 *                     },
 * 
 *               "put"={
 *                      "controller"=AffectationController::class
 *                     }
 *               }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AffectationCompteRepository")
 */
class AffectationCompte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"read","write"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"read","write"})
     */
    private $dateFin;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="affectationComptes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read", "write"})
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userAfect")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read","write"})
     */
    private $userAfect;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userAfectCompte")
     * @Groups({"read","write"})
     */
    private $userAfecteCompte;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

   

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getUserAfect(): ?User
    {
        return $this->userAfect;
    }

    public function setUserAfect(?User $userAfect): self
    {
        $this->userAfect = $userAfect;

        return $this;
    }

    public function getUserAfecteCompte(): ?User
    {
        return $this->userAfecteCompte;
    }

    public function setUserAfecteCompte(?User $userAfecteCompte): self
    {
        $this->userAfecteCompte = $userAfecteCompte;

        return $this;
    }

 
}
