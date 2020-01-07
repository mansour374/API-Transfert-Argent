<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppPersister implements DataPersisterInterface{

private $entityManagerInterface;
private $encoder;

 public function __construct(EntityManagerInterface $entityManagerInterface,UserPasswordEncoderInterface $encoder)
 {
     $this->entityManagerInterface=$entityManagerInterface;
     $this->encoder=$encoder;
}
 
    public function supports($data): bool
    {
        return $data instanceof User;

    }

    public function persist($data)
    {
        $data->setPassword($this->encoder->encodePassword($data, $data->getPassword()));
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