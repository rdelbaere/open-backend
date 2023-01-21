<?php

namespace App\Security\Voter;

use App\Entity\System;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SystemVoter extends Voter
{
    public const READ = 'SYSTEM_READ';
    public const WRITE = 'SYSTEM_WRITE';
    public const ATTRIBUTES = [self::READ, self::WRITE];

    public function __construct(private Security $security) {}

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, self::ATTRIBUTES) && $subject instanceof System;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $subject->getUser() === $this->security->getUser();
    }
}
