<?php

namespace Detroit\Cctv\Infrastructure\Http;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\Views\Twig;

final class TemplateServiceProvider extends AbstractServiceProvider
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
            return new Twig(
                __DIR__ . '/../../../resources/templates',
                [
                    'cache' => false,
                ]
            );
        });
    }
}
