<?php

namespace App\Security\Voter;

use App\Entity\Filesystem;
use App\Entity\System;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FilesystemVoter extends Voter
{
    public const READ = 'FILESYSTEM_READ';
    public const WRITE = 'FILESYSTEM_WRITE';
    public const ATTRIBUTES = [self::READ, self::WRITE];

    public function __construct(private Security $security) {}

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, self::ATTRIBUTES) && $subject instanceof Filesystem;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        return $subject->getSystem()->getUser() === $this->security->getUser();
    }
}
