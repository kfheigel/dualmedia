<?php

declare(strict_types=1);

namespace App\Infrastructure\MessageBus\Middleware;

use App\Infrastructure\MessageBus\Stamp\TimeStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final readonly class LoggerMiddleware implements MiddlewareInterface
{
    public function __construct(private LoggerInterface $messengerLogger)
    {
    }
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(TimeStamp::class)) {
            $envelope = $envelope->with(new TimeStamp());
        }

        /** @var TimeStamp $stamp */
        $stamp = $envelope->last(TimeStamp::class);
        $message = $envelope->getMessage();

        $context = [
            'class' => get_class($message),
            'currentDateTime' => (string) $stamp,
            'message' => $message
        ];

        $this->messengerLogger->info('Message: {message}', $context);

        return $stack->next()->handle($envelope, $stack);
    }
}
