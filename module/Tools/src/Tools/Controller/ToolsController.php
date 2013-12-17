<?php

namespace Tools\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ffmpeg\Form\DirForm;
use Audio\Myclass\DanAudio;
use Audio\Myclass\Dandan;
use Audio\Myclass\DanDb;
use Audio\Myclass\DBConnection;

class ToolsController extends AbstractActionController
{

    public function indexAction()
    {
        $dir = Dandan::DIR;
        $form = new DirForm();
        if (file_exists($dir)) {
            $files = Dandan::dirToArray($dir);
            krsort($files);
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $forwardPlugin = $this->forward();
                    // var_dump($request->getPost());
            switch ($request->getPost()->submit) {
                case 'Read File':
                $returnValue = $forwardPlugin->dispatch('Tools\Controller\Tools', array(
                    'action' => 'readdoc'
                    ));
                break;
                case 'Read Question':
                $returnValue = $forwardPlugin->dispatch('Tools\Controller\Tools', array(
                                // 'action' => 'splt'
                    'action' => 'readquestion'
                    ));
                return $returnValue;
                case 'Save DB':
                $returnValue = $forwardPlugin->dispatch('Tools\Controller\Tools', array(
                    'action' => 'savequestion'
                    ));
                return $returnValue;
                break;
                case 'Total':
                $returnValue = $forwardPlugin->dispatch('Tools\Controller\Tools', array(
                    'action' => 'total'
                    ));
                return $returnValue;
                break;
                        // case 'Scan Dir':
                        // $returnValue = $forwardPlugin->dispatch('Ffmpeg\Controller\Ffmpeg', array(
                        //     'action' => 'scan'
                        //     ));
                        // return $returnValue;
                        // break;
                default:
                return false;
            }
        }

        return array(
            'files' => $files,
            'dir' => $dir,
            'form' => $form
            );
    }

    public function readdocAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $title = $request->getPost()->title;
                    // $audioSDir = Dandan::RAWDIR;
            $audioFile = $title;
            echo Dandan::read_doc_file($audioFile);
            return array('doc' => Dandan::read_doc_file($audioFile));
        }
        return array('doc' => Dandan::read_doc_file("./data/cet4/00-10/03/cet4_200306.doc"));
    }

    public function readpdfAction()
    {
        $pdf2 = \ZendPdf\PdfDocument::load('./public/file/2003.6CET4.pdf');
            //  var_dump($pdf2);
                // return array('doc' => Dandan::parseWord("./public/file/2013.6CET4.doc"));
        return false;
    }

    public function readquestionAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {    
            $audioFile = $request->getPost()->title;
                    // $audioSDir = Dandan::RAWDIR;
                // $audioFile = Dandan::DOCDIR.$title;
            var_dump($audioFile);
            $subject = Dandan::read_doc_file($audioFile);
            echo $subject;
            $pattern = '/(\d{1,2})\.\s+A\)([^)]*)\s+B\)([^)]*)\s+C\)([^)]*)\s+D\)([^\\n]*)/';
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
var_dump($select);
}
return false;
        // return array('select' => $select);
}

public function savequestionAction()
{
    $mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
    $collection = $mongo->getCollection('question');
    $quizfile = "./data/cet4/00-10/03/cet4_200306.doc";
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
    $collection->insert($select[$i]);
}
                // return array('doc' => Dandan::parseWord("./public/file/2013.6CET4.doc"));
        // var_dump($select);
            //  var_dump($collection);
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

public function totalAction()
{
    $mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
    $collection = $mongo->getCollection('question');
    $dir = Dandan::DOCDIR;
    $request = $this->getRequest();
    if ($request->isPost()) {    
       $dir = $request->getPost()->title;
   };
   $files = Dandan::dirToArray($dir, 'TRUE');
   $newFiles = Dandan::flatten_array($files);
   $extArr = array('doc', 'mp3');
   $targetArr = array();
   for($i=0; $i<count($newFiles); $i++) {
    if(in_array(pathinfo($newFiles[$i], PATHINFO_EXTENSION), $extArr)){
        $targetArr[pathinfo($newFiles[$i], PATHINFO_FILENAME)][pathinfo($newFiles[$i], PATHINFO_EXTENSION)] = $newFiles[$i] ;
    }
    else {
        unlink($newFiles[$i]);
        unset($newFiles[$i]);
    }
}
$mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
$collection = $mongo->getCollection('question');
if(count($newFiles) !== 2 * count($targetArr)) echo "something wrong ! ";
            // var_dump($targetArr);
$cmd = "rm -rf ".Dandan::RESDIR;
foreach ($targetArr as $value) {
                # code...
    $targetDir = Dandan::RESDIR . pathinfo($value['doc'], PATHINFO_FILENAME) . DIRECTORY_SEPARATOR;
                // var_dump($targetDir);

    $resmp3 = DanAudio::mp3splt($value['mp3'], $targetDir);
    $resdoc = Dandan::savequestion($value['doc'], $collection);
    var_dump($resdoc);
    var_dump($resmp3);
}
return false;
}

