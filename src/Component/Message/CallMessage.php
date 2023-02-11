<?php

namespace App\Component\Message;

class CallMessage
{
    private int $userId;

    /**
     * @param integer $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Получить идентификатор пользователя
     *
     * @return integer
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}
