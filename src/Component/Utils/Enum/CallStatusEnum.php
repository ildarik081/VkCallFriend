<?php

namespace App\Component\Utils\Enum;

enum CallStatusEnum: string
{
    /** Успешный созвон */
    case Success = 'success';

    /** Неудачный созвон */
    case Fail = 'fail';

    public function getDescription(): string
    {
        return match ($this) {
            self::Success => 'Успешный созвон',
            self::Fail => 'Неудачный созвон'
        };
    }
}
