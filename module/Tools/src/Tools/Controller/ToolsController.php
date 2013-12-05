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
    public function readdocAction()
    {
        return array('doc' => Dandan::read_pdf_file("./public/file/2013.6CET4.doc"));
        // return array('doc' => Dandan::read_pdf_file("./public/file/2013.6CET4.pdf"));
        // return array('doc' => Dandan::read_doc_file("./public/file/2013.6CET4.doc"));
        // return array('doc' => Dandan::parseWord("./public/file/2013.6CET4.doc"));
        return false;
    }
    public function readpdfAction()
    {
        $pdf2 = \ZendPdf\PdfDocument::load('./public/file/2013.6CET4.pdf');
        var_dump($pdf2);
        // return array('doc' => Dandan::parseWord("./public/file/2013.6CET4.doc"));
        return false;
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
}
