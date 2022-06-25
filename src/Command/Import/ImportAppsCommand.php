<?php

namespace App\Command\Import;

use App\Entity\App;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:import:apps',
    description: 'Import apps in database',
)]
class ImportAppsCommand extends AbstractImportCommand
{
    protected function getClass(): string
    {
        return App::class;
    }

    protected function getDefaultFile(): string
    {
        return 'data/import/apps.yaml';
    }
}
