<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Ffmpeg\Controller\Ffmpeg' => 'Ffmpeg\Controller\FfmpegController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'ffmpeg' => array(
                'type'
                => 'segment',
                'options' => array(
                    'route'
                    => '/ffmpeg[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'
                        => '[a-zA-Z0-9_-]+',
                        ),
                    'defaults' => array(
                        'controller' => 'Ffmpeg\Controller\Ffmpeg',
                        'action'
                        => 'index',
                        ),
                    ),
                ),
            ),
        ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ffmpeg' => __DIR__ . '/../view',
        ),
    ),
);