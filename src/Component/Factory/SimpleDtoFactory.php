<?php

namespace App\Component\Factory;

use App\Dto\Friend;

class SimpleDtoFactory
{
    /**
     * Создать DTO Friend
     *
     * @param integer $id
     * @param string $firstName
     * @param string|null $lastName
     * @return Friend
     */
    public static function createFriend(int $id, string $firstName, ?string $lastName = null): Friend
    {
        $friend = new Friend();
        $friend->id = $id;
        $friend->firstName = $firstName;
        $friend->lastName = $lastName;

        return $friend;
    }
}
