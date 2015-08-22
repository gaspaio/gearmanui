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
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

class ControllerProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider gearmanuiHTMLUrls
     */
    public function testHTMLRoutes($url, $ajax = false) {
        $app = $this->getApplication();
        $request = Request::create($url);
        $response = $app->handle($request);
        $this->assertEquals('200', $response->getStatusCode());
    }


    /**
     * @dataProvider gearmanuiJSONUrls
     */
    public function testJSONRoutes($url) {
        $app = $this->getApplication();
        $app['gearmanui.servers'] = array();
        // Test Error condition: all json calls should be via ajax
        $request = Request::create($url);
        $response = $app->handle($request);
        $this->assertEquals('200', $response->getStatusCode());
    }


    public function gearmanuiHTMLUrls() {
        return array(
            array('/'),
            array('/status'),
            array('/servers'),
            array('/workers'),
        );
    }


    public function gearmanuiJSONUrls() {
        return array(
            array('/info'),
        );
    }


    protected function getApplication()
    {
        $app = new GearmanUIApplication();
        return $app;
    }
}
