<?php
namespace App\Event;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationListener
{
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $payload = [
            'status' => true,
            'data' => $event->getData(),
        ];
        
        $event->setData($payload);
    }
}
