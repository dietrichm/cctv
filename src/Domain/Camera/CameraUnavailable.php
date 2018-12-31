<?php

namespace Detroit\Cctv\Domain\Camera;

use Exception;

final class CameraUnavailable extends Exception
{
    public static function withName(string $name): self
    {
        return new self('Camera ' . $name . ' unavailable');
    }
}
