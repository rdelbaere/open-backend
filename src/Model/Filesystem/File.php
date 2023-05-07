<?php

namespace App\Model\Filesystem;

use App\Entity\Tempfile;

class File extends Resource
{
    private Tempfile $tempfile;

    public function getTempfile(): Tempfile
    {
        return $this->tempfile;
    }

    public function setTempfile(Tempfile $tempfile): self
    {
        $this->tempfile = $tempfile;

        return $this;
    }


}
