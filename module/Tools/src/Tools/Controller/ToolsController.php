<?php
namespace Tools\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use FFMpeg;
use Audio\Myclass\Dandan;
use Audio\Myclass\DBConnection;
class ToolsController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function readdocAction()
    {
        // return array('doc' => Dandan::read_pdf_file("./public/file/2013.6CET4.doc"));
        // return array('doc' => Dandan::read_pdf_file("./public/file/2013.6CET4.pdf"));
        return array('doc' => Dandan::read_doc_file("./public/file/2013.6CET4.doc"));
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
    public function readquestionAction()
    {
        $subject = Dandan::read_doc_file("./data/cet4/00-10/00/cet4_200006.doc");
        // echo $content;
        $pattern = '/(\d{1,2})\.\s+A\)([^)]*)\s+B\)([^)]*)\s+C\)([^)]*)\s+D\)([^.)]*)/';
preg_match_all($pattern, $subject, $match);
// var_dump($match);

for ($i=0; $i < count($match['0']) ; $i++) {

    # code...
    $select[$i]['no'] = $match['1'][$i];
    $select[$i]['A']= trim($match['2'][$i]);
    $select[$i]['B']= trim($match['3'][$i]);
    $select[$i]['C'] = trim($match['4'][$i]);
    $select[$i]['D'] = trim($match['5'][$i]);
}
        // return array('doc' => Dandan::parseWord("./public/file/2013.6CET4.doc"));
// var_dump($select);
//         return false;
return array('select' => $select);
}
public function  savequestionAction()
{
    $mongo = DBConnection::instantiate();
        //get a MongoGridFS instance
        $collection = $mongo->getCollection('question');
        $quizfile = "./data/cet4/00-10/00/cet4_200006.doc";
        $quizname = pathinfo($quizfile, PATHINFO_FILENAME);
    $subject = Dandan::read_doc_file($quizfile);
        // echo $content;
    $pattern = '/(\d{1,2})\.\s+A\)([^)]*)\s+B\)([^)]*)\s+C\)([^)]*)\s+D\)([^.)]*)/';
preg_match_all($pattern, $subject, $match);
// var_dump($match);

for ($i=0; $i < count($match['0']) ; $i++) {

    # code...
    $select[$i]['no'] = $match['1'][$i];
    $select[$i]['quiz'] = $quizname;
    $select[$i]['A']= trim($match['2'][$i]);
    $select[$i]['B']= trim($match['3'][$i]);
    $select[$i]['C'] = trim($match['4'][$i]);
    $select[$i]['D'] = trim($match['5'][$i]);

    var_dump($collection->insert($select[$i]));
}
        // return array('doc' => Dandan::parseWord("./public/file/2013.6CET4.doc"));
// var_dump($select);

        
        var_dump($collection);
        return false;
return array('select' => $select);
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
public function iframeAction()
{
    // $homepage = file_get_contents('http://www.baidu.com/', false);
//     $curl = curl_init();
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_URL, 'http://www.baidu.com');
// $homepage = curl_exec($curl);
// curl_close($curl);
//     var_dump($homepage);
// echo $homepage;
         $result = new ViewModel();
    $result->setTerminal(true);

    return $result;
}
}
