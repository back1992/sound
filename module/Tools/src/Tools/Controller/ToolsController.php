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
   public function convertAction()
   {
    $module_conf = $this->getServiceLocator()->get('Config');
    $ffmpeg_conf = $module_conf['ffmpeg_config'];
    $config = new \PHPVideoToolkit\Config($ffmpeg_conf);
    $example_video_path = './public/media/BigBuckBunny_320x180.mp4';
    $example_audio_path = './public/media/Ballad_of_the_Sneak.mp3';
    $video = new \PHPVideoToolkit\Video($example_video_path, $config);
    $process = $video->getProcess();
    //  $process->setProcessTimelimit(1);
    $output = $video->extractSegment(new \PHPVideoToolkit\Timecode(10), new \PHPVideoToolkit\Timecode(20))
    ->save('./public/output/big_buck_bunny.3gp', null, \PHPVideoToolkit\Media::OVERWRITE_EXISTING);
    echo '<h1>Executed Command</h1>';
    \PHPVideoToolkit\Trace::vars($process->getExecutedCommand());
    echo '<hr /><h1>FFmpeg Process Messages</h1>';
    \PHPVideoToolkit\Trace::vars($process->getMessages());
    echo '<hr /><h1>Buffer Output</h1>';
    \PHPVideoToolkit\Trace::vars($process->getBuffer(true));
    echo '<hr /><h1>Resulting Output</h1>';
    \PHPVideoToolkit\Trace::vars($output->getOutput()->getMediaPath());
    return FALSE;
}
public function logspltAction()
{
    $module_conf = $this->getServiceLocator()->get('Config');
    $ffmpeg_conf = $module_conf['ffmpeg_config'];
    $config = new \PHPVideoToolkit\Config($ffmpeg_conf);
    // $example_video_path = './public/media/BigBuckBunny_320x180.mp4';
    // $example_audio_path = './public/media/Ballad_of_the_Sneak.mp3';
    $example_audio_path = './public/audiodata/raw/fengtai.mp3';
    $video = new \PHPVideoToolkit\Audio($example_audio_path, $config);
    $process = $video->getProcess();
    //  $process->setProcessTimelimit(1);
    $output = $video->extractSegment(new \PHPVideoToolkit\Timecode(10), new \PHPVideoToolkit\Timecode(100))
    ->save('./public/output/fengtai3.mp3');


    echo '<h1>Executed Command</h1>';
    \PHPVideoToolkit\Trace::vars($process->getExecutedCommand());
    echo '<hr /><h1>FFmpeg Process Messages</h1>';
    \PHPVideoToolkit\Trace::vars($process->getMessages());
    echo '<hr /><h1>Buffer Output</h1>';
    \PHPVideoToolkit\Trace::vars($process->getBuffer(true));
    echo '<hr /><h1>Resulting Output</h1>';
    \PHPVideoToolkit\Trace::vars($output->getOutput()->getMediaPath());
    return FALSE;
}

public function editAction(){
       $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        return $viewModel;
}
}
