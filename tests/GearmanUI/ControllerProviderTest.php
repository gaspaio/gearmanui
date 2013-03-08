<?php

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

        // Test Error condition: all json calls should be via ajax
        $request = Request::create($url);
        $response = $app->handle($request);
        $this->assertEquals('404', $response->getStatusCode());

        $request = Request::create($url);
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $app['gearmanui.servers'] = array();
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