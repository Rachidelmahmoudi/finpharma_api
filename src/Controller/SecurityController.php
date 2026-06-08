<?php

namespace App\Controller;

use App\Form\DeleteAccountType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/profile', name: 'profile')]
    public function profile(): void
    {

    }

    #[Route(path: '/account/delete', name: 'delete_account')]
    public function deleteAccount(Request $request, UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $deleteAccountForm = $this->createForm(DeleteAccountType::class);
        $deleteAccountForm->handleRequest($request);
        if ($deleteAccountForm->isSubmitted() && $deleteAccountForm->isValid()) {
            $user = $userRepository->findOneBy(['email' => $deleteAccountForm->get('email')->getData(), 'status' => 1]);
            if ($user) {
                $user->setStatus(-1);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Your account has been deleted.');
                return $this->redirectToRoute('index');
            } else {
                $this->addFlash('error', 'No account found with the provided email.');
                return $this->redirectToRoute('delete_account');
            }  
        }
        return $this->render('security/delete_account.html.twig', [
            'deleteAccountForm' => $deleteAccountForm,
        ]);
    }
}
