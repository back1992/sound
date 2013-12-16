<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonSnoopy for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'snoopy' => array(
                'type'
                => 'segment',
                'options' => array(
                    'route'
                    => '/snoopy[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'
                        => '[a-zA-Z0-9_-]+',
                        ),
                    'defaults' => array(
                        'controller' => 'Snoopy\Controller\Snoopy',
                        'action'
                        => 'index',
                        ),
                    ),
                ),
            ),
        ),
    'controllers' => array(
        'invokables' => array(
            'Snoopy\Controller\Snoopy' => 'Snoopy\Controller\SnoopyController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'snoopy/index/index' => __DIR__ . '/../view/snoopy/index/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
);
