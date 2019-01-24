<?php

namespace Detroit\Cctv\Application\Camera;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RebootCamerasCommand extends Command
{
    protected static $defaultName = 'reboot-cameras';

    protected function configure(): void
    {
        $this->setDescription('Reboot all cameras');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): void {
    }
}
