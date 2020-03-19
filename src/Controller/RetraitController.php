<?php
namespace App\Controller;

use App\Entity\User;
use Twilio\Rest\Client;
use App\Entity\Transaction;
use App\Repository\TarifsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RetraitController{

    public function __construct(TokenStorageInterface $tokenStorage,TransactionRepository $transRepo, EntityManagerInterface $em )
    {
      $this->tokenStorage = $tokenStorage;
      $this->transrepo = $transRepo;
      $this->em = $em;
    }
 

    public function __invoke(Transaction $data): Transaction
    {
     
      $repo = $this->em->getRepository(Transaction::class);
      $request = new Request();
      $value = json_decode($request->getContent(), true);
      
 
      $retrait = $repo->RechercheCodeEnvoie($value['codeEnvoie']);

      if (!$retrait) {
        throw new Exception("Ce code est invalide");
        
      }elseif ($data->getStatus() !="Envoyé") {

        throw new Exception("La Somme a ete deja retire");
        
      }
    
      $userConnect = $this->tokenStorage->getToken()->getUser();

      if($userConnect->getRoles()[0] == "ROLE_ADMIN_SYSTEME" || $userConnect->getRoles()[0] == "ROLE_ADMIN" || $userConnect->getRoles()[0] == "ROLE_CAISIIER"){

        throw new HttpException("vous n'étes pas autorisé à éffecteur cette Opération");
        
      }else{
          $data->setUserRetrait($userConnect);
      }
 
      if($data->getUserRetrait()->getRoles()[0] == "ROLE_USER_PARTENAIRE"){

        $compteAffect = $data->getUserRetrait()->getUserAfectCompte()->toarray();

        foreach ($compteAffect as $ligneCompte){

          $data->setCompteRetrait($ligneCompte->getCompte());

        }

        $montantRetrait = $retrait->getMontantEnvoie();

        $montantSeuil = $retrait->getCompteRetrait()->getMontantPlafond();

        if($montantRetrait > $montantSeuil)
        {
           throw new HttpException(403,"Montant plafonnage dépasse, impossible d'effectuer le retrait");
           
        }

        $soldeActuel = $data->getCompteRetrait()->getSolde() + $montantRetrait;

        $data->getCompteRetrait()->setSolde($soldeActuel);

      }


      $data->setDateRetrait(new \Datetime())
          ->setCniBeneficiaire($value['cniBeneficiaire'])
          ->setStatus('retiré');

      
 // Your Account SID and Auth Token from twilio.com/console
$account_sid = 'ACb6425aafd06661af9abce0062809499b';
$auth_token = '6ac4a8f75da01c757e658544ab459d2e';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

// A Twilio number you own with SMS capabilities
//Nº de l'Emetteur du SystemTransfertMoney by Son Excellence WADE
$numSonExcellence= "+12029327632";

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    '+221'.$data->getTelEnvoyeur(),
    array(
        'from' => $numSonExcellence,
        'body' => 'Bienvenu(e) chez Mansour API-TRANSFERT-ARGENT  ! Les '.$data->getMontantEnvoie().' FCFA envoyés à '.$data->getPrenomBeneficiaire().' '.$data->getNonBeneficiaire().
                 ' viennent d\'être retirés  Code: '.$data->getCodeEnvoie().' Merci de votre fidélité.'
    )
);


       return $data;
    } 
  }
            
    


