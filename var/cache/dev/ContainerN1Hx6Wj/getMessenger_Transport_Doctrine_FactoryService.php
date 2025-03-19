<?php

namespace ContainerN1Hx6Wj;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getMessenger_Transport_Doctrine_FactoryService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'messenger.transport.doctrine.factory' shared service.
     *
     * @return \Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransportFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/symfony/messenger/Transport/TransportFactoryInterface.php';
        include_once \dirname(__DIR__, 4).'/vendor/symfony/doctrine-messenger/Transport/DoctrineTransportFactory.php';

        return $container->privates['messenger.transport.doctrine.factory'] = new \Symfony\Component\Messenger\Bridge\Doctrine\Transport\DoctrineTransportFactory(($container->services['doctrine'] ?? self::getDoctrineService($container)));
    }
}
