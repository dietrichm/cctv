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
            $view = new Twig(
                __DIR__ . '/../../resources/templates',
                [
                    'cache' => __DIR__ . '/../../tmp/twig',
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
