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
                    'page' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/page',
                            'defaults' => array(
                                'controller' => 'SlmCmfAdmin\Controller\PageController',
                            ),
                        ),
                        'child_routes' => array(
                            'open' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/open/:id',
                                    'defaults' => array(
                                        'action' => 'open'
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'params' => array(
                                        'type' => 'SlmCmfAdmin\Router\Http\CatchAll',
                                        'options' => array(
                                            'name' => 'params'
                                        ),
                                    ),
                                ),
                            ),
                            'update' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/edit/:id',
                                    'defaults' => array(
                                        'action' => 'update'
                                    ),
                                ),
                            ),
                            'create' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/new',
                                    'defaults' => array(
                                        'action' => 'create'
                                    ),
                                ),
                            ),
                             'delete' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/delete/:id',
                                    'defaults' => array(
                                        'action' => 'delete'
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_map' => array(
            'layout/admin' => __DIR__ . '/../view/layout/admin.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        ),
        'helper_map' => array(
            'adminPageTree' => 'SlmCmfAdmin\View\Helper\PageTree',
            'adminUrl'      => 'SlmCmfAdmin\View\Helper\AdminUrl',
        ),
    ),
);
