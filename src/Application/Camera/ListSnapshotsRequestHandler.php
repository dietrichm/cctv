<?php

namespace Detroit\Cctv\Application\Camera;

use Detroit\Cctv\Domain\Camera\Camera;
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
        $output = implode('', array_map(function (Camera $camera) {
            return '<img src="/snapshot/' . $camera->getName() . '">';
        }, $this->cameraRepository->findAll()));

        $response->getBody()->write($output);

        return $response;
    }
}
