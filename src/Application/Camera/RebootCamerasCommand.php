<?php

namespace Detroit\Cctv\Application\Camera;

use Symfony\Component\Console\Command\Command;

final class RebootCamerasCommand extends Command
{
    protected static $defaultName = 'reboot-cameras';

    protected function configure(): void
    {
        $this->setDescription('Reboot all cameras');
    }
}
