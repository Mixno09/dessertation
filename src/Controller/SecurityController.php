<?php

namespace App\Controller;

use App\Form\RegistrationDto;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\CreateUserCommand;
use App\Service\UserService;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private UserService $userService;
    private GuardAuthenticatorHandler $authenticatorHandler;
    private LoginFormAuthenticator $loginFormAuthenticator;
    private UserRepository $repository;

    public function __construct(UserService $userService, GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, UserRepository $repository)
    {
        $this->userService = $userService;
        $this->authenticatorHandler = $authenticatorHandler;
        $this->loginFormAuthenticator = $loginFormAuthenticator;
        $this->repository = $repository;
    }

    /**
     * @Route("/registration", name="registration", methods={"GET","POST"})
     */
    public function registration(Request $request): Response
    {
        $registrationDto = new RegistrationDto();
        $form = $this->createForm(RegistrationType::class, $registrationDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $command = new CreateUserCommand($registrationDto->login, $registrationDto->password);
            $userId = $this->userService->create($command);
            $user = $this->repository->findUserById($userId);
            $response = $this->authenticatorHandler->authenticateUserAndHandleSuccess($user, $request, $this->loginFormAuthenticator, 'main');
            if ($response instanceof Response) {
                return $response;
            }
            return $this->redirectToRoute('main');
        }
        return $this->render('security/registration.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('main');
         }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
