<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class ResponseFormatterSubscriber implements EventSubscriberInterface
{
    public function formatResponse(ViewEvent $event): void
    {
        $payload = [
            'status' => true,
            'data' => $event->getControllerResult(),
        ];

        $event->setControllerResult($payload);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.view' => ['formatResponse', EventPriorities::PRE_SERIALIZE],
        ];
    }
}
