<?php
namespace App\Controller;

use App\Entity\User;
use Twilio\Rest\Client;
use App\Entity\Transaction;

use App\Repository\TarifsRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

//require_once '/path/to/vendor/autoload.php';




class TransactionControle{

    public function __construct(TokenStorageInterface $tokenStorage,TarifsRepository $tarifsRepo )
    {
      $this->tokenStorage = $tokenStorage;
      $this->tarifsRepo = $tarifsRepo;
    }

    
    public function __invoke(Transaction $data): Transaction
    {
      $codeenvoie = rand(9, 1000000000);

  
      if($data->getId()==null){
       $data ->setCodeEnvoie($codeenvoie);
    } 
      
     $userconnect = $this->tokenStorage->getToken()->getUser();
     //dd($userconnect);
     $data->setUserEnvoie($userconnect);
    

      $tarif = $this->tarifsRepo->findAll();
     
      
      for ($i=0; $i < count($tarif) ; $i++) { 
        $borneinf = $tarif[$i]->getBorneInf();
        $bornesup = $tarif[$i]->getBorneSup();
        
        $montantenvoie =$data->getMontantEnvoie();
        if ($borneinf<= $montantenvoie && $montantenvoie <=$bornesup) {
       $frais =$tarif[$i]->getValeur();
       
        break;

      }elseif (2000001 <= $montantenvoie && $montantenvoie <=3000000) {
       
          $frais = $montantenvoie * 0.02;

        }
        
      }
     
      $partEtat= $frais * 0.4;
      $partWari = $frais * 0.3;
      $partAgenceEnvoie = $frais * 0.1;
      $partAgenceRetrait = $frais * 0.2;


      $compte= $data->getUserEnvoie()->getPartenaire()->getComptes();
      

   //   foreach ($compte as $value) {
   //     if ($montantenvoie < $value->getSolde()) {
    //      $solde= $value->getSolde() + $partAgence-$montantenvoie-$frais;
   //       $value->setSolde($solde);

//        }
       
  //    }

      //verifier si l'utilisateur connecté est userPartenaire
      if($data->getUserEnvoie()->getRoles()[0] == "ROLE_USER_PARTENAIRE"){
        $compteAffecte = $data->getUserEnvoie()->getUserAfectCompte()->toArray();
        foreach( $compteAffecte as $TabCompte){
          $data->setCompteEnvoie($TabCompte->getCompte());
        }
      }elseif($data->getUserEnvoie()->getRoles()[0] == "ROLE_ADMIN_PARTENAIRE" || $data->getUserEnvoie()->getRoles()[0] == "ROLE__PARTENAIRE"){
     
        $comptePart = $data->getUserEnvoie()->getPartenaire()->getComptes();

        for ($i=0; $i < count($comptePart); $i++) {
          
          $compte = $comptePart[$i];
          $data->setUserEnvoie($compte);
          # code...
        }

        }
      
        if ($montantenvoie > $data->getCompteEnvoie()->getSolde()) {
           throw new Exception("Le Solde de votre Compte actuel ne vous permet pas de faire une transaction");
           
        }else {
          $soldeActuel = $data->getCompteEnvoie()->getSolde() - $montantenvoie;
          $data->getCompteEnvoie()->setSolde($soldeActuel);
        }

      $data->setFraisEnvoie($frais)
           ->setTaxeEtat($partEtat)
           ->setPartWari($partWari)
           ->setPartAgenceEnvoie($partAgenceEnvoie)
           ->setPartAgenceRetrait($partAgenceRetrait)
           ->setStatus('Envoyé');




            ####################################################################################################
 ########################## SEND SMS TRANSACTION BY SON EXCELLENCE #########################
 ####################################################################################################

 // Your Account SID and Auth Token from twilio.com/console
$account_sid = 'ACb6425aafd06661af9abce0062809499b';
$auth_token = '6ac4a8f75da01c757e658544ab459d2e';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

// A Twilio number you own with SMS capabilities
//Nº de l'Emetteur du SystemTransfertMoney by Son Excellence WADE
$numMansour= "+12029327632";

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    '+221'.$data->getTelBeneficiaire(),
    array(
        'from' => $numMansour,
        'body' => 'Bienvenu(e) chez Wari ! '.$data->getPrenomEnvoyeur().' '.$data->getNomEnvoyeur().
                 ' vous a envoyé '.$data->getMontantEnvoie().' FCFA  Code: '.$data->getCodeEnvoie().' Disponible dans tout le réseau Wari.'
    )
);

        return $data;
    }
            
    }


