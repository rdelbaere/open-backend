<?php
namespace App\Event;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AuthenticationListener
{
    public function __construct(private NormalizerInterface $normalizer){}

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $normalizedUser = $this->normalizer->normalize($event->getUser(), 'json', [
            'groups' => ['user:read'],
        ]);

        $payload = [
            'status' => true,
            'data' => [
                'user' => $normalizedUser,
                ...$event->getData(),
            ],
        ];
        
        $event->setData($payload);
    }
}
