<?php

namespace SlmCmfAdmin;

use Zend\ModuleManager\Feature;
use Zend\EventManager\Event;
use SlmCmfAdmin\Router\AdminRouter;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface,
    Feature\BootstrapListenerInterface
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
    
    public function onBootstrap(Event $e)
    {
        $app = $e->getParam('application');
        $em  = $app->events()->getSharedManager();
        
        $em->attach(__NAMESPACE__, 'dispatch', function($e) {
            $controller = $e->getTarget();
            $controller->layout('layout/admin');
        }, 100);
    }
}
