<?php

namespace Audio\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AudioController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }


}

