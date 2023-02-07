<?php

namespace App\Dto\ControllerResponse;

use App\Component\Interface\Controller\ControllerResponseInterface;
use JMS\Serializer\Annotation;

class ExceptionResponse implements ControllerResponseInterface
{
    /**
     * @var string
     * @Annotation\Type("string")
     * @Annotation\SerializedName("code")
     */
    public string $code;

    /**
     * @var string
     * @Annotation\Type("string")
     * @Annotation\SerializedName("message")
     */
    public string $message;

    /**
     * @var array
     * @Annotation\Type("array")
     * @Annotation\SerializedName("trace")
     */
    public array $trace;
}
