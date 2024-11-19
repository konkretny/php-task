<?php

namespace App\Core\User\Application\Command\CreateUser;

use App\Common\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;

#[AsMessageHandler]
class CreateUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly MailerInterface $mailer
    ) {}

    public function __invoke(CreateUserCommand $command): void
    {
        $this->userRepository->save(new User(
            $command->email,
            false
        ));

        //maling
        $this->sendRegistrationEmail($command->email);

        $this->userRepository->flush();
    }

    private function sendRegistrationEmail(string $email): void
    {
        $this->mailer->send(
            recipient: $email,
            subject: 'Rejestracja konta w systemie',
            message: 'Zarejestrowano konto w systemie. Aktywacja konta trwa do 24h'
        );
    }
}
