<?php

/*
 * This file is part of the GearmanUI package.
 *
 * (c) Rodolfo Ripado <ggaspaio@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GearmanUI;

use Monolog\Logger,
    Monolog\Handler\NullHandler,
    Silex\Application,
    Silex\Provider\ServiceControllerServiceProvider,
    Silex\Provider\TwigServiceProvider,
    Silex\Provider\MonologServiceProvider;

class GearmanUIApplication extends Application
{
    use Application\TwigTrait;

    const DEFAULT_LOG_FILE = '/../../logs/gearmanui.log';

    public function __construct(array $values = array())
    {
        parent::__construct($values);

        # Set run env
        $this['env'] = getenv('APP_ENV') ?: 'prod';

        # Monolog service
        # In test env, do not log.
        $this->register(new MonologServiceProvider, array(
            'monolog.logfile' => __DIR__ . static::DEFAULT_LOG_FILE,
            'monolog.name' => 'GearmanUI'
        ));

        if ('test' === $this['env']) {
            $this['monolog.handler'] = function () {
                return new NullHandler();
            };
        }

        $this->register(new ServiceControllerServiceProvider);

        $this->register(new TwigServiceProvider, array(
            'twig.path' => __DIR__ . '/Resources/views'
        ));

        $this->register(new GearmanFacadeProvider());

        $this->register(new ConfigurationProvider());

        $this->register(new ControllerProvider());
     }
}
