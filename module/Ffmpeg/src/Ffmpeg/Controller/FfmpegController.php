<?php
namespace Ffmpeg\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ffmpeg\Form\DirForm;
use Zend\Stdlib\Hydrator;
use Audio\Myclass\Dandan;
class FfmpegController extends AbstractActionController
{
	public function indexAction()
	{
		$dir = Dandan::SDIR;
		$form = new DirForm();
		if (file_exists($dir)) {
			$files = Dandan::dirToArray($dir);
			krsort($files);
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$forwardPlugin = $this->forward();
			
			switch ($request->getPost()->submit) {
				case 'Convert Mp3 to Ogg':
				$returnValue = $forwardPlugin->dispatch('Ffmpeg\Controller\Ffmpeg', array(
					'action' => 'convert'
					));
				break;
				case 'Split by Silence':
				$returnValue = $forwardPlugin->dispatch('Ffmpeg\Controller\Ffmpeg', array(
						// 'action' => 'splt'
					'action' => 'splt'
					));
				return $returnValue;
				case 'Edit the Audio':
				$returnValue = $forwardPlugin->dispatch('Ffmpeg\Controller\Ffmpeg', array(
					'action' => 'edit'
					));
				return $returnValue;
				break;
				case 'Fine Tune':
				$returnValue = $forwardPlugin->dispatch('Ffmpeg\Controller\Ffmpeg', array(
					'action' => 'fine'
					));
				return $returnValue;
				break;
				case 'Scan Dir':
				$returnValue = $forwardPlugin->dispatch('Ffmpeg\Controller\Ffmpeg', array(
					'action' => 'scan'
					));
				return $returnValue;
				break;
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
	public function editAction()
	{
		$dir = Dandan::SDIR;
		$form = new DirForm();
		$files = $audioFile = '';
		if (file_exists($dir)) {
			$files = Dandan::dirToArray($dir);
			krsort($files);
		}
		$request = $this->getRequest();
		if ($request->isPost()) {
			$audioFile = str_replace('./public/', '/', $dir . substr($request->getPost()->title, 0, -4));
			// $audioFile = '/audiodata/'.substr($request->getPost()->title, 0, -4);
			var_dump($audioFile);
			$data = array(
				'title' => $request->getPost()->title,
				);
			$form->setData($data);
		}
		// var_dump($form);
		// return false;
		
		return array(
			'files' => $files,
			'dir' => $dir,
			'audioFile' => $audioFile,
			'form' => $form
			);
	}
	public function convertAction()
	{
		ini_set('display_errors', '1');
		$dir = Dandan::SDIR;
		// system('shell_exec('ffmpeg -i ./data/mp3/'. $audiofile . ' '.$path_parts['filename'].'.ogg');');
		// var_dump($this->params);
		$request = $this->getRequest();
		if ($request->isPost()) {
			// var_dump($request->getPost());
			$sDir = $dir . $request->getPost()->title;
			if (is_dir($sDir)) {
				$sFiles = Dandan::dirToArray($sDir, true);
				$cmd_rm = 'rm ' . $sDir . '/*.ogg';
				shell_exec($cmd_rm);
			}
			else {
				$sFiles[] = $sDir;
			}
			// $cmd_conv = 'ffmpeg -i '. $sourceFile .'  -acodec libvorbis  '. $targetFile;
			// var_dump($cmd_conv);
			// shell_exec($cmd_conv);
			// var_dump($request->getPost());
			// var_dump($sDir);
			// var_dump($sFiles);
			
			foreach ($sFiles as $sFile) {
				$sFile_parts = pathinfo($sFile);
				$sourceFile = $sFile;
				$targetFile = $sFile_parts['dirname'] . '/' . $sFile_parts['filename'] . '.ogg';
				$cmd_conv = 'ffmpeg -i ' . $sourceFile . '  -acodec libvorbis  ' . $targetFile;
				// var_dump($cmd_conv);
				shell_exec($cmd_conv);
				// shell_exec('ffmpeg -i '. $sDir . '/'.$sFile .' '. $sDir. '/ogg/'.$sFile_parts['basename'].'.ogg' );
				
			}
		}
		
		return false;
	}
	public function fineAction()
	{
		$logfile = './mp3splt.log';
		$logArray = file($logfile);
		array_splice($logArray, 0, 2);
		
		for ($i = 0; $i < count($logArray); $i++) {
			$logArray[$i] = preg_split("/[\s,]+/", $logArray[$i]);
			array_splice($logArray[$i], 2, 2);
		}
		sort($logArray);
		$request = $this->getRequest();
		if ($request->isPost()) {
			$audioFile = str_replace('./public/', '/', substr($request->getPost()->title, 0, -4));
			var_dump($audioFile);
		}
		
		return array(
			'logArray' => $logArray,
			'audioFile' => $audioFile,
			);
	}
	function rangeAction()
	{
	}
	function spltAction()
	{
		$mp3dir = Dandan::MP3DIR;
		$form = new DirForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
			var_dump($request->getPost());
			// $fileInfo = pathinfo($audioFile);
			$title = $request->getPost()->title;
			$audioSDir = Dandan::RAWDIR;
			$audioFile = Dandan::SDIR.$title;
			// $audioTDir = Dandan::SDIR  . basename($audioFile, '.mp3') . DIRECTORY_SEPARATOR;
			$audioTDir = Dandan::SDIR  .  pathinfo($title, PATHINFO_FILENAME) . DIRECTORY_SEPARATOR;
			var_dump($audioFile);
			var_dump($audioSDir);
			var_dump($audioTDir);
			// var_dump(Dandan::deleteDirectory($audioTDir));
			// var_dump(chmod($audioTDir, '0777'));
			// var_dump(Dandan::rrmdir($audioTDir));
			if(file_exists($audioTDir)) Dandan::removeDir($audioTDir);	
			var_dump(mkdir($audioTDir));
			var_dump(chmod($audioTDir, 0777));
			// $cmd_splt = " rm -rf $audioTDir* && mp3splt -s -p min=4,off=0.6  $audioFile  -d $audioTDir  && cp ./mp3splt.log  $audioTDir";
			$cmd_splt = " mp3splt -s -p min=2.4,off=0.6  $audioFile  -d $audioTDir ";
			var_dump($cmd_splt);
			$spltlog = shell_exec($cmd_splt);
			// var_dump($spltlog);
			if (file_exists($audioTDir)) {
				$files = Dandan::dirToArray($audioTDir);
				krsort($files);
				$resFile = str_replace('.mp3', '', str_replace('./public', '', $audioFile));
				return array(
					'files' => $files,
					'audioFile' => $resFile,
					'dir' => $audioTDir,
					'form' => $form
					);
			}
		}
		
		return false;
	}
	public function readlogAction()
	{
		$logfile = './mp3splt.log';
		$logArray = file($logfile);
		array_splice($logArray, 0, 2);
		
		for ($i = 0; $i < count($logArray); $i++) {
			$logArray[$i] = preg_split("/[\s,]+/", $logArray[$i]);
			array_splice($logArray[$i], 2, 2);
		}
		sort($logArray);
		$module_conf = $this->getServiceLocator()->get('Config');
		$ffmpeg_conf = $module_conf['ffmpeg_config'];
		$config = new \PHPVideoToolkit\Config($ffmpeg_conf);
		$example_audio_path = './public/audiodata/raw/fengtai.mp3';
		// var_dump(is_writable($example_audio_path));
		// $audio = new \PHPVideoToolkit\Audio($example_audio_path, $config);
		// $process = $audio->getProcess();
    //  $process->setProcessTimelimit(1);
/*    $output = $audio->extractSegment(new \PHPVideoToolkit\Timecode(10), new \PHPVideoToolkit\Timecode(100))
    ->save('./public/output/fengtai3.mp3');
*/
    for ($i=0; $i < count($logArray) ; $i++) { 
    	$audio = new \PHPVideoToolkit\Audio($example_audio_path, $config);
    	$process = $audio->getProcess();
    	$output[$i] = $audio->extractSegment(new \PHPVideoToolkit\Timecode($logArray[$i]['0']), new \PHPVideoToolkit\Timecode($logArray[$i]['1']))
    	->save("./public/output/fengtai$i.ogg"); 
    	//    	$outputogg[$i] = $audio->extractSegment(new \PHPVideoToolkit\Timecode($logArray[$i]['0']), new \PHPVideoToolkit\Timecode($logArray[$i]['1']))
    	// ->save("./public/output/fengtai$i.ogg");
    }
    var_dump($logArray);
    return array(
    	'logArray' => $logArray,
    	);
}
public function scanAction()
{
	// $audioDir = './public/output/';
	$audioDir = Dandan::SDIR.'jiangxi/';
	// $audioDir = Dandan::SDIR.'fengtai/';
	// var_dump(chmod($audioDir, '0777'));
	$request = $this->getRequest();
	if ($request->isPost()) {
		$data = $request->getPost();
		$audioDir = Dandan::SDIR . $data['title'] . DIRECTORY_SEPARATOR;
			var_dump($audioDir);
	}
	$audioArray = Dandan::dirToArray($audioDir);

	$aDirSuffix = str_replace('./public', '', $audioDir);
	foreach ($audioArray as $audioFile) {
		# code...
		if(pathinfo($audioFile, PATHINFO_EXTENSION) == 'mp3'){
			$mp3Files[] = $aDirSuffix.$audioFile;
		} elseif (pathinfo($audioFile, PATHINFO_EXTENSION) == 'ogg'){
			$oggFiles[] = $aDirSuffix.$audioFile;
		} 
	}
	var_dump($mp3Files);
	var_dump($oggFiles);
	return array(
		'mp3Files' => $mp3Files,
		'oggFiles' => $oggFiles,
		);
}
public function viewAction()
{
		// get the article from the persistence layer, etc...
	$view = new ViewModel();
		// this is not needed since it matches "module/controller/action"
	$view->setTemplate('content/article/view');
	$article = 'var article';
	$articleView = new ViewModel(array(
		'article' => $article
		));
	$articleView->setTemplate('content/article');
	$primarySidebarView = new ViewModel();
	$primarySidebarView->setTemplate('content/main-sidebar');
	$secondarySidebarView = new ViewModel();
	$secondarySidebarView->setTemplate('content/secondary-sidebar');
	$sidebarBlockView = new ViewModel();
	$sidebarBlockView->setTemplate('content/block');
	$secondarySidebarView->addChild($sidebarBlockView, 'block');
	$view->addChild($articleView, 'article')->addChild($primarySidebarView, 'sidebar_primary')->addChild($secondarySidebarView, 'sidebar_secondary');
	return $view;
}
public function viewlistAction()
{
		// get the article from the persistence layer, etc...
	$view = new ViewModel();
		// this is not needed since it matches "module/controller/action"
		// $view->setTemplate('content/article/view');
	$article = 'var article';
	$articleView = new ViewModel(array(
		'article' => $article
		));
	$articleView->setTemplate('content/article');
	$primarySidebarView = new ViewModel();
	$primarySidebarView->setTemplate('content/main-sidebar');
	$config = $this->getServiceLocator()->get('Config');
	$dir = $config['settings']['audiodata'];
	if (file_exists($dir)) {
		$files = $this->dirToArray($dir);
		krsort($files);
	}
		// $filelistView = new ViewModel();
	$filelistView = new ViewModel(array(
		'files' => $files,
		'dir' => $dir
		));
	$filelistView->setTemplate('audio/filelist');
	$secondarySidebarView = new ViewModel();
	$secondarySidebarView->setTemplate('content/secondary-sidebar');
	$sidebarBlockView = new ViewModel();
	$sidebarBlockView->setTemplate('content/block');
	$secondarySidebarView->addChild($sidebarBlockView, 'block');
	$view->addChild($articleView, 'article')->addChild($primarySidebarView, 'sidebar_primary')->addChild($filelistView, 'filelist')->addChild($secondarySidebarView, 'sidebar_secondary');
	return $view;
}
}
