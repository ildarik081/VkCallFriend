<?php

namespace App\Service;

use App\Component\Exception\ServiceException;
use App\Component\Factory\EntityFactory;
use App\Repository\UserRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CallbackService
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * Сохранить данные пользователя
     *
     * @param Request $request
     * @return boolean
     * @throws ServiceException
     */
    public function saveUserData(Request $request): bool
    {
        $user = $this->userRepository->getUserByExternalId((int) $request->query->get('user_id'));

        if (null === $user) {
            $user = EntityFactory::createUser(
                sessionId: $request->query->get('state'),
                externalId: $request->query->get('user_id'),
                accessToken: $request->query->get('access_token')
            );
        } else {
            $user
                ->setSessionId($request->query->get('state'))
                ->setExternalId($request->query->get('user_id'))
                ->setAccessToken($request->query->get('access_token'))
            ;
        }

        try {
            $this->userRepository->save($user, true);
        } catch (Exception $exception) {
            throw new ServiceException(
                message: 'Ошибка при сохранении данных о пользователе: ' . $exception->getMessage(),
                code: ResponseAlias::HTTP_BAD_REQUEST,
                responseCode: 'ERROR_SAVE_USER',
                logLevel: LogLevel::CRITICAL
            );
        }

        return true;
    }
}