public function totalinsertAction()
{
    $mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
    $collection = $mongo->getCollection('question');
    $dir = Dandan::DOCDIR;
    $request = $this->getRequest();
    if ($request->isPost()) {    
       $dir = $request->getPost()->title;
   };
   $files = Dandan::dirToArray($dir, 'TRUE');
   $newFiles = Dandan::flatten_array($files);
   $extArr = array('doc', 'mp3');
   $targetArr = array();
   for($i=0; $i<count($newFiles); $i++) {
    if(in_array(pathinfo($newFiles[$i], PATHINFO_EXTENSION), $extArr)){
        $targetArr[pathinfo($newFiles[$i], PATHINFO_FILENAME)][pathinfo($newFiles[$i], PATHINFO_EXTENSION)] = $newFiles[$i] ;
    }
    else {
        unlink($newFiles[$i]);
        unset($newFiles[$i]);
    }
}
$mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
$collection = $mongo->getCollection('question');
if(count($newFiles) !== 2 * count($targetArr)) echo "something wrong ! ";
            // var_dump($targetArr);
// $cmd = "rm -rf ".Dandan::RESDIR;
foreach ($targetArr as $value) {
    $file = $value['mp3'];
    $data['audioname'] = basename($value['mp3']);
    $data['filetype'] = 'audio/mp3';
    $data['title'] = '大学英语四级听力';
    $data['monthyear']['month'] = substr($data['audioname'], -6, -4);
    $data['monthyear']['year'] = substr($data['audioname'], -10, -6);
    DanDb::insertFile($file, $data);
    Dandan::savequestion($value['doc'], $collection);
}
return false;
}

public function scantotalAction()
{
    $mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
    $collection = $mongo->getCollection('question');
    $dirs = array_filter(glob(Dandan::RESDIR.'*'), 'is_dir');
        // print_r( $dirs);

    return array('objects' => $dirs, 'collection' => $collection);
}

public function scanaudioAction()
{
    $form = new DirForm();
    $dir = $this->getEvent()->getRouteMatch()->getParam('id');
    $audioDir = Dandan::RESDIR.$dir.DIRECTORY_SEPARATOR;


    // var_dump($audioDir);
            // $audioDir = Dandan::SDIR.'fengtai/';
                        // $sFiles = readdir($audioDir);
                        // $sFiles = glob($audioDir."*.*");
    $rawMp3File = Dandan::RAWDIR.$dir.'.mp3';
    $rawOggFile = Dandan::RAWDIR.$dir.'.ogg';
    if(!file_exists($rawMp3File))  DanDb::fetchAudio($rawMp3File);
    DanAudio::mp32ogg($rawMp3File, $rawOggFile);
    $mp3Files = glob($audioDir.'*.mp3');
    $oggFiles = glob($audioDir.'*.ogg');
    // var_dump($mp3Files);
    // var_dump($oggFiles);
        $data = array(
                'title' => $rawMp3File,
                );
$form->setData($data);
    return array(
        'rawMp3File' => str_replace('./public/', '/', $rawMp3File),
        'rawOggFile' => str_replace('./public/', '/', $rawOggFile),
        'mp3Files' => str_replace('./public/', '/', $mp3Files),
        'oggFiles' => str_replace('./public/', '/', $oggFiles),
        'form' => $form
        ); 
    return false;
}

public function scandocAction()
{
    $quiz = $this->getEvent()->getRouteMatch()->getParam('id');
    $dir = DanAudio::DOCDIR;
        // $Directory = new \RecursiveDirectoryIterator($dir);
        // $Iterator = new \RecursiveIteratorIterator($Directory);
        //  $files = iterator_to_array($Iterator,true); 
        //  var_dump($quiz);
        //  var_dump($files);
    $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), \RecursiveIteratorIterator::SELF_FIRST);
    foreach($objects as $name => $object){
            // echo pathinfo($name, PATHINFO_FILENAME)."<br />";
        if(basename($name, '.doc') == $quiz)  echo $doc = $name;
    }

        // echo Dandan::read_doc_file($doc);
    echo Dandan::read_doc_file($doc);
        // echo Dandan::parseWord($doc);
                    //  $audioFile = $title;
                    // echo Dandan::read_doc_file($audioFile);
    $mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
    $collection = $mongo->getCollection('question');
    $questions = $collection->find(array('quiz' => $quiz));
    return false;
    return array('objects' =>$questions);
}

function respltAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            // var_dump($request->getPost());
            // $fileInfo = pathinfo($audioFile);
            $title = $request->getPost()->title;
            $audioFile = $title;
            // $audioTDir = Dandan::SDIR  . basename($audioFile, '.mp3') . DIRECTORY_SEPARATOR;
            $audioTDir = Dandan::RESDIR  .  pathinfo($title, PATHINFO_FILENAME) . DIRECTORY_SEPARATOR;
            // var_dump($audioFile);
            // var_dump($audioTDir);
            $th = $request->getPost()->th_input;
            $min = $request->getPost()->min_input;
            echo " th = $th , min = $min  <br />";
            // var_dump(Dandan::deleteDirectory($audioTDir));
            // var_dump(chmod($audioTDir, '0777'));
            // var_dump(Dandan::rrmdir($audioTDir));
            if(file_exists($audioTDir)) Dandan::removeDir($audioTDir);  
            if(!file_exists(Dandan::RESDIR)) mkdir(Dandan::RESDIR);
            DanAudio::mp3splt($audioFile, $audioTDir, $th, $min);   
            DanAudio::mp32oggDir($audioTDir);   

            // var_dump($resmp);
            // return $this->redirect()->toRoute('tools', array(
            //     'action' => 'scanaudio',
            //     'id' => pathinfo($audioFile, PATHINFO_FILENAME),
            // ));

        // $this->flashMessenger()->addMessage("You have fetch audio file  $audioname into  <li class='pft-directory'>$audiofiledir</li>");
            $forwardPlugin = $this->forward();
            $returnValue = $forwardPlugin->dispatch('Tools\Controller\Tools', array(
               'action' => 'scanaudio',
                'id' => pathinfo($audioFile, PATHINFO_FILENAME),
            ));
            
            return $returnValue;


        }
        
        return false;
    }
public function editAction()
{
    $quiz = $this->getEvent()->getRouteMatch()->getParam('id');
    $mongo = DBConnection::instantiate();
                //get a MongoGridFS instance
    $collection = $mongo->getCollection('question');
    $res = $collection->ensureIndex(array("A" => 1, "B" => 1), array("unique" => 1, "dropDups" => 1));
         // var_dump($res);
    $questions = $collection->find(array('quiz' => $quiz));
            // return false;
    $audioDir = Dandan::RESDIR.$quiz.DIRECTORY_SEPARATOR;
         // var_dump($audioDir);
            // $audioDir = Dandan::SDIR.'fengtai/';
                        // $sFiles = readdir($audioDir);
                        // $sFiles = glob($audioDir."*.*");
    $mp3Files = glob($audioDir.'*.mp3');

         // var_dump($mp3Files);
    for ($i=0; $i < count($mp3Files) ; $i++) {
        $audioName[$i] =  dirname($mp3Files[$i]). DIRECTORY_SEPARATOR. pathinfo($mp3Files[$i], PATHINFO_FILENAME);
        $oggFiles[$i] = $audioName[$i].'.ogg';
        DanAudio::mp32ogg($mp3Files[$i], $oggFiles[$i]);
        $audioName[$i] = str_replace('./public/', '/', $audioName[$i]);
    }
    return array(
        'objects' =>$questions,
        'audioDir' => $audioDir,
        'audioName' => $audioName,
        );
}

public function deletefileAction()
{
    $id= $this->getEvent()->getRouteMatch()->getParam('id');
        // $id= 'cet4_200301_silence_01';
    $file = DanAudio::RESDIR.substr($id, 0, 11).DIRECTORY_SEPARATOR.$id;
    var_dump($file);
    unlink($file.'.mp3');
    unlink($file.'.ogg');
    return false;
    return new ViewModel();
}

public function mergeupAction()
{
    $id= $this->getEvent()->getRouteMatch()->getParam('id');
    // $id= 'cet4_200301_silence_14';
    $idup = substr($id, -2)  ;
    do {
        $idup--;
        $file = DanAudio::RESDIR.substr($id, 0, 11).DIRECTORY_SEPARATOR.$id;
        $fileup = substr(DanAudio::RESDIR.substr($id, 0, 11).DIRECTORY_SEPARATOR.$id, 0, -2). $idup;
    } while (file_exists($fileup));
    var_dump($file);
    var_dump($fileup);
    file_put_contents($fileup.'.mp3',    file_get_contents($fileup.'.mp3') .    file_get_contents($file.'.mp3'));
    file_put_contents($fileup.'.ogg',    file_get_contents($fileup.'.ogg') .    file_get_contents($file.'.ogg'));

    unlink($file.'.mp3');
    unlink($file.'.ogg');
    return false;
    return new ViewModel();
}


}

