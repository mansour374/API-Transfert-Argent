<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"read"}},
 *          denormalizationContext={"groups"={"write"}},
 * 
*           collectionOperations={
*              "get"={"security"="is_granted('ROLE_ADMIN')",
*                    "security_message"="vous n'etes pas autorisé à acceder à cet service",
*                    },
*                  
 *          "post"={
 *                  "security"="is_granted('ROLE_ADMIN')",
*                   "security_message"="vous n'etes pas autorisé à acceder à cet service",
 *              
 *                 }
 *  },
 *          itemOperations={
*                "get"={"security"="is_granted('ROLE_ADMIN')",
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
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 */
class Partenaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=14)
     * @Groups({"read","write"})
     */
    private $ninea;

    /**
     * @ORM\Column(type="string", length=19)
     * @Groups({"read","write"})
     */
    private $rccm;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read","write"})
     */
    private $email;

      /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","write"})
     */
    private $raisonsociale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read","write"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"read","write"})
     */
    private $capital;

    /**
     * @ORM\Column(type="string", length=9)
     * @Assert\Regex(
     * pattern="/(77||78||70||76)[0-9]{7}$/",
     * message="Votre numero doit commencer par 77 OU 78 OU 76 OU 70"
     * )
     * @Groups({"read","write"})
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="partenaire",  cascade={"persist", "remove"})
     * @Groups({"read","write"})
     */
    private $userPartenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="partenaire", cascade={"persist", "remove"})
     * @Groups({"read"})
     * 
     */
    private $comptes;

  

    public function __construct()
    {
        $this->userPartenaire = new ArrayCollection();
        $this->comptes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getRccm(): ?string
    {
        return $this->rccm;
    }

    public function setRccm(string $rccm): self
    {
        $this->rccm = $rccm;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserPartenaire(): Collection
    {
        return $this->userPartenaire;
    }

    public function addUserPartenaire(User $userPartenaire): self
    {
        if (!$this->userPartenaire->contains($userPartenaire)) {
            $this->userPartenaire[] = $userPartenaire;
            $userPartenaire->setPartenaire($this);
        }

        return $this;
    }

    public function removeUserPartenaire(User $userPartenaire): self
    {
        if ($this->userPartenaire->contains($userPartenaire)) {
            $this->userPartenaire->removeElement($userPartenaire);
            // set the owning side to null (unless already changed)
            if ($userPartenaire->getPartenaire() === $this) {
                $userPartenaire->setPartenaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): self
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes[] = $compte;
            $compte->setPartenaire($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getPartenaire() === $this) {
                $compte->setPartenaire(null);
            }
        }

        return $this;
    }

    public function getMontantDepot(): ?int
    {
        return $this->montantDepot;
    }

    public function setMontantDepot(?int $montantDepot): self
    {
        $this->montantDepot = $montantDepot;

        return $this;
    }

    public function getRaisonsociale(): ?string
    {
        return $this->raisonsociale;
    }

    public function setRaisonsociale(?string $raisonsociale): self
    {
        $this->raisonsociale = $raisonsociale;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCapital(): ?int
    {
        return $this->capital;
    }

    public function setCapital(?int $capital): self
    {
        $this->capital = $capital;

        return $this;
    }
}
