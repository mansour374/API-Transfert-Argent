<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Service\HTML2PDF;
use App\Repository\ContratRepository;
use App\Repository\PartenaireRepository;
use App\Repository\TermeContratRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContratController extends AbstractController
{
    /**
     * @Route("/contrat", name="contrat")
     */
    public function genereContrat(Contrat $contrats = null, ContratRepository $contratRepo,PartenaireRepository $partenaireRepo,TermeContratRepository $termeRepo )
    {
        $partenaire = $partenaireRepo->findOneBy([], ['id' => 'desc']);
        $close = $termeRepo->findOneBy([],['id' =>'desc']);
         
        if(!$contrats)
        {
            $contrats = new Contrat();
 
            $entityManager = $this->getDoctrine()->getManager();
            
            $contrats->setcontrats($partenaire)
                    ->setCloses($close);
                   
                
                       


          $tabContrat[]=$contrats;

           
        }

        $template = $this->renderView('pdf.html.twig',
       [
           'lignecontrat' => $tabContrat
       ]);

       $html2pdf = new HTML2PDF();
       $html2pdf->create('P', 'A4', 'fr', true, 'UTF-8', array(10,15,10,15));
       
      return  $html2pdf->generatePdf($template, "contrat");

        
        
    }
}
