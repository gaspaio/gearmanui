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

use Silex\Application,
    Silex\ServiceProviderInterface,
    Symfony\Component\Yaml\Yaml;


class ConfigurationProvider implements ServiceProviderInterface {

    const CONFIG_FILE = '/../../config.yml';

    public function register(Application $app) {

        if (!is_file(__DIR__ . static::CONFIG_FILE)) {
            throw new \Exception(
                sprintf('The GearmanUI config file \'%1$s\' doesn\'t seem to exist. Copy the default \'%1$s.dist\' and rename it to \'%1$s\'.', static::CONFIG_FILE));
        }

        $config = Yaml::parse(__DIR__ . static::CONFIG_FILE);

        foreach ($config as $key => $param) {
            $app[$key] = $param;
        }

    }


    public function boot(Application $app) {
    }
}
