<?php

namespace App\Util\Exception;

use Throwable;

class BackendFilesystemException extends BackendException
{
    protected ?string $path;

    public function __construct(
        int $statusCode,
        string $message = '',
        Throwable $previous = null,
        array $headers = [],
        int $code = 0,
        ?string $path = null
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
        $this->path = $path;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
}
