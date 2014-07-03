<?php

use Silex\Application;

$app = new Application();
$app['debug'] = true;

$app['root'] = dirname(__DIR__);
$app['view.path'] = $app['root'].'/views';

// register service provider
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => $app['view.path'],
));
$app->register(new Services\TemplateServiceProvider());

// routes
$app->get('/', function () use ($app) {
    return $app['templating']->render('indexView.php', []);
});

$app->get('/hello/{name}', function ($name) use ($app) {
        return 'Hello '.$app->escape($name);
});


return $app;
