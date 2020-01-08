<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="Cet utilisateur existe déja")
 * @ApiResource(
 *      collectionOperations = {
 *      "get"= {"security"= "is_granted(['ROLE_SUP_ADMIN'])"},
 * 
 *      "CreatedAdmin"={"method"= "POST", 
 *      "path"="/users/admin/new",
 *      "security"= "is_granted(['ROLE_SUP_ADMIN'])" },
 * 
 *      "CreatedCaissier"={"method"= "POST", 
 *      "path"="/users/caissier/new", 
 *      "security"="is_granted(['ROLE_SUP_ADMIN','ROLE_ADMIN'])"}
 *     },
 * 
 *       itemOperations = {
 *         "get"={
 *            "security"= "is_granted(['ROLE_SUP_ADMIN','Role_ADMIN'])",
 *             "security_message"="Vous n'etes pas autorister à lister des utilisateurs, droit reservé au SUP_ADMIN et l'ADMIN" 
 *            },
 * 
 *          "blockedAdmin"={
 *             "method"="PUT",
 *              "path"="/users/admin/{id}",
 *              "security_message"="accés refusé, seule le SUP_ADMIN peut bloquer un adminitrateur"
 *           },
 *           
 *          "blockedCaissier"={
 *             "method"="PUT",
 *              "path"="/users/caissier/{id}",
 *              "security_message"="seule le SUP_ADMIN et l'Admin peuvent bloquer un adminitrateur"
 *           },
 *          
 *          "delete"={"security"= "is_granted(['ROLE_SUP_ADMIN','Role_ADMIN'])"}
 *              
 *      } 
 *  )
 */
class User implements AdvancedUserInterface
{
    /**
     * 
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

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
        $roles = [$this->role->getLibelle()];
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }


    public function isAccountNonExpired(){
        return true;
    }
    public function isAccountNonLocked(){
        return true;
    }
    public function isCredentialsNonExpired(){

        return true;
    }
    

    public function isEnabled(){
    
        return  $this->is_active;
    }

}
