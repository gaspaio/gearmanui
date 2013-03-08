<?php

namespace GearmanUI;

use Silex\Application,
    Monolog\Logger,
    Silex\Provider\ServiceControllerServiceProvider,
    Silex\Provider\TwigServiceProvider,
    Silex\Provider\MonologServiceProvider,
    Symfony\Component\Yaml\Yaml;

class GearmanUIApplication extends Application
{
    public function __construct(array $values = array())
    {
        $config = Yaml::parse(__DIR__ . '/../../app/config/gearmanui.yml');
        parent::__construct(array_merge($values, $config));

        // TODO Allow config to overwrite monolog config
        $this->register(new MonologServiceProvider, array(
            'monolog.logfile' => __DIR__.'/../../app/logs/gearmanui.log',
            'monolog.name' => 'GearmanUI'
        ));

        $this->register(new ServiceControllerServiceProvider);

        $this->register(new TwigServiceProvider, array(
            'twig.path' => __DIR__ . '/Resources/views'
        ));

        $this->register(new GearmanFacadeProvider());

        $this->register(new ControllerProvider());
     }
}