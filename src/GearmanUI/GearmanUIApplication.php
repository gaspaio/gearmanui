<?php

namespace GearmanUI;

use Silex\Application,
    Monolog\Logger,
    Silex\Provider\ServiceControllerServiceProvider,
    Silex\Provider\TwigServiceProvider,
    Silex\Provider\MonologServiceProvider;

class GearmanUIApplication extends Application
{
    public function __construct(array $values = array())
    {

        parent::__construct($values);

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

        $this->register(new ConfigurationProvider());

        $this->register(new ControllerProvider());
     }
}