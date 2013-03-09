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
    Net_Gearman_Manager;

class GearmanFacadeProvider implements ServiceProviderInterface
{
    public function register(Application $app) {
        $app['gearman.manager'] = $app->protect(function ($server_adress) {
            return new \Net_Gearman_Manager($server_adress);
        });

        $app['gearman.serverInfo'] = $app->share(function() use ($app) {
            return new GearmanFacade(
                $app['gearmanui.servers'],
                $app['gearman.manager'],
                $app['monolog']);
        });
    }

    public function boot(Application $app) {}
}