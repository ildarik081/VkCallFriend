<?php

namespace App\Component\Resolver;

use App\Component\Exception\ResolverException;
use App\Component\Interface\AbstractDtoControllerRequest;
use Generator;
use JMS\Serializer\SerializerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RequestDtoResolver implements ArgumentValueResolverInterface
{
    /** Ключ сессии */
    private const SESSION = 'session';

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_subclass_of($argument->getType(), AbstractDtoControllerRequest::class);
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        if ($request->getSession()->has(self::SESSION)) {
            $session = $request->getSession()->get(self::SESSION);
        } else {
            $session = uniqid(more_entropy: true);
            $request->getSession()->set(self::SESSION, $session);
        }

        if (Request::METHOD_GET === $request->getMethod()) {
            $data = (string) json_encode($request->query->all(), JSON_THROW_ON_ERROR);
        } else {
            $data = $this->getBodyString($request);
        }

        try {
            /** @var AbstractDtoControllerRequest $result */
            $result = $this->serializer->deserialize($data, $argument->getType(), 'json');
        } catch (RuntimeException $exception) {
            throw new ResolverException(
                message: $exception->getMessage(),
                code: ResponseAlias::HTTP_BAD_REQUEST,
                responseCode: 'ERROR_DESERIALIZE_DTO_RESOLVER',
                logLevel: LogLevel::CRITICAL
            );
        }

        $result->session = $session;

        yield $result;
    }

    /**
     * Получить тело запроса
     *
     * @param Request $request
     * @return string
     */
    private function getBodyString(Request $request): string
    {
        if (empty($request->getContent())) {
            return '{}';
        }

        return (string) $request->getContent();
    }
}
