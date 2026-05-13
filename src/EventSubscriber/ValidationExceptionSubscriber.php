<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ValidationExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $validationException = $this->findValidationException(
            $event->getThrowable(),
        );

        if (!$validationException) {
            return;
        }

        $errors = [];

        foreach ($validationException->getViolations() as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        $event->setResponse(new JsonResponse([
            'message' => 'Validation failed',
            'errors' => $errors,
        ], 422));
    }

    private function findValidationException(
        \Throwable $exception,
    ): ?ValidationFailedException {
        do {
            if ($exception instanceof ValidationFailedException) {
                return $exception;
            }

            $exception = $exception->getPrevious();
        } while ($exception !== null);

        return null;
    }
}
