<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Exception\ApiException;
use App\Exception\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final readonly class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private bool $debug,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = match (true) {
            $exception instanceof ValidationException => $this->handleValidation($exception),
            $exception instanceof ApiException => $this->handleApiException($exception),
            default => $this->handleUnexpected($exception),
        };

        $event->setResponse($response);
    }

    /**
     * Валидация — 422
     */
    private function handleValidation(ValidationException $exception): JsonResponse
    {
        $errors = [];

        foreach ($exception->getViolations() as $violation) {
            $errors[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return new JsonResponse(
            ['errors' => $errors],
            Response::HTTP_UNPROCESSABLE_ENTITY,
        );
    }

    /**
     * Бизнес — 400
     */
    private function handleApiException(ApiException $exception): JsonResponse
    {
        return new JsonResponse(
            ['errors' => [['message' => $exception->getMessage()]]],
            Response::HTTP_BAD_REQUEST,
        );
    }

    /**
     * Непредвиденные — 500
     * Описание только для dev
     */
    private function handleUnexpected(Throwable $exception): JsonResponse
    {
        $message = $this->debug
            ? $exception->getMessage()
            : 'Internal server error';

        return new JsonResponse(
            ['errors' => [['message' => $message]]],
            Response::HTTP_INTERNAL_SERVER_ERROR,
        );
    }
}
