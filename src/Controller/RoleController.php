<?php

namespace App\Controller;



use App\Repository\RoleRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RoleController{

public function __construct(TokenStorageInterface $tokenStorage,RoleRepository $roleRepo)
{
    $this->tokenStorage = $tokenStorage;
    $this->roleRepo = $roleRepo;
}

public function __invoke()
{
    $userconnect = $this->tokenStorage->getToken()->getUser();

    $listeRole = $this->roleRepo->findAll();

    $roleUserConect = $userconnect->getROles()[0];
    
    $tabRole = [];

    $i = 0;

    if ($roleUserConect === "ROLE_ADMIN_SYSTEME") {
        
        
        foreach ($listeRole as $role) {
       
            if($role->getLibelle() === "ADMIN" ||  $role->getLibelle() === "CAISSIER"){

                $tabRole[$i] = $role; 
                
                $i++;
            }
        
        }

    }
    if ($roleUserConect === "ROLE_ADMIN") {
        
          
        foreach ($listeRole as $role) {  
            
            if($role->getLibelle() === "CAISSIER"){

                $tabRole[$i] = $role; 
                
                $i++;
                
            }
           
        }
    }


    if ($roleUserConect === "ROLE_PARTENAIRE") {
        
          
        foreach ($listeRole as $role) {  

            
            
            if($role->getLibelle() === "ADMIN_PARTENAIRE" || $role->getLibelle() === "USER_PARTENAIRE"){

              
                $tabRole[$i] = $role; 
                
                $i++;
                
            }

           
           
        }
    }

    

    if ($roleUserConect === "ROLE_ADMIN_PARTENAIRE") {
        
          
        foreach ($listeRole as $role) {  
            
            if($role->getLibelle() === "USER_PARTENAIRE"){

                $tabRole[$i] = $role; 
                
                $i++;
                
            }
           
        }
    }

    return $tabRole;
}

}