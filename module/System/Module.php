<?php

namespace System;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use System\Model\SelectTable;
use System\Model\SelectOption;
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
                ) ,
            ) ,
        );
    }
    public function getServiceConfig()
    {
        
        return array(
            'invokables' => array() ,
            'factories' => array(
                'formdependencies-model-selecttable' => function ($sm)
                {
                    $tableGateway = $sm->get('selecttable-gateway');
                    $table = new SelectTable($tableGateway);
                    
                    return $table;
                }
                ,
                'selecttable-gateway' => function ($sm)
                {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new SelectOption());
                    
                    return new TableGateway('selectoptions', $dbAdapter, null, $resultSetPrototype);
                }
                ,
            )
        );
    }
}
