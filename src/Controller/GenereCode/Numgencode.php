<?php
namespace App\Controller\GenereCode;

use App\Repository\CompteRepository;
use App\Repository\TransactionRepository;


class Numgencode{
 
    private $numcompte;
   

    public function __construct(CompteRepository $repo,TransactionRepository $transaction){
        
       $this->repo = $repo;
       $this->transaction = $transaction;
    }

    public function GenCode(){


        $lastCompte = $this->repo->findOneBy([], ['id' => 'desc']);
        if($lastCompte != null){
            $lastId = $lastCompte->getId();
            $this->numcompte = sprintf("%06d", $lastId+1);    
        }
        else{
            $this->numcompte = sprintf("%06d", 1);  
        }

        return "ID".$this->numcompte."CM"; 

         }

        public function codeTransaction(){

        
        $lastTransaction = $this->repo->findOneBy([], ['id' => 'desc']);
        if($lastTransaction != null){
            $lastId = $lastTransaction->getId();
            $this->codeEnvoie = sprintf("%09d", $lastId+1);    
        }
        else{
            $this->codeEnvoie = sprintf("%09d", 1);  
        }

        return $this->codeEnvoie; 

         }

     
}