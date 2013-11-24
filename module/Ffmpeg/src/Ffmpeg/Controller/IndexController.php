<?php

namespace Ffmpeg\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
    	ini_set('display_errors', '1');
    	
        return new ViewModel();
    }


}

