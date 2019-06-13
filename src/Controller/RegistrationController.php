<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="app_registration")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler    $authenticator
     * @param LoginFormAuthenticator       $loginFormAuthenticator
     *
     * @return Response
     */
    public function registration(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $authenticator,
        LoginFormAuthenticator $loginFormAuthenticator
    ) : Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get password from form
            $passwordData = $form->get('password')->getData();
            $this->encodePasswordAndFlush($user, $passwordEncoder, $passwordData);

            // create a new token for the user and redirect it.
            return $authenticator->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
            );
        }

        return $this->render('registration/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param User                         $user
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param string                       $data
     */
    private function encodePasswordAndFlush(
        User $user,
        UserPasswordEncoderInterface $passwordEncoder,
        string $data
    ) : void
    {
        // encode the plain password
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $data
            )
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }
}