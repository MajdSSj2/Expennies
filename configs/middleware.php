<?php

declare(strict_types=1);

use App\Config;
use App\Middleware\csrfFieldsMiddleware;
use App\Middleware\OldDataMiddleware;
use App\Middleware\SessionMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use App\Middleware\ValidationExceptionMiddleware;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $container = $app->getContainer();
    $config = $container->get(Config::class);


    // Twig
    $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));

    $app->add(csrfFieldsMiddleware::class);
    $app->add('csrf');
    $app->add(OldDataMiddleware::class);
    $app->add(ValidationErrorsMiddleware::class);
    $app->add(ValidationExceptionMiddleware::class);
    $app->add(SessionMiddleware::class);

    // Logger
    $app->addErrorMiddleware(
        (bool)$config->get('display_error_details'),
        (bool)$config->get('log_errors'),
        (bool)$config->get('log_error_details')
    );
};
