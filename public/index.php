<?php

use League\Container\Container;
use League\Container\ReflectionContainer;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$container->delegate(new ReflectionContainer());
