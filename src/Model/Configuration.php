<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class Configuration
{
    #[Groups('system:read')]
    private string $wallpaper = 'rocket.jpg';

    public function getWallpaper(): string
    {
        return $this->wallpaper;
    }

    public function setWallpaper(string $wallpaper): void
    {
        $this->wallpaper = $wallpaper;
    }
}
