<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\Depot;

use App\Entity\Compte;

use Doctrine\DBAL\Tools\Dumper;
use App\Repository\PartenaireRepository;
use App\Controller\GenereCode\Numgencode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CompteControle{

    public function __construct(TokenStorageInterface $tokenStorage, Numgencode $numgencode, UserPasswordEncoderInterface $encoder,PartenaireRepository $partenairerepo)
    {
        $this->tokenStorage = $tokenStorage;
        $this->numgencode = $numgencode;
        $this->encoder = $encoder;
        $this->partenairerepo = $partenairerepo; 
    }

    public function __invoke(Compte $data): Compte

    {
      if($data->getId()==null)
      {
        //Création compte partenaire
        $request = new Request();
        $value = json_decode($request->getContent(), true);
      
        $ninea = $value['partenaire']['ninea'];

        $partenaire = $this->partenairerepo->searchNinea($ninea); 
     
      if($partenaire ==null){
        $userPartenaire= $data->getPartenaire()->getUserPartenaire();
 
        foreach ($userPartenaire as $users) {
          $users->setPassword($this->encoder->encodePassword($users, $users->getPassword()))
              ->setRoles(["ROLE_".$users->getRole()->getLibelle()])
              ->getPartenaire()->addUserPartenaire($users);
        }   

      }else{
        $data->setPartenaire($partenaire);
      }
      
    }
               
      $RecupUsercreate = $this->tokenStorage->getToken()->getUser();

       if($data->getId() == null){
               $data->setNumCompte($this->numgencode->GenCode());
                  
      }
      $data->setUser($RecupUsercreate)
           ->setMontantPlafond($data->getSolde());

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
    


