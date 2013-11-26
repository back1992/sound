<?php

namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SystemController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }
public function formDbAdapterAction()
{
    $vm = new ViewModel();
    $vm->setTemplate('form-dependencies/form/form-db-adapter.phtml');

    $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    $form      = new DbAdapterForm($dbAdapter);

    return $vm->setVariables(array(
        'form' => $form
    ));
}

    public function formTableAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('form-dependencies/form/form-table.phtml');

        $tableGateway = $this->getServiceLocator()->get('formdependencies-model-selecttable');
        $form         = new TableForm($tableGateway);

        return $vm->setVariables(array(
            'form' => $form
        ));
    }

}

