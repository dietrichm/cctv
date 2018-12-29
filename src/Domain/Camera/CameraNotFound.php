<?php

namespace Detroit\Cctv\Domain\Camera;

use Exception;

final class CameraNotFound extends Exception
{
    public static function withName(string $name): self
    {
        return new self('Camera ' . $name . ' not found');
    }
}
