<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonAudio for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'audio' => array(
                'type'
                => 'segment',
                'options' => array(
                    'route'
                    => '/audio[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'
                        => '[a-zA-Z0-9_-]+',
                        ),
                    'defaults' => array(
                        'controller' => 'Audio\Controller\Audio',
                        'action'
                        => 'index',
                        ),
                    ),
                ),
            ),
        ),
    'controllers' => array(
        'invokables' => array(
            'Audio\Controller\Audio' => 'Audio\Controller\AudioController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'audio/index/index' => __DIR__ . '/../view/audio/index/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
);
