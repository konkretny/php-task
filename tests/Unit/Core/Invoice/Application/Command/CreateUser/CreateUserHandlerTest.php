<?php

namespace App\Tests\Unit\Core\Invoice\Application\Command\CreateInvoice;

use App\Common\Mailer\MailerInterface;
use App\Core\User\Application\Command\CreateUser\CreateUserCommand;
use App\Core\User\Application\Command\CreateUser\CreateUserHandler;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateUserHandlerTest extends TestCase
{
    private UserRepositoryInterface|MockObject $userRepository;
    private MailerInterface|MockObject $mailer;

    private CreateUserHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = new CreateUserHandler(
            $this->userRepository = $this->createMock(
                UserRepositoryInterface::class
            ),
            $this->mailer = $this->createMock(
                MailerInterface::class
            )
        );
    }

    public function test_handle_success(): void
    {
        $user = new User(
            "test@example.com"
        );

        $this->userRepository->expects(self::once())
            ->method('save')
            ->with($user);

        $this->userRepository->expects(self::once())
            ->method('flush');

        $this->handler->__invoke((new CreateUserCommand('test@example.com', false)));
    }
}
