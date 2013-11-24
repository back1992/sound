<?php

namespace Ffmpeg\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FfmpegController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }


}

