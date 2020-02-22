<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\TransactionControle;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *        
 * normalizationContext={"groups"={"read"}},
 *          denormalizationContext={"groups"={"write"}},
 * 
*           collectionOperations={
*              "get"={"security"="is_granted('ROLE_USER_PARTENAIRE')",
*                    "security_message"="vous n'etes pas autorisé à acceder à cet service",
*                    },
*                  
 *          "post"={
 *                  "security"="is_granted('ROLE_USER_PARTENAIRE')",
*                   "security_message"="vous n'etes pas autorisé à acceder à cet service",
 *                  "controller"=TransactionControle::class
 *                 }
 *  },
 *          itemOperations={
*                "get"={"security"="is_granted('ROLE_USER_PARTENAIRE')",
    *                  "security_message"="vous n'etes pas autorisé à acceder à cet service",
    *                  "normalization_context"={"groups"={"read"}}
 *                     },
 * 
 *               "put"={
 *                     
 *                      "controller"=TransactionControle::class
 *                     }
 *               }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $nomEnvoyeur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $prenomEnvoyeur;

    
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $codeEnvoie;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $fraisEnvoie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $nonBeneficiaire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $prenomBeneficiaire;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read", "write"})
     */
    private $telBeneficiaire;

  
    /**
     * @ORM\Column(type="float")
     * @Groups({"read"})
     */
    private $taxeEtat;

    /**
     * @ORM\Column(type="float")
     * @Groups({"read"})
     */
    private $partWari;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transactions")
     * @Groups({"read", "write"})
     */
    private $User;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read", "write"})
     */
    private $montantEnvoie;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read", "write"})
     */
    private $telEnvoyeur;

   
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $cniBeneficiaire;

  

    /**
     * @ORM\Column(type="float")
     */
    private $partAgenceEnvoie;

    /**
     * @ORM\Column(type="float")
     */
    private $partAgenceRetrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="transactionEnvoie")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read", "write"})
     */
    private $compteEnvoie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Compte", inversedBy="transactionRetrait")
     * @Groups({"read", "write"})
     */
    private $compteRetrait;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEnvoyeur(): ?string
    {
        return $this->nomEnvoyeur;
    }

    public function setNomEnvoyeur(string $nomEnvoyeur): self
    {
        $this->nomEnvoyeur = $nomEnvoyeur;

        return $this;
    }

    public function getPrenomEnvoyeur(): ?string
    {
        return $this->prenomEnvoyeur;
    }

    public function setPrenomEnvoyeur(string $prenomEnvoyeur): self
    {
        $this->prenomEnvoyeur = $prenomEnvoyeur;

        return $this;
    }

   

    public function getCodeEnvoie(): ?string
    {
        return $this->codeEnvoie;
    }

    public function setCodeEnvoie(string $codeEnvoie): self
    {
        $this->codeEnvoie = $codeEnvoie;

        return $this;
    }

    public function getFraisEnvoie(): ?int
    {
        return $this->fraisEnvoie;
    }

    public function setFraisEnvoie(int $fraisEnvoie): self
    {
        $this->fraisEnvoie = $fraisEnvoie;

        return $this;
    }

    public function getNonBeneficiaire(): ?string
    {
        return $this->nonBeneficiaire;
    }

    public function setNonBeneficiaire(string $nonBeneficiaire): self
    {
        $this->nonBeneficiaire = $nonBeneficiaire;

        return $this;
    }

    public function getPrenomBeneficiaire(): ?string
    {
        return $this->prenomBeneficiaire;
    }

    public function setPrenomBeneficiaire(string $prenomBeneficiaire): self
    {
        $this->prenomBeneficiaire = $prenomBeneficiaire;

        return $this;
    }

    public function getTelBeneficiaire(): ?int
    {
        return $this->telBeneficiaire;
    }

    public function setTelBeneficiaire(int $telBeneficiaire): self
    {
        $this->telBeneficiaire = $telBeneficiaire;

        return $this;
    }

  
    public function getTaxeEtat(): ?float
    {
        return $this->taxeEtat;
    }

    public function setTaxeEtat(float $taxeEtat): self
    {
        $this->taxeEtat = $taxeEtat;

        return $this;
    }

    public function getPartWari(): ?float
    {
        return $this->partWari;
    }

    public function setPartWari(float $partWari): self
    {
        $this->partWari = $partWari;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getMontantEnvoie(): ?int
    {
        return $this->montantEnvoie;
    }

    public function setMontantEnvoie(int $montantEnvoie): self
    {
        $this->montantEnvoie = $montantEnvoie;

        return $this;
    }

    public function getTelEnvoyeur(): ?int
    {
        return $this->telEnvoyeur;
    }

    public function setTelEnvoyeur(int $telEnvoyeur): self
    {
        $this->telEnvoyeur = $telEnvoyeur;

        return $this;
    }

    
    public function getCniBeneficiaire(): ?string
    {
        return $this->cniBeneficiaire;
    }

    public function setCniBeneficiaire(string $cniBeneficiaire): self
    {
        $this->cniBeneficiaire = $cniBeneficiaire;

        return $this;
    }

   

    public function getUserEnvoie(): ?User
    {
        return $this->userEnvoie;
    }

    public function setUserEnvoie(?User $userEnvoie): self
    {
        $this->userEnvoie = $userEnvoie;

        return $this;
    }



    public function getPartAgenceEnvoie(): ?float
    {
        return $this->partAgenceEnvoie;
    }

    public function setPartAgenceEnvoie(float $partAgenceEnvoie): self
    {
        $this->partAgenceEnvoie = $partAgenceEnvoie;

        return $this;
    }

    public function getPartAgenceRetrait(): ?float
    {
        return $this->partAgenceRetrait;
    }

    public function setPartAgenceRetrait(float $partAgenceRetrait): self
    {
        $this->partAgenceRetrait = $partAgenceRetrait;

        return $this;
    }

    public function getCompteEnvoie(): ?Compte
    {
        return $this->compteEnvoie;
    }

    public function setCompteEnvoie(?Compte $compteEnvoie): self
    {
        $this->compteEnvoie = $compteEnvoie;

        return $this;
    }

    public function getCompteRetrait(): ?Compte
    {
        return $this->compteRetrait;
    }

    public function setCompteRetrait(?Compte $compteRetrait): self
    {
        $this->compteRetrait = $compteRetrait;

        return $this;
    }

   

}
