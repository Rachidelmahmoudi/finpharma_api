<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Message\EmailNotification;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final class RegisterController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
        private Environment $twig,
        private readonly MessageBusInterface $bus,
        private TranslatorInterface $translator,
        private readonly Security $security
    ) {
        
    }
    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if (!!$user) {
            return new JsonResponse(['success' => false, 'message' => $this->translator->trans('registration.email_used')], Response::HTTP_BAD_REQUEST);
        }
        $user = new User();
        $user->setFirstName('FinPharmaa USER')
        ->setLastName('FinPharma user')
        ->setEmail($data['email'])
        ->setRoles([User::ROLE_USER]);
        $this->em->persist($user);
        $password = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($password);
        $this->em->persist($user);
        /**
         * Send confirmationn email
         */
        $this->bus->dispatch(new EmailNotification(
            'contact@finpharma.ma', 
            $user->getEmail(), 
            $this->translator->trans('registration.subject' , domain: 'emails'), 
            $this->twig->render('emails/welcome.html.twig')
        ));
        $this->em->flush();
        return new JsonResponse(['success' => true]);
    }
}
