<?php

namespace Ffmpeg\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ffmpeg\Form\DirForm;
use Zend\Stdlib\Hydrator;
class FfmpegController extends AbstractActionController
{
	public function indexAction()
	{
		$config = $this->getServiceLocator()->get('Config');
		$form = new DirForm();
		$dir = $config['settings']['audiodata'];
		if (file_exists($dir)) {
			$files = $this->dirToArray($dir);
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
					$returnValue = $forwardPlugin->dispatch('Audio\Controller\Audio', array(
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
		$config = $this->getServiceLocator()->get('Config');
		$dir = $config['settings']['audiodata'];
		$form = new DirForm();
		$files = $audioFile = '';
		if (file_exists($dir)) {
			$files = $this->dirToArray($dir);
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
		$config = $this->getServiceLocator()->get('Config');
		$dir = $config['settings']['audiodata'];
		// system('shell_exec('ffmpeg -i ./data/mp3/'. $audiofile . ' '.$path_parts['filename'].'.ogg');');
		// var_dump($this->params);
		$request = $this->getRequest();
		if ($request->isPost()) {
			// var_dump($request->getPost());
			$sDir = $dir . $request->getPost()->title;
			if (is_dir($sDir)) {
				$sFiles = $this->dirToArray($sDir, true);
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
	public function spltAction()
	{
		// Consumes the configuration array
		// $reader = new \Zend\Config\Reader\Json();
		// $data   = $reader->fromFile('./config/autoload/config.json');
		$form = new DirForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
			// var_dump($request->getPost());
			$audioFile = $request->getPost()->audiofiledir . $request->getPost()->audioname;
			// $fileInfo = pathinfo($audioFile);
			$audioSDir = $request->getPost()->audiofiledir;
			$audioTDir = $audioSDir . '../' . basename($audioFile, '.mp3') . '/';
			// var_dump($audioFile);
			// var_dump($audioSDir);
			// var_dump($audioTDir);
			$cmd_splt = " rm -rf $audioTDir* && mp3splt -s  $audioFile  -d $audioTDir  && cp ./mp3splt.log  $audioTDir";
			// var_dump($cmd_splt);
			$spltlog = shell_exec($cmd_splt);
			// var_dump($spltlog);
			if (file_exists($audioTDir)) {
				$files = $this->dirToArray($audioTDir);
				krsort($files);
			}
			$resFile = str_replace('.mp3', '', str_replace('./public', '', $audioFile));
			
			return array(
				'files' => $files,
				'audioFile' => $resFile,
				'dir' => $audioTDir,
				'form' => $form
			);
		}
		
		return false;
	}
	public function spltdirAction()
	{
		// Consumes the configuration array
		$reader = new \Zend\Config\Reader\Json();
		$data = $reader->fromFile('./data/config.json');
		// $dir = './public/';
		$form = new DirForm();
		$request = $this->getRequest();
		if ($request->isPost()) {
			var_dump($request->getPost());
			$audioFile = $request->getPost()->title;
			$fileInfo = pathinfo($audioFile);
			$audioSDir = $fileInfo['dirname'];
			$audioTDir = $audioSDir . '/../' . $fileInfo['filename'] . '/';
			var_dump($audioFile);
			var_dump($fileInfo);
			var_dump($audioSDir);
			var_dump($audioTDir);
			$cmd_splt = " rm -rf $audioTDir* && mp3splt -s  $audioFile  -d $audioTDir  && cp ./mp3splt.log  $audioTDir";
			var_dump($cmd_splt);
			// $spltlog = shell_exec($cmd_splt);
			// var_dump($spltlog);
			if (file_exists($audioTDir)) {
				$files = $this->dirToArray($audioTDir);
				krsort($files);
			}
			$resFile = str_replace('.mp3', '', str_replace('./public', '', $audioFile));
			
			return array(
				'files' => $files,
				'audioFile' => $resFile,
				'dir' => $audioTDir,
				'form' => $form
			);
		}
		
		return false;
	}
	function rangeAction()
	{
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
	function dirToArray($dir, $path = false)
	{
		$result = array();
		$cdir = scandir($dir);
		
		foreach ($cdir as $key => $value) {
			if (!in_array($value, array(
				".",
				".."
			))) {
				if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
					$result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
				}
				else {
					// (!$path) ? $result[] =  $value : $result[] = $dir . DIRECTORY_SEPARATOR . $value;
					$result[] = (!$path) ? $value : $dir . DIRECTORY_SEPARATOR . $value;
				}
			}
		}
		
		return $result;
	}
}
