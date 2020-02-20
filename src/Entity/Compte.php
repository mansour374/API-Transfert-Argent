<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\CompteControle;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiFilter(SearchFilter::class, properties={"numCompte":"partial"})
 * @ApiResource(
 * 
 * normalizationContext={"groups"={"read"}},
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
 *                  "controller"=CompteControle::class
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
 *                      "controller"=CompteControle::class
 *                     }
 *               }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CompteRepository")
 */
class Compte
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"read"})
     */
    private $numCompte;

    /**
     * @ORM\Column(type="integer", length=255)
     * 
     * @Groups({"read", "write"})
     */
    private $solde;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    private $createadAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comptes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="comptes", cascade={"persist", "remove"})
     * @Groups({"read","write"})
     */
    private $partenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="compte", cascade={"persist", "remove"})
     * @Groups({"read"})
     */
    private $depots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AffectationCompte", mappedBy="compte")
     */
    private $affectationComptes;

   
    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $montantPlafond;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="compteEnvoie")
     */
    private $transactionEnvoie;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="compteRetrait")
     */
    private $transactionRetrait;

  
    public function __construct()
    {
        $this->depots = new ArrayCollection();                                                            
            $this->createadAt = new \DateTime('@'.strtotime('now'));
            $this->affectationComptes = new ArrayCollection();
            $this->transactionEnvoie = new ArrayCollection();
            $this->transactionRetrait = new ArrayCollection();
            
          
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCompte(): ?string
    {
        return $this->numCompte;
    }

    public function setNumCompte(string $numCompte): self
    {
        $this->numCompte = $numCompte;

        return $this;
    }

    public function getSolde():  ?int
    {
        return $this->solde;
    }

    public function setSolde(string $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getCreateadAt(): ?\DateTimeInterface
    {
        return $this->createadAt;
    }

    public function setCreateadAt(\DateTimeInterface $createadAt): self
    {
        $this->createadAt = $createadAt;

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

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setCompte($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getCompte() === $this) {
                $depot->setCompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AffectationCompte[]
     */
    public function getAffectationComptes(): Collection
    {
        return $this->affectationComptes;
    }

    public function addAffectationCompte(AffectationCompte $affectationCompte): self
    {
        if (!$this->affectationComptes->contains($affectationCompte)) {
            $this->affectationComptes[] = $affectationCompte;
            $affectationCompte->setCompte($this);
        }

        return $this;
    }

    public function removeAffectationCompte(AffectationCompte $affectationCompte): self
    {
        if ($this->affectationComptes->contains($affectationCompte)) {
            $this->affectationComptes->removeElement($affectationCompte);
            // set the owning side to null (unless already changed)
            if ($affectationCompte->getCompte() === $this) {
                $affectationCompte->setCompte(null);
            }
        }

        return $this;
    }

   

    public function getMontantPlafond(): ?int
    {
        return $this->montantPlafond;
    }

    public function setMontantPlafond(int $montantPlafond): self
    {
        $this->montantPlafond = $montantPlafond;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionEnvoie(): Collection
    {
        return $this->transactionEnvoie;
    }

    public function addTransactionEnvoie(Transaction $transactionEnvoie): self
    {
        if (!$this->transactionEnvoie->contains($transactionEnvoie)) {
            $this->transactionEnvoie[] = $transactionEnvoie;
            $transactionEnvoie->setCompteEnvoie($this);
        }

        return $this;
    }

    public function removeTransactionEnvoie(Transaction $transactionEnvoie): self
    {
        if ($this->transactionEnvoie->contains($transactionEnvoie)) {
            $this->transactionEnvoie->removeElement($transactionEnvoie);
            // set the owning side to null (unless already changed)
            if ($transactionEnvoie->getCompteEnvoie() === $this) {
                $transactionEnvoie->setCompteEnvoie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactionRetrait(): Collection
    {
        return $this->transactionRetrait;
    }

    public function addTransactionRetrait(Transaction $transactionRetrait): self
    {
        if (!$this->transactionRetrait->contains($transactionRetrait)) {
            $this->transactionRetrait[] = $transactionRetrait;
            $transactionRetrait->setCompteRetrait($this);
        }

        return $this;
    }

    public function removeTransactionRetrait(Transaction $transactionRetrait): self
    {
        if ($this->transactionRetrait->contains($transactionRetrait)) {
            $this->transactionRetrait->removeElement($transactionRetrait);
            // set the owning side to null (unless already changed)
            if ($transactionRetrait->getCompteRetrait() === $this) {
                $transactionRetrait->setCompteRetrait(null);
            }
        }

        return $this;
    }

}
