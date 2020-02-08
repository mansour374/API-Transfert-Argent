<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\DepotControle;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * attributes={"force_eager"=false},
 *         normalizationContext={"groups"={"read"}},
 *          denormalizationContext={"groups"={"write"}},
 * 
*           collectionOperations={
*              "get"={"security"="is_granted('ROLE_CAISSIER')",
*                    "security_message"="vous n'etes pas autorisé à acceder à cet service",
*                    },
*                  
 *          "post"={
 *                  "security"="is_granted('ROLE_CAISSIER')",
*                   "security_message"="vous n'etes pas autorisé à acceder à cet service",
 *                  "controller"=DepotControle::class
 *                 }
 *  },
 *          itemOperations={
*                "get"={"security"="is_granted('ROLE_CAISSIER')",
    *                  "security_message"="vous n'etes pas autorisé à acceder à cet service",
    *                  "normalization_context"={"groups"={"read"}}
 *                     },
 * 
 *               "put"={
 *                     
 *                      "controller"=DepotControle::class
 *                     }
 *               }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 */
class Depot
{
   
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     *  @Groups({"read"})
     */
    private $depotAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="depots",  fetch="EAGER")
     *  @Groups({"read", "write"})
     *  @ApiProperty(attributes={"fetchEager": true})
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="depots")
     *  @Groups({"read"})
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read", "write"})
     */
    private $montantDepot;

    public function __construct(){
        $this->depotAt = new \DateTime('@'.strtotime('now'));
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepotAt(): ?\DateTimeInterface
    {
        return $this->depotAt;
    }

    public function setDepotAt(\DateTimeInterface $depotAt): self
    {
        $this->depotAt = $depotAt;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMontantDepot(): ?int
    {
        return $this->montantDepot;
    }

    public function setMontantDepot(int $montantDepot): self
    {
        $this->montantDepot = $montantDepot;

        return $this;
    }
}
