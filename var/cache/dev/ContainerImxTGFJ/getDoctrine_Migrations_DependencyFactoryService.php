<?php

namespace ContainerImxTGFJ;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getDoctrine_Migrations_DependencyFactoryService extends App_KernelDevDebugContainer
{
    /**
     * Gets the private 'doctrine.migrations.dependency_factory' shared service.
     *
     * @return \Doctrine\Migrations\DependencyFactory
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/vendor/doctrine/migrations/src/DependencyFactory.php';
        include_once \dirname(__DIR__, 4).'/vendor/doctrine/migrations/src/Configuration/Migration/ConfigurationLoader.php';
        include_once \dirname(__DIR__, 4).'/vendor/doctrine/migrations/src/Configuration/Migration/ExistingConfiguration.php';
        include_once \dirname(__DIR__, 4).'/vendor/doctrine/migrations/src/Configuration/Configuration.php';
        include_once \dirname(__DIR__, 4).'/vendor/doctrine/migrations/src/Metadata/Storage/MetadataStorageConfiguration.php';
        include_once \dirname(__DIR__, 4).'/vendor/doctrine/migrations/src/Metadata/Storage/TableMetadataStorageConfiguration.php';
        include_once \dirname(__DIR__, 4).'/vendor/doctrine/migrations/src/Configuration/EntityManager/EntityManagerLoader.php';
        include_once \dirname(__DIR__, 4).'/vendor/doctrine/migrations/src/Configuration/EntityManager/ManagerRegistryEntityManager.php';

        $a = new \Doctrine\Migrations\Configuration\Configuration();
        $a->addMigrationsDirectory('DoctrineMigrations', (\dirname(__DIR__, 4).'/migrations'));
        $a->setAllOrNothing(false);
        $a->setCheckDatabasePlatform(true);
        $a->setTransactional(true);
        $a->setMetadataStorageConfiguration(new \Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration());

        $container->privates['doctrine.migrations.dependency_factory'] = $instance = \Doctrine\Migrations\DependencyFactory::fromEntityManager(new \Doctrine\Migrations\Configuration\Migration\ExistingConfiguration($a), \Doctrine\Migrations\Configuration\EntityManager\ManagerRegistryEntityManager::withSimpleDefault(($container->services['doctrine'] ?? self::getDoctrineService($container))), ($container->privates['monolog.logger'] ?? $container->load('getMonolog_LoggerService')));

        $instance->setDefinition('Doctrine\\Migrations\\Version\\MigrationFactory', #[\Closure(name: 'doctrine.migrations.migrations_factory', class: 'Doctrine\\Migrations\\Version\\MigrationFactory')] fn () => ($container->privates['doctrine.migrations.migrations_factory'] ?? $container->load('getDoctrine_Migrations_MigrationsFactoryService')));

        return $instance;
    }
}
