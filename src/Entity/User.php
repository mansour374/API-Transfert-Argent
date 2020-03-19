<?php

namespace App\Entity;




use App\Controller\UserControle;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="Cet utilisateur existe déja")
 * @ApiResource(
 *          attributes={"force_eager"=false},
 *          normalizationContext={"groups"={"read"}},
 *          denormalizationContext={"groups"={"write"}},
 * 
*           collectionOperations={
*              "get"={"security"="is_granted('ROLE_ADMIN')",
*                    "security_message"="vous n'etes pas autorisé à acceder à cet service",
*                    },
*                  
 *          "post"={
 *                 "controller"=UserControle::class
 *                 }
 *  },
 *          itemOperations={
*                "get"={"security"="is_granted('ROLE_ADMIN')",
    *                  "security_message"="vous n'etes pas autorisé à acceder à cet service",
    *                  "normalization_context"={"groups"={"read"}}
 *                     },
 * 
 *               "put"={
 *                      "controller"=UserControle::class
 *                     }
 *               }
 *       
 *  )
 */
class User implements UserInterface
{
    /**
     * 
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *  @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read", "write"})
     */
    private $username;

      /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"read", "write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"read", "write"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read", "write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"read", "write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"write"})
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read", "write"})
     * 
     */
    private $role;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read", "write"})
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="userPartenaire", cascade={"persist", "remove"}, fetch="EAGER")
     * @ApiProperty(attributes={"fetchEager": true})
     * @Groups({"read", "write"})
     */
    private $partenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Compte", mappedBy="user")
     */
    private $comptes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="user")
     */
    private $depots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AffectationCompte", mappedBy="userAfect")
     */
    private $userAfect;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AffectationCompte", mappedBy="userAfecteCompte")
     */
    private $userAfectCompte;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="userEnvoie")
     */
    private $transactions;

    
    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->depots = new ArrayCollection();
        $this->userAfect = new ArrayCollection();
        $this->userAfectCompte = new ArrayCollection();
       $this->isActive = true;
       $this->transactions = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
      
        return $roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }


    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

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
            $compte->setUser($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): self
    {
        if ($this->comptes->contains($compte)) {
            $this->comptes->removeElement($compte);
            // set the owning side to null (unless already changed)
            if ($compte->getUser() === $this) {
                $compte->setUser(null);
            }
        }

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
            $depot->setUser($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getUser() === $this) {
                $depot->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AffectationCompte[]
     */
    public function getUserAfect(): Collection
    {
        return $this->userAfect;
    }

    public function addUserAfect(AffectationCompte $userAfect): self
    {
        if (!$this->userAfect->contains($userAfect)) {
            $this->userAfect[] = $userAfect;
            $userAfect->setUserAfect($this);
        }

        return $this;
    }

    public function removeUserAfect(AffectationCompte $userAfect): self
    {
        if ($this->userAfect->contains($userAfect)) {
            $this->userAfect->removeElement($userAfect);
            // set the owning side to null (unless already changed)
            if ($userAfect->getUserAfect() === $this) {
                $userAfect->setUserAfect(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AffectationCompte[]
     */
    public function getUserAfectCompte(): Collection
    {
        return $this->userAfectCompte;
    }

    public function addUserAfectCompte(AffectationCompte $userAfectCompte): self
    {
        if (!$this->userAfectCompte->contains($userAfectCompte)) {
            $this->userAfectCompte[] = $userAfectCompte;
            $userAfectCompte->setUserAfecteCompte($this);
        }

        return $this;
    }

    public function removeUserAfectCompte(AffectationCompte $userAfectCompte): self
    {
        if ($this->userAfectCompte->contains($userAfectCompte)) {
            $this->userAfectCompte->removeElement($userAfectCompte);
            // set the owning side to null (unless already changed)
            if ($userAfectCompte->getUserAfecteCompte() === $this) {
                $userAfectCompte->setUserAfecteCompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setUserEnvoie($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getUserEnvoie() === $this) {
                $transaction->setUserEnvoie(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

  


}
