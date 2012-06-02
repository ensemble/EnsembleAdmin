<?php
return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        'controller' => 'SlmCmfAdmin\Controller\AdminController',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'page-open' => array(
                        'type' => 'SlmCmfAdmin\Router\Http\Regex',
                        'options' => array(
                            'regex' => '/page/open/(?<id>[0-9]+)(\/(?<params>(.*)))?',
                            'defaults' => array(
                                'controller' => 'SlmCmfAdmin\Controller\PageController',
                                'action' => 'open'
                            ),
                            'spec' => '/blog/%id%/%params%',
                        ),
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
    ),
    
    'di' => array(
        'instance' => array(
            'SlmCmfAdmin\Controller\PageController' => array(
                'parameters' => array(
                    'service' => 'SlmCmfAdmin\Service\Page'
                ),
            ),
            
            'SlmCmfAdmin\Service\Page' => array(
                'parameters' => array(
                    'em' => 'doctrine_em'
                ),
            ),
        ),
    ),
);
