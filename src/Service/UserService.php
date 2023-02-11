<?php

namespace App\Service;

use App\Component\Exception\RepositoryException;
use App\Component\Exception\ServiceException;
use App\Component\Factory\EntityFactory;
use App\Component\Factory\SimpleDtoFactory;
use App\Component\Requester\VkRequester;
use App\Dto\ControllerRequest\BaseRequest;
use App\Dto\Friend as DtoFriend;
use App\Entity\Friend;
use App\Entity\User;
use App\Repository\FriendRepository;
use App\Repository\UserRepository;
use Exception;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly FriendRepository $friendRepository,
        private readonly VkRequester $vkRequester
    ) {
    }

    /**
     * Актуализировать список друзей
     *
     * @param BaseRequest $request
     * @return JsonResponse
     * @throws ServiceException
     * @throws RepositoryException
     */
    public function updateFriends(BaseRequest $request): JsonResponse
    {
        $user = $this->userRepository->getUserBySessionId($request->session);

        try {
            $friendsVk = $this
                ->vkRequester
                ->getClient()
                ->friends()
                ->get(
                    $user->getAccessToken(),
                    [
                        'fields' => ['first_name', 'last_name']
                    ]
                )
            ;
        } catch (VKClientException | VKApiException $exception) {
            throw new ServiceException(
                message: 'Ошибка получения списка друзей из ВК: ' . $exception->getMessage(),
                code: ResponseAlias::HTTP_BAD_REQUEST,
                responseCode: 'ERROR_GET_FRIENDS',
                logLevel: LogLevel::CRITICAL
            );
        }

        $existFriends = $this->getExistFriends($user);
        $requestFriends = $this->convertRequestFriends($friendsVk['items']);

        $deleteFriends = array_diff_key($existFriends, $requestFriends);
        $newFriends = array_diff_key($requestFriends, $existFriends);

        $this->bigDeleteFriends($user, $deleteFriends);
        $this->createFriends($user, $newFriends);

        try {
            $this->userRepository->save($user, true);
        } catch (Exception $exception) {
            throw new ServiceException(
                message: 'Ошибка при сохранении друзей пользователя: ' . $exception->getMessage(),
                code: ResponseAlias::HTTP_BAD_REQUEST,
                responseCode: 'ERROR_SAVE_FRIENDS_USER',
                logLevel: LogLevel::CRITICAL
            );
        }

        return new JsonResponse(['success' => true]);
    }

    /**
     * Получить список друзей пользователя
     *
     * @param BaseRequest $request
     * @return User
     * @throws RepositoryException
     */
    public function getList(BaseRequest $request): User
    {
        return $this->userRepository->getUserBySessionId($request->session);
    }

    /**
     * Получить массив друзей пользователя
     *
     * Ключом массива является внешний идентификатор друга
     *
     * @param User $user
     * @return Friend[]
     */
    private function getExistFriends(User $user): array
    {
        $friends = $this->friendRepository->findBy(['user' => $user]);
        $result = [];

        foreach ($friends as $friend) {
            $result[$friend->getExternalId()] = $friend;
        }

        return $result;
    }

    /**
     * Преобразовать массив друзей из запроса
     *
     * Ключом массива является внешний идентификатор друга
     *
     * @param array $friends
     * @return DtoFriend[]
     */
    private function convertRequestFriends(array $friends): array
    {
        $result = [];

        foreach ($friends as $friend) {
            $result[$friend['id']] = SimpleDtoFactory::createFriend(
                id: $friend['id'],
                firstName: $friend['first_name'],
                lastName: $friend['last_name']
            );
        }

        return $result;
    }

    /**
     * Массовое удаление друзей
     *
     * Удаляются друзья из БД, которых уже нет в ВК
     *
     * @param User $user
     * @param Friend[] $friends
     * @return void
     */
    private function bigDeleteFriends(User $user, array $friends): void
    {
        foreach ($friends as $friend) {
            $user->removeFriend($friend);
            $this->friendRepository->remove($friend, false);
        }
    }

    /**
     * Массовое добавление друзей в БД
     *
     * Добавляются друзья из ВК, которых уже нет в БД
     *
     * @param User $user
     * @param DtoFriend[] $friends
     * @return void
     */
    private function createFriends(User $user, array $friends): void
    {
        foreach ($friends as $friend) {
            $user->addFriend(
                EntityFactory::createFriend($friend->id, $friend->firstName, $friend->lastName)
            );
        }
    }
}
