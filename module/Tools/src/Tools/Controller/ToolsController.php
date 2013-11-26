<?php

namespace Tools\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use FFMpeg;
use Audio\Myclass\Dandan;

class ToolsController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function pffmpegAction()
    {
        $ffmpeg = FFMpeg\FFMpeg::create();
        		$video = $ffmpeg->open(Dandan::VDIR.'2.wmv');
        		$video
        		->filters()
        		->resize(new FFMpeg\Coordinate\Dimension(320, 240))
        		->synchronize();
        		$video
        		->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))
        		->save('frame.jpg');
        		$video
        		->save(new FFMpeg\Format\Video\X264(), 'export-x264.mp4')
        		->save(new FFMpeg\Format\Video\WMV(), 'export-wmv.wmv')
        		->save(new FFMpeg\Format\Video\WebM(), 'export-webm.webm');
        		return new ViewModel();
    }

    public function formatAction()
    {
    	        $ffmpeg = FFMpeg\FFMpeg::create();
        		$video = $ffmpeg->open(Dandan::VDIR.'2.wmv');
    	$format = new \FFMpeg\Format\Video\X264();
$format->on('progress', function ($video, $format, $percentage) {
    echo "$percentage % transcoded";
});

$format
    -> setKiloBitrate(1000)
    -> setAudioKiloBitrate(256);

$video->save($format, Dandan::VDIR.'3.mpeg');
        return new ViewModel();
    }


}

