<?php
namespace Quiz;
use PhlyMongo\MongoCollectionFactory;
use PhlyMongo\MongoDbFactory;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    ),
                ),
            );
    }

    public function getServiceConfig()
    {
        return array('factories' => array(
            'Quiz\Mongo'           => 'PhlyMongo\MongoConnectionFactory',
            'Quiz\MongoDB'         => new MongoDbFactory('cet4', 'Quiz\Mongo'),
            'Quiz\MongoCollection' => new MongoCollectionFactory('question', 'Quiz\MongoDB'),
            ));
    }
}
