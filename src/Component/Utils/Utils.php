<?php

namespace App\Component\Utils;

use App\Entity\Friend;
use Doctrine\Common\Collections\Collection;

class Utils
{
    /**
     * Получить массив внешних идентификаторов друзей
     *
     * Ключом массива является внешний идентификатор
     *
     * @param Collection<int, Friend> $friends
     * @return array
     */
    public static function getFriendsId(Collection $friends): array
    {
        $result = [];

        foreach ($friends as $friend) {
            $result[$friend->getExternalId()] = $friend->getExternalId();
        }

        return $result;
    }
}
