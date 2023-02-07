<?php

namespace App\Component\Factory;

use App\Component\Exception\ControllerException;
use App\Component\Interface\Controller\ControllerResponseInterface;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DtoResponseFactory
{
    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(public SerializerInterface $serializer)
    {
    }

    /**
     * @param mixed $response
     * @throws ControllerException
     * @return JsonResponse
     */
    public function create(mixed $response): JsonResponse
    {
        if (!($response instanceof ControllerResponseInterface)) {
            throw new ControllerException(
                message: 'Ошибка сервера. Контроллер вернул непредвиденный тип данных.',
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                responseCode: 'BAD_RESPONSE_TYPE_FROM_CONTROLLER',
                logLevel: LogLevel::CRITICAL
            );
        }

        $dtoJson = $this->serializer->serialize($response, 'json');

        return new JsonResponse($dtoJson, Response::HTTP_OK, [], true);
    }
}
