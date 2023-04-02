<?php

namespace App\Event;

use App\Util\Exception\BackendException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    const TRANSLATION_DOMAIN = 'exceptions';

    public function __construct(private TranslatorInterface $translator) {}

    public function formatException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($exception instanceof BackendException) {
            $messageId = $exception->getMessage();
        } else {
            $messageId = 'default';
        }

        $body = [
            'status' => false,
            'message' => $this->translator->trans(
                id: $messageId,
                domain: self::TRANSLATION_DOMAIN,
            ),
        ];

        $response = new JsonResponse($body, $statusCode);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ["formatException"],
        ];
    }
}
