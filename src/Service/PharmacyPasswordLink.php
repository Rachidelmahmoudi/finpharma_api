<?php
namespace App\Service;

use ApiPlatform\Metadata\UrlGeneratorInterface;
use App\Entity\User;
use App\Message\EmailNotification;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelper;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Twig\Environment;

class PharmacyPasswordLink {
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private TranslatorInterface $translator,
        private Environment $twig,
        private readonly MessageBusInterface $bus,
        private UrlGeneratorInterface $url_generator
    ) { }

    /**
     * Send email with password creation link to pharmacy owner
     * 
     * @return void
     */
    public function sendLink(User $user, string $lang): void 
    {
        $resetToken = $this->resetPasswordHelper->generateResetToken($user);

        $resetLink = $this->url_generator->generate(
            'app_reset_password', // The route name for your reset page
            ['token' => $resetToken->getToken()],
            UrlGeneratorInterface::ABS_URL
        );

        $this->bus->dispatch(new EmailNotification(
            'contact@finpharma.ma', 
            $user->getEmail(), 
            $this->translator->trans('new_pharmacy.subject', locale: $lang, domain: 'emails'), 
            $this->twig->render('emails/created_pharmacy.html.twig', ['lang' => $lang, 'link' => $resetLink])
        ));
    }
}