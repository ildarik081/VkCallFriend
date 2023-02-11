<?php

namespace App\Component\Handler;

use App\Component\Factory\EntityFactory;
use App\Component\Message\CallMessage;
use App\Component\Requester\VkRequester;
use App\Component\Utils\Enum\CallStatusEnum;
use App\Component\Utils\Utils;
use App\Entity\Friend;
use App\Entity\User;
use App\Repository\CallStatusRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CallHandler implements MessageHandlerInterface
{
    private array $statusTypes = [];

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CallStatusRepository $callStatusRepository,
        private readonly VkRequester $vkRequester
    ) {
    }

    public function __invoke(CallMessage $message): void
    {
        /** @var User $user */
        $user = $this->userRepository->find($message->getUserId());
        $idsFriendOnline = $this->vkRequester->getClient()->friends()->getOnline($user->getAccessToken());

        $successStatusIds = [];

        foreach ($idsFriendOnline as $externalId) {
            $successStatusIds[$externalId] = $externalId;
        }

        $allIdsFromDb = Utils::getFriendsId($user->getFriend());
        $failStatusIds = array_diff_key($allIdsFromDb, $successStatusIds);

        $this->getStatusType();

        $this->addCalls($user, $failStatusIds, CallStatusEnum::Fail->value);
        $this->addCalls($user, $successStatusIds, CallStatusEnum::Success->value);

        $this->userRepository->save($user, true);
    }

    /**
     * Получить массив возможных статусов
     *
     * Ключом массива является значение статуса
     *
     * @return void
     */
    private function getStatusType(): void
    {
        $statuses = $this->callStatusRepository->findAll();

        foreach ($statuses as $status) {
            $this->statusTypes[$status->getValue()] = $status;
        }
    }

    /**
     * Добавить созвоны с друзьями
     *
     * @param User $user
     * @param array $friendIds
     * @param string $status
     * @return void
     */
    private function addCalls(User $user, array $friendIds, string $status): void
    {
        foreach ($friendIds as $externalId) {
            $friend = $user
                ->getFriend()
                ->filter(function (Friend $friend) use ($externalId) {
                    return $friend->getExternalId() === $externalId;
                })
                ->first()
            ;

            if (false === $friend) {
                continue;
            }

            /** @var Friend $friend */
            $friend->addCall(
                EntityFactory::createCall($this->statusTypes[$status])
            );
        }
    }
}
