<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Patcher;

use Gin0115\WpScoper\Patcher\PatcherFileIO;

class PatcherManager
{
    
    private PatcherFileIO $fileIO;

    public function __construct(PatcherFileIO $fileIO)
    {
        $this->fileIO = $fileIO;
    }
}
