<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonTools for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'tools' => array(
                'type'
                => 'segment',
                'options' => array(
                    'route'
                    => '/tools[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'
                        => '[a-zA-Z0-9_-]+',
                        ),
                    'defaults' => array(
                        'controller' => 'Tools\Controller\Tools',
                        'action'
                        => 'index',
                        ),
                    ),
                ),
            ),
        ),
    'controllers' => array(
        'invokables' => array(
            'Tools\Controller\Tools' => 'Tools\Controller\ToolsController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'tools/index/index' => __DIR__ . '/../view/tools/index/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    //ffmepg config
    'ffmpeg_config' => array(
            'temp_directory'              => './data/tmp',
            'ffmpeg'                      => '/usr/local/bin/ffmpeg',
            'ffprobe'                     => '/usr/local/bin/ffprobe',
            'yamdi'                       => '/usr/local/bin/yamdi',
            'qtfaststart'                 => '/usr/local/bin/qt-faststart',
            'gif_transcoder'              => 'php',
            'convert'                     => '/usr/local/bin/convert',
            'gifsicle'                    => '/usr/local/bin/gifsicle',
            'php_exec_infinite_timelimit' => true,
           ),


);
