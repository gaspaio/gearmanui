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
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\HttpFoundation\Request;

class ControllerProvider implements ServiceProviderInterface {

    public function register(Application $app) {
        $app->get('/', function() use ($app) {
            return $app->renderView('index.html.twig', array('settings' => $app['gearmanui.settings']));
        });

        $app->get('/status', function() use ($app) {
            return $app->renderView('status.html.twig');
        });

        $app->get('/workers', function() use ($app) {
            return $app->renderView('workers.html.twig');
        });

        $app->get('/servers', function() use ($app) {
            return $app->renderView('servers.html.twig');
        });

        $app->get('/info', function(Request $request) use ($app) {

            $info = $app['gearman.serverInfo']->getServersInfo();
            return new JsonResponse($info);
            // return $app->renderView('gearman.json.twig');
        });
    }


    public function boot(Application $app) {
    }
}
