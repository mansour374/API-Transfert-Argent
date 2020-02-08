<?php
namespace App\Controller;

use App\Entity\User;
use App\Operation\UserHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class UserControle{



    public function __construct(UserHandler $userHandler, TokenStorageInterface $tokenStorage, UserPasswordEncoderInterface $encoder)
    {
        
        $this->tokenStorage=$tokenStorage;
        $this->userHandler=$userHandler;
        $this->encoder = $encoder;
        
    }

    public function __invoke(User $data): User
    {
        $data->setPassword($this->encoder->encodePassword($data, $data->getPassword()));
        $data->setRoles(["ROLE_".$data->getRole()->getlibelle()]);
        $token=$this->tokenStorage->getToken();
       
        $userlog = $token->getUser();
       
        $recuproleUser=$data->getRole()->getLibelle();
        if( $userlog->getRoles()[0]=="ROLE_CAISSIER" )
        {

            
            if($recuproleUser=="ADMIN" || $recuproleUser=="ADMIN_SYSTEME" || $recuproleUser=="CAISSIER" ){

                
                throw new HttpException('403', "vous n'avez pas les privillèges pour accéder à ce service");
            }
        }

        if( $userlog->getRoles()[0]=="ROLE_ADMIN" ){

            if($recuproleUser=="ADMIN" || $recuproleUser=="ADMIN_SYSTEME"  )
            {
                     
                throw new HttpException('403', "vous n'avez pas les privillèges pour accéder à ce service");
            }

        
        }

        
        return $data;

    }
        
      
    }
