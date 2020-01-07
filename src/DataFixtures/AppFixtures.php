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
        $role = new Role();
        $role->setLibelle("ROLE_SUP_ADMIN");
        $manager->persist($role);
        $manager->flush();
        $this->addReference('roleAdmin', $role);
         
        $roleSupAdmin = $this->getReference('roleAdmin');
        
        $user = new User();
        $user->setUsername("mansour");
        $user->setRoles((array("ROLE_USER")));
        $user->setPassword($this->encoder->encodePassword($user, "admin"));
        $user->setRole($roleSupAdmin);
        

        $manager->persist($user);
        $manager->flush();
    }
}
