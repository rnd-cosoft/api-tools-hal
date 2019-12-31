<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-hal for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-hal/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-hal/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Hal\Factory;

use Laminas\ApiTools\Hal\Factory\HalControllerPluginFactory;
use Laminas\ApiTools\Hal\Plugin\Hal as HalPlugin;
use Laminas\Hydrator\HydratorPluginManager;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\HelperPluginManager;
use PHPUnit\Framework\TestCase;

class HalControllerPluginFactoryTest extends TestCase
{
    public function testInstantiatesHalJsonRenderer()
    {
        $viewHelperManager = $this->prophesize(HelperPluginManager::class);
        $viewHelperManager->get('Hal')
            ->willReturn(new HalPlugin(new HydratorPluginManager(new ServiceManager())))
            ->shouldBeCalledTimes(1);

        $services = new ServiceManager();
        $services->setService('ViewHelperManager', $viewHelperManager->reveal());

        $factory = new HalControllerPluginFactory();
        $plugin = $factory($services, 'Hal');

        $this->assertInstanceOf(HalPlugin::class, $plugin);
    }

    public function testInstantiatesHalJsonRendererWithV2()
    {
        $viewHelperManager = $this->prophesize(HelperPluginManager::class);
        $viewHelperManager->get('Hal')
            ->willReturn(new HalPlugin(new HydratorPluginManager(new ServiceManager())))
            ->shouldBeCalledTimes(1);

        $services = new ServiceManager();
        $services->setService('ViewHelperManager', $viewHelperManager->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($services)
            ->shouldBeCalledTimes(1);

        $factory = new HalControllerPluginFactory();
        $plugin = $factory->createService($pluginManager->reveal());

        $this->assertInstanceOf(HalPlugin::class, $plugin);
    }
}
