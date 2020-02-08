<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class AppPersister implements DataPersisterInterface{



 public function __construct(EntityManagerInterface $entityManagerInterface)
 {
     $this->entityManagerInterface=$entityManagerInterface;
   
}
 
    public function supports($data): bool
    {
        return $data instanceof User;

    }

    public function persist($data)
    {
        
        $data->eraseCredentials();

        $this->entityManagerInterface->persist($data);
        $this->entityManagerInterface->flush();
    }

    public function remove($data)
    {
        $this->entityManagerInterface->remove($data);
        $this->entityManagerInterface->flush();
    }
}