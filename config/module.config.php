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
                        'type' => 'SlmCmfAdmin\Router\Http\Segment',
                        'options' => array(
                            'route' => '/page/open/:id[/:params]',
                            'defaults' => array(
                                'controller' => 'SlmCmfAdmin\Controller\PageController',
                                'action' => 'open'
                            ),
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
);
