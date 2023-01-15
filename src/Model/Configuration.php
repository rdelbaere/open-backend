<?php

namespace App\Model;

class Configuration
{
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
