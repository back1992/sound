<?php
namespace Tools;
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(


    'Zend\Loader\ClassMapAutoloader' => array(
        'PHPVideoToolkit' => __DIR__. '/.classmap.php',
        'PHPVideoToolkit'          => __DIR__ . '/../../vendor/PHPVideoToolkit/autoload_classmap.php',

        // /sound/vendor/PHPVideoToolkit/autoload_classmap.php
    ),


            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
