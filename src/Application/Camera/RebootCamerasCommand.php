<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;
use Detroit\Cctv\Domain\Camera\RebootCameraCommand;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RebootCamerasCommand extends Command
{
    /**
     * @var CameraRepository
     */
    private $cameraRepository;

    /**
     * @var CommandBus
     */
    private $commandBus;

    protected static $defaultName = 'reboot-cameras';

    public function __construct(
        CameraRepository $cameraRepository,
        CommandBus $commandBus
    ) {
        parent::__construct();

        $this->cameraRepository = $cameraRepository;
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this->setDescription('Reboot all cameras');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): void {
        foreach ($this->cameraRepository->findAll() as $camera) {
            $this->commandBus->handle(
                new RebootCameraCommand(
                    $camera->getName()
                )
            );
        }
    }
}
