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
    ini_set('display_errors', '1');
    $module_conf = $this->getServiceLocator()->get('Config');
    $ffmpeg_conf = $module['ffmpeg_config'];

    $basedir = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR;
    // define('BASE', $basedir);
    var_dump($basedir);
    // const BASE = $basedir;
        $config = new \PHPVideoToolkit\Config($ffmpeg_conf);
    // catch(\PHPVideoToolkit\Exception $e)
    // {
    //     echo '<h1>Config set errors</h1>';
    //     \PHPVideoToolkit\Trace::vars($e);
    //     exit;
    // }
    $example_video_path = 'examples/media/BigBuckBunny_320x180.mp4';
    $example_audio_path = 'examples/media/Ballad_of_the_Sneak.mp3';
        $video = new \PHPVideoToolkit\Video($example_video_path, $config);
        $process = $video->getProcess();
    //  $process->setProcessTimelimit(1);
        $output = $video->extractSegment(new \PHPVideoToolkit\Timecode(10), new \PHPVideoToolkit\Timecode(20))
        ->save('./output/big_buck_bunny.3gp', null, \PHPVideoToolkit\Media::OVERWRITE_EXISTING);
        echo '<h1>Executed Command</h1>';
        \PHPVideoToolkit\Trace::vars($process->getExecutedCommand());
        echo '<hr /><h1>FFmpeg Process Messages</h1>';
        \PHPVideoToolkit\Trace::vars($process->getMessages());
        echo '<hr /><h1>Buffer Output</h1>';
        \PHPVideoToolkit\Trace::vars($process->getBuffer(true));
        echo '<hr /><h1>Resulting Output</h1>';
        \PHPVideoToolkit\Trace::vars($output->getOutput()->getMediaPath());
   /* catch(\PHPVideoToolkit\FfmpegProcessOutputException $e)
    {
        echo '<h1>Error</h1>';
        \PHPVideoToolkit\Trace::vars($e);
        $process = $video->getProcess();
        if($process->isCompleted())
        {
            echo '<hr /><h2>Executed Command</h2>';
            \PHPVideoToolkit\Trace::vars($process->getExecutedCommand());
            echo '<hr /><h2>FFmpeg Process Messages</h2>';
            \PHPVideoToolkit\Trace::vars($process->getMessages());
            echo '<hr /><h2>Buffer Output</h2>';
            \PHPVideoToolkit\Trace::vars($process->getBuffer(true));
        }
    }
    catch(\PHPVideoToolkit\Exception $e)
    {
        echo '<h1>Error</h1>';
        \PHPVideoToolkit\Trace::vars($e->getMessage());
        echo '<h2>\PHPVideoToolkit\Exception</h2>';
        \PHPVideoToolkit\Trace::vars($e);
    }*/
}
}
