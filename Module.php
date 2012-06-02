<?php

namespace SlmCmfAdmin;

use Zend\ModuleManager\Feature;
use SlmCmfAdmin\Router\AdminRouter;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'slmCmfAdminRouter' => function ($sm) {
                    $config = $sm->get('config');
                    $routes = $config['cmf_admin_routes'];
                    
                    $router = new AdminRouter($routes);
                    return $router;
                }
            ),
        );
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
