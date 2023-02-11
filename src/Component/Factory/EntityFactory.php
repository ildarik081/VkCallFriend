<?php

namespace App\Component\Factory;

use App\Entity\Call;
use App\Entity\CallStatus;
use App\Entity\Friend;
use App\Entity\User;

class EntityFactory
{
    /**
     * Создать сущность пользователя
     *
     * @param string $sessionId
     * @param integer $externalId
     * @param string $accessToken
     * @return User
     */
    public static function createUser(string $sessionId, int $externalId, string $accessToken): User
    {
        $user = new User();
        $user
            ->setSessionId($sessionId)
            ->setExternalId($externalId)
            ->setAccessToken($accessToken)
        ;

        return $user;
    }

    /**
     * Создать сущность друга
     *
     * @param integer $externalId
     * @param string $firstName
     * @param string|null $lastName
     * @return Friend
     */
    public static function createFriend(int $externalId, string $firstName, ?string $lastName = null): Friend
    {
        $friend = new Friend();
        $friend
            ->setExternalId($externalId)
            ->setFirstName($firstName)
            ->setLastName($lastName)
        ;

        return $friend;
    }

    /**
     * Создать сущность Call
     *
     * @param CallStatus $status
     * @return Call
     */
    public static function createCall(CallStatus $status): Call
    {
        $call = new Call();
        $call->setStatus($status);

        return $call;
    }
}
