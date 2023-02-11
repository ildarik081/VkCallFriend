<?php

namespace App\Component\Utils;

class Aliases
{
    /** Ссылка на которую переадресует VK после подтверждения доступа */
    public const VK_REDIRECT_URL = 'http://localhost/vk/callback';

    /** Статус созвона */
    public const CALL_STATUS = [
        'success' => ['value' => 'success', 'description' => 'Успешный созвон'],
        'fail' => ['value' => 'fail', 'description' => 'Неудачный созвон']
    ];
}
