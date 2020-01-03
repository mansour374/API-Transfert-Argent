<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @route("/api")
 */

class SecurityController extends AbstractController
{
    
    /**
     * @Route("/register", name="register", methods={"POst"})
     */
    public function register(Request $request,UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $entityManager,SerializerInterface $serializer,ValidatorInterface $validator)
    {
        $values=json_decode($request->getContent());
        if(isset($values->username,$values->password)){
            $user = new User();
            $user->setUsername($values->username);
            $user->setPassword($passwordEncoder->encodePassword($user,$values->password));
            $user->setRoles($user->getRoles());
            $errors = $validator->validate($user);

            if(count($errors)){
                $errors = $serializer->serialize($errors,'json');

                return new Response($errors,500,[
                    'Content-Type'=>'application/json'
                ]);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message'=>'L\'utilisateur à été créé'
            ];

            return new JsonResponse($data,201);
        }

        $data = [

            'status' => 500,

            'message' =>'Vous devez renseigner lés clés username et password'
        ];

        return new JsonResponse($data,500);
    }

    /**
     * @Route("/login", name="login", methods={"Post"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        
        return $this->json([
            'username' => $user->getUsername(),
            'roles'=>$user->getRoles()
        ]);
    }
}
