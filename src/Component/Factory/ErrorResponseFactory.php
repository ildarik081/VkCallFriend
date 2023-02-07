<?php

namespace App\Component\Factory;

use App\Component\Exception\AbstractApiException;
use App\Dto\ControllerResponse\ExceptionResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ErrorResponseFactory
{
    private const UNKNOWN_ERROR = 'UNKNOWN_ERROR';
    private const UNKNOWN_ERROR_MESSAGE = 'Неизвестная ошибка';

    /**
     * @param Throwable $exception
     * @return JsonResponse
     */
    public function create(Throwable $exception): JsonResponse
    {
        $responseDto = new ExceptionResponse();

        if ($exception instanceof AbstractApiException) {
            $responseDto->message = $exception->getMessage();
            $responseDto->code = $exception->getResponseCode();
            $responseDto->trace = $exception->getTrace();

            $response = new JsonResponse(
                $responseDto,
                $exception->getCode(),
                $exception->getHeaders(),
                false
            );
        } else {
            $responseDto->message = empty($exception->getMessage())
                ? self::UNKNOWN_ERROR_MESSAGE
                : $exception->getMessage();

            $responseDto->code = self::UNKNOWN_ERROR;
            $responseDto->trace = $exception->getTrace();

            $response = new JsonResponse(
                $responseDto,
                Response::HTTP_BAD_REQUEST,
                [],
                false
            );
        }

        return $response;
    }
}
