<?php

namespace App\Dto;

use JMS\Serializer\Annotation;

class Friend
{
    /**
     * Идентификатор друга
     *
     * @var int
     * @Annotation\Type("int")
     * @Annotation\SerializedName("id")
     */
    public int $id;

    /**
     * Имя друга
     *
     * @var string
     * @Annotation\Type("string")
     * @Annotation\SerializedName("firstName")
     */
    public string $firstName;

    /**
     * Фамилия друга
     *
     * @var string|null
     * @Annotation\Type("string")
     * @Annotation\SerializedName("lastName")
     */
    public ?string $lastName = null;
}
