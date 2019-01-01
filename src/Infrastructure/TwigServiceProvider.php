<?php

namespace Detroit\Cctv\Infrastructure;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Http\Uri;
use Slim\Interfaces\RouterInterface;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

final class TwigServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        Twig::class,
    ];

    public function register(): void
    {
        $this->getContainer()->share(Twig::class, function () {
            $rootPath = __DIR__ . '/../../';
            $view = new Twig(
                $rootPath . 'resources/templates',
                [
                    'cache' => $rootPath . 'tmp/twig',
                ]
            );

            $view->addExtension(new TwigExtension(
                $this->getContainer()->get(RouterInterface::class),
                Uri::createFromEnvironment(
                    $this->getContainer()->get('environment')
                )
            ));

            return $view;
        });
    }
}
