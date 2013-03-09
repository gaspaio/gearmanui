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

class GearmanFacadeTest extends \PHPUnit_Framework_TestCase
{
    protected $logger;

    protected $managerFactory;

    public function setUp() {
        $this->logger = $this->getMock('Monolog\Logger', array(), array('mylogger'));
    }


    public function testConstructor()
    {
        $servers = array(
            array('s1' => 'test server'),
            array('s2' => 'another test server')
        );


        $managerFactory = function() {
            return ;
        };

        $gearmanFacade = new GearmanFacade($servers, $managerFactory, $this->logger);

        $this->assertEquals($servers, $gearmanFacade->getServers());

        $actual_factory =  $gearmanFacade->getManagerFactory();
        $expected_factory = $managerFactory;
        $this->assertEquals($expected_factory, $actual_factory);

        $this->assertInstanceOf('Monolog\Logger', $gearmanFacade->getLogger());
    }


    public function testGetServerInfoConnectionFailed() {

        $managerFactory = function ($server_addr) {
            throw new \Exception("Connection Error");
        };

        $gearmanFacade = new GearmanFacade(array(), $managerFactory, $this->logger);

        $server = array(
            'name' => 'testServer',
            'addr' => '1.2.3.4:7656'
        );

        $info = $gearmanFacade->getServerInfo($server);

        $this->assertFalse($info['up']);
        $this->assertRegExp('/^.*testServer.*$/', $info['error']);
    }


    public function testGetServerInfoDataFetchFailed() {

        $manager_mock = $this->getMock(
            '\Net_Gearman_Manager',
            array('version', 'workers', 'status'),
            array('server addr'),
            '',
            false
        );

        $manager_mock->expects($this->any())
            ->method('version')
            ->will($this->throwException(new \Exception("DataFetchError")));

        $manager_mock->expects($this->any())
            ->method('workers')
            ->will($this->returnValue("workers_array"));

        $manager_mock->expects($this->any())
            ->method('status')
            ->will($this->returnValue("status_array"));

        $managerFactory = function ($server_addr) use($manager_mock) {
            return $manager_mock;
        };

        $gearmanFacade = new GearmanFacade(array(), $managerFactory, $this->logger);

        $server = array(
            'name' => 'testServer',
            'addr' => '1.2.3.4:7656'
        );

        $info = $gearmanFacade->getServerInfo($server);

        $this->assertTrue($info['up']);
        $this->assertRegExp('/^.*testServer.*DataFetchError.*$/', $info['error']);
    }


    public function testGetServerInfoDataFetchSuccess() {

        $manager_mock = $this->getMock(
            '\Net_Gearman_Manager',
            array('version', 'workers', 'status'),
            array('server addr'),
            '',
            false
        );

        $manager_mock->expects($this->any())
            ->method('version')
            ->will($this->returnValue("workers_array"));

        $manager_mock->expects($this->any())
            ->method('workers')
            ->will($this->returnValue("workers_array"));

        $manager_mock->expects($this->any())
            ->method('status')
            ->will($this->returnValue("status_array"));

        $managerFactory = function ($server_addr) use($manager_mock) {
            return $manager_mock;
        };

        $gearmanFacade = new GearmanFacade(array(), $managerFactory, $this->logger);

        $server = array(
            'name' => 'testServer',
            'addr' => '1.2.3.4:7656'
        );

        $info = $gearmanFacade->getServerInfo($server);

        $this->assertTrue($info['up']);
        $this->assertArrayHasKey('version', $info);
        $this->assertArrayHasKey('workers', $info);
        $this->assertArrayHasKey('status', $info);
        $this->assertArrayNotHasKey('error', $info);
    }
}