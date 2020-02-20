<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Config\Definition\Exception\Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class JWTCreatedListener
{

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var $user \AppBundle\Entity\User */

        $user = $event->getUser();

//blocage compte Utilisateurs:

        if($user->getIsActive() == false){

            throw new CustomUserMessageAuthenticationException("votre compte à été bloqué veiullez vous rapprocher de vos suppérieurs ");
            
        }
//blocage compte Partenaire

        if($user->getPartenaire() !=null){

            if ($user->getPartenaire()->getUserPartenaire()[0]->getIsActive() == false){

              throw new CustomUserMessageAuthenticationException("le compte du partenaire " .$user->getPartenaire()->getRaisonSociale()." à été bloqué");
                
            }
        }
        // merge with existing event data
        $payload = array_merge(
            $event->getData(),
            [
                'password' => $user->getPassword()
            ]
        );

        $event->setData($payload);
    }
}