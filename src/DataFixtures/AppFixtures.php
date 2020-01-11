<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {   
        //hydratation du role ADMIN_SYSTEME
        $roleAdminSysteme = new Role();
        $roleAdminSysteme->setLibelle("ADMIN_SYSTEME");
        $manager->persist($roleAdminSysteme);

        //hydratation du role ADMIN
        $roleAdmin = new Role();
        $roleAdmin->setLibelle("ADMIN");
        $manager->persist($roleAdmin);

        //hydratation du role CAISSIER
        $roleCaissier = new role();
        $roleCaissier->setLibelle("CAISSIER");
        $manager->persist($roleCaissier);

        //hydratation de la table role 
        $this->addReference('ADMIN_SYSTEME', $roleAdminSysteme);
        $this->addReference('ADMIN', $roleAdmin);
        $this->addReference('CAISSiER', $roleCaissier);

         
        $roleSupAdmin = $this->getReference('ADMIN_SYSTEME');
        
        $user = new User();
        $user->setUsername("mansour");
        $user->setRoles((array("ROLE_".$roleSupAdmin->getLibelle())));
        $user->setPassword($this->encoder->encodePassword($user, "Admin"));
        $user->setRole($roleSupAdmin);
        $user->setIsActive(true);

        $manager->persist($user);
        $manager->flush();
    }
}
