<?php

namespace App\EventSubscriber;

use App\Component\Exception\ControllerException;
use App\Component\Factory\DtoResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class DtoResponseSubscriber implements EventSubscriberInterface
{
    /**
     * @param DtoResponseFactory $dtoResponseFactory
     */
    public function __construct(private readonly DtoResponseFactory $dtoResponseFactory)
    {
    }

    /**
     * @param ViewEvent $event
     *
     * @return void
     * @throws ControllerException
     */
    public function onKernelView(ViewEvent $event): void
    {
        $response = $this->dtoResponseFactory->create($event->getControllerResult());

        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.view' => 'onKernelView',
        ];
    }
}
