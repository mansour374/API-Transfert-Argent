<?php

namespace App\Controller;

use App\Entity\AffectationCompte;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;



class AffectationController{

    public function __construct(TokenStorageInterface $tokenStorage){

    $this->tokenStorage = $tokenStorage;
 }

    public function __invoke(AffectationCompte $data):AffectationCompte
    { 
     

        $userConect = $this->tokenStorage->getToken()->getUser();
       
        if ($userConect->getRoles()[0] == "ROLE_ADMIN_PARTENAIRE" || 
            $userConect->getRoles()[0] == "ROLE_PARTENAIRE")
        {
    
            $data->setUserAfect($userConect);

//Récupération date début date fin

            $dateDebut = $data->getDateDebut()->format('Y-m-d');
            $dateFin = $data->getDateFin()->format('Y-m-d');
            $dateCourent = date('Y-m-d');
            
                if ($dateDebut <= $dateCourent && $dateCourent <= $dateFin) 
                {

                    if ($data->getUserAfecteCompte()->getRoles()[0] == "ROLE_ADMIN_SYSTEME" ||
                        $data->getUserAfecteCompte()->getRoles()[0] == "ROLE_CAISSIER" ||
                        $data->getUserAfecteCompte()->getRoles()[0] == "ROLE_ADMIN" )
                    {
                        throw new HttpException(403, "cet utilisateur ne peut pas étre affecter à un compte");

                        }elseif ($data->getUserAfecteCompte()->getPartenaire()->getNinea() != $data->getUserAfect()->getPartenaire()->getNinea()) {
                        
                            throw new HttpException(403, "cet utilisateur n'appartient pas à cette Agence ");
  
                             }
                                $listcompte = $data->getUserAfect()->getPartenaire()->getComptes();
                                $tabCompte = $listcompte->toArray();

                                for ($i=0; $i < count($tabCompte) ; $i++) { 
                                  dd($tabCompte);
                                }
    
                }else {

                    throw new HttpException(403, "la durée d'utilisation de ce compte est terminé");
                }

            }else{
                
             throw new HttpException(403, "vous n'étes pas autorisé à ce service veiullez vous rapprocher de vos supérieur");
             
        }

        return $data;
        
    }

}