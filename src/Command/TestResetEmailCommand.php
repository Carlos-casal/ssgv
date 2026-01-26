<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'app:test-reset-email',
    description: 'Sends a test reset password email to admin@example.com',
)]
class TestResetEmailCommand extends Command
{
    private MailerInterface $mailer;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $urlGenerator)
    {
        parent::__construct();
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = 'admin@example.com';
        $resetUrl = $this->urlGenerator->generate('app_reset_password', ['token' => 'test-token'], UrlGeneratorInterface::ABSOLUTE_URL);

        $emailMessage = (new TemplatedEmail())
            ->from(new Address('no-reply@proteccioncivilvigo.org', 'Protección Civil Vigo'))
            ->to($email)
            ->subject('[Protección Civil Vigo] Restablecer contraseña')
            ->htmlTemplate('emails/reset_password.html.twig')
            ->context([
                'user_name' => 'Admin Test',
                'reset_url' => $resetUrl,
            ]);

        $this->mailer->send($emailMessage);

        $output->writeln('Test reset password email sent to ' . $email);
        $output->writeln('Check the Symfony Profiler to view it.');

        return Command::SUCCESS;
    }
}
