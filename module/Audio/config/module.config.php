<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Audio\Controller\Audio' => 'Audio\Controller\AudioController',
            ),
        ),
    'router' => array(
        'routes' => array(
            'audio' => array(
                'type'
                => 'segment',
                'options' => array(
    'route'    => '/audio',
                    'defaults' => array(
                        'controller' => 'Audio\Controller\Audio',
                        'action'
                        => 'index',
                        ),
                    ),
                                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
                ),
            ),
        ),
    'view_manager' => array(
        'template_map' => array(
            'layout/layouttwb-demo' => __DIR__ . '/../view/layout/layouttwb-demo.phtml',
            ),
        'template_path_stack' => array(
            'audio' => __DIR__ . '/../view',
            ),
        ),
    'service_manager'   => array(
        'factories'         => array(
            'dlu_twb_demo_navigation'       => 'DluTwBootstrapDemo\Navigation\Service\DluTwbDemoNavigationFactory',
            ),
        ),
    );

