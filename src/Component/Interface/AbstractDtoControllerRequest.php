<?php

namespace App\Component\Interface;

use App\Component\Interface\Controller\ControllerRequestInterface;
use JMS\Serializer\Annotation;

abstract class AbstractDtoControllerRequest implements ControllerRequestInterface
{
    /**
     * Сессия пользователя
     *
     * @var string
     * @Annotation\Type("string")
     * @Annotation\SerializedName("session")
     */
    public string $session;
}
