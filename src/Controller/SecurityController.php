<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Security\LoginFormAuthenticator;
use App\Security\UserProvider;
use App\UseCase\Command\CreateUserCommand;
use App\UseCase\Command\CreateUserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private CreateUserHandler $handler;
    private GuardAuthenticatorHandler $authenticatorHandler;
    private LoginFormAuthenticator $loginFormAuthenticator;
    private UserProvider $userProvider;

    public function __construct(CreateUserHandler $handler, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, UserProvider $userProvider)
    {
        $this->handler = $handler;
        $this->authenticatorHandler = $authenticatorHandler;
        $this->loginFormAuthenticator = $loginFormAuthenticator;
        $this->userProvider = $userProvider;
    }

    /**
     * @Route("/registration", name="registration", methods={"GET","POST"})
     */
    public function registration(Request $request): Response
    {
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new CreateUserCommand($data['username'], $data['password']);
            $this->handler->handle($command);
            $user = $this->userProvider->loadUserByUsername($data['username']);
            $this->authenticatorHandler->authenticateUserAndHandleSuccess($user, $request, $this->loginFormAuthenticator, 'main');
            return $this->redirectToRoute('main');
        }
        return $this->render('security/registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
