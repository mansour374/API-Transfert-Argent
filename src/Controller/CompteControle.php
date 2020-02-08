<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;

use App\Entity\Compte;

use App\Controller\GenereCode\Numgencode;
use Doctrine\DBAL\Tools\Dumper;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CompteControle{

    public function __construct(TokenStorageInterface $tokenStorage, Numgencode $numgencode, UserPasswordEncoderInterface $encoder )
    {
        $this->tokenStorage = $tokenStorage;
        $this->numgencode = $numgencode;
        $this->encoder = $encoder;
       
    }

    public function __invoke(Compte $data): Compte
    {
      if($data->getId()==null)
      {

       $userPartenaire= $data->getPartenaire()->getUserPartenaire();
 
       foreach ($userPartenaire as $users) {
         $users->setPassword($this->encoder->encodePassword($users, $users->getPassword()))
             ->setRoles(["ROLE_".$users->getRole()->getLibelle()])
             ->getPartenaire()->addUserPartenaire($users);

            
            
            
            
       }
               
               $RecupUsercreate = $this->tokenStorage->getToken()->getUser();

               $data->setNumCompte($this->numgencode->GenCode())
                    ->setUser($RecupUsercreate);
      }
      if($data->getSolde()>= 500000)  {
        $depot = new Depot();
        $depot->setMontantDepot($data->getSolde())
              ->setUser($RecupUsercreate);

      $data->addDepot($depot);
      }else{
        throw new Exception("le montant d'ouverture de compte doit etre au minimum 500000 FCFA");
      }

     

        return $data;
    }
        
      
    }


