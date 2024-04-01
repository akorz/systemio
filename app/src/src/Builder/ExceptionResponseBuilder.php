<?php

namespace App\Builder;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionResponseBuilder
{
    public function __construct(
        private readonly ValidationFailedErrorsBuilder $validationFailedErrorsBuilder
    ) { }

    public function getExceptionResponse(mixed $exception): ?JsonResponse
    {
        $isHttpEvent = $exception instanceof HttpExceptionInterface;
        $previousException = $exception->getPrevious();

        if ($isHttpEvent && $previousException) {
            return match (get_class($previousException)) {
                ValidationFailedException::class => $this->getResponseForValidationFailedException($previousException),
                default => null
            };
        }

        return null;
    }

    private function getResponseForValidationFailedException(ValidationFailedException $exception): JsonResponse
    {
        $errors = $this->validationFailedErrorsBuilder->build($exception->getViolations());
        return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }
}