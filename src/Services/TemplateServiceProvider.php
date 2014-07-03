<?php
namespace Services;

use Silex\Application;
use Silex\ServiceProviderInterface;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\DelegatingEngine;
use Symfony\Bridge\Twig\TwigEngine;

/**
 * Class TemplateServiceProvider
 * @package Services
 */
class TemplateServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app['templating.engines'] = $app->share(function() {
            return ['php', 'twig'];
        });
        $app['templating.loader'] = $app->share(function() use ($app) {
            return new FilesystemLoader($app['view.path'].'/%name%');
        });
        $app['templating.template_name_parser'] = $app->share(function() {
            return new TemplateNameParser();
        });

        $app['templating.engine.php'] = $app->share(function() use ($app) {
            return new PhpEngine($app['templating.template_name_parser'], $app['templating.loader']);
        });

        $app['templating.engine.twig'] = $app->share(function() use ($app) {
            return new TwigEngine($app['twig'], $app['templating.template_name_parser']);
        });

        $app['templating'] = $app->share(function() use ($app) {
            $engines = array();

            foreach ($app['templating.engines'] as $i => $engine) {
                if (is_string($engine)) {
                    $engines[$i] = $app[sprintf('templating.engine.%s', $engine)];
                }
            }

            return new DelegatingEngine($engines);
        });

    }

    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
}
