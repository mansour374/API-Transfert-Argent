<?php
namespace App\Controller\GenereCode;

use App\Repository\CompteRepository;


class Numgencode{
 
    private $numcompte;
   

    public function __construct(CompteRepository $repo){
        
       $this->repo = $repo;
    }

    public function GenCode(){

       /*  $autoGenCode= $this->repo->findAll();
        $count = count($autoGenCode);
        $this->numcompte = sprintf("%05d", $count+1);
        return "ID-".$this->numcompte."SN"; */

        $lastCompte = $this->repo->findOneBy([], ['id' => 'desc']);
        if($lastCompte != null){
            $lastId = $lastCompte->getId();
            $this->numcompte = sprintf("%05d", $lastId+1);    
        }
        else{
            $this->numcompte = sprintf("%07d", 1);  
        }

        return "ID".$this->numcompte."CM"; 

    }
}