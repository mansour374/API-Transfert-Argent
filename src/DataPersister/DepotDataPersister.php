<?php

namespace App\DataPersister;


use App\Entity\Depot;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;


class DepotDataPersister implements DataPersisterInterface{



 public function __construct(EntityManagerInterface $entityManagerInterface)
 {
     $this->entityManagerInterface=$entityManagerInterface;
     
}
 
    public function supports($data): bool
    {
        return $data instanceof Depot;

    }

    public function persist($data)
    {

        $this->entityManagerInterface->persist($data);
        $this->entityManagerInterface->flush();
    }

    public function remove($data)
    {
        $this->entityManagerInterface->remove($data);
        $this->entityManagerInterface->flush();
    }
}