<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class Configuration
{
    #[Groups(['system:read', 'system:write'])]
    private string $wallpaper = 'rocket.jpg';

    #[Groups(['system:read', 'system:write'])]
    private string $theme = 'dark';

    public function getWallpaper(): string
    {
        return $this->wallpaper;
    }

    public function setWallpaper(string $wallpaper): void
    {
        $this->wallpaper = $wallpaper;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): void
    {
        $this->theme = $theme;
    }
}
