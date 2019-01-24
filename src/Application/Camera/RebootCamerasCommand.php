<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RebootCamerasCommand extends Command
{
    /**
     * @var CameraRepository
     */
    private $cameraRepository;

    protected static $defaultName = 'reboot-cameras';

    public function __construct(CameraRepository $cameraRepository)
    {
        parent::__construct();

        $this->cameraRepository = $cameraRepository;
    }

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
