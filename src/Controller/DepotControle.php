<?php
namespace App\Controller;

use App\Entity\Depot;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;



class DepotControle{



    public function __construct(TokenStorageInterface $tokenStorage )
    {
        $this->tokenStorage = $tokenStorage;
        
    }

    public function __invoke(Depot $data): Depot
    {
      if($data->getId()==null)
      {
               $RecupUserDepot = $this->tokenStorage->getToken()->getUser();

                  
      }
        
      $totalSolde =$data->getCompte()->getSolde() + $data->getMontantDepot();
      
      $data->getCompte()->setSolde($totalSolde);
      $data->getCompte()->setMontantPlafond($totalSolde);
       
        return $data;
    }
        
      
    }
