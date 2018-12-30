<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\CameraRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class ListSnapshotsRequestHandler
{
    /**
     * @var CameraRepository
     */
    private $cameraRepository;

    /**
     * @var Twig
     */
    private $view;

    public function __construct(
        CameraRepository $cameraRepository,
        Twig $view
    ) {
        $this->cameraRepository = $cameraRepository;
        $this->view = $view;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $cameras = $this->cameraRepository->findAll();

        return $this->view->render(
            $response,
            'camera/snapshot/list.html.twig',
            [
                'cameras' => $cameras,
            ]
        );
    }
}
