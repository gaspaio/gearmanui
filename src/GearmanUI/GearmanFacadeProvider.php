<?php

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