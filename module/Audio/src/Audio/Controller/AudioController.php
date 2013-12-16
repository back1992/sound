<?php

namespace Audio\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MongoId;
use Audio\Myclass\DBConnection;
use Audio\Form\AudioForm;
use Audio\Form\UploadForm;
use Audio\Form\UpdateForm;
use Audio\Form\ViewAudioForm;
use Ffmpeg\Form\DirForm;
use Audio\Myclass\Dandan;
class AudioController extends AbstractActionController
{
	public function indexAction()
	{
		
		return new ViewModel();
	}
	public function uploadAction()
	{
		ini_set('display_errors', '1');
		$flag = 0;
		$mongo = DBConnection::instantiate();
		//get a MongoGridFS instance
		$gridFS = $mongo->database->getGridFS();
		$form = new UploadForm('upload-form');
		$form->setValidationGroup('title', 'monthyear', 'tag', 'audio-file');
		$request = $this->getRequest();
		if ($request->isPost()) {
			// Make certain to merge the files info!
			$post = array_merge_recursive($request->getPost()->toArray() , $request->getFiles()->toArray());
			$form->setData($post);
			// var_dump($post);
			if ($form->isValid()) {
				$data = $form->getData();
				// var_dump($data);
				$filetype = $data['audio-file']['type'];
				$title = $data['title'];
				$monthyear = $post['monthyear'];
				$tmpfilepath = $data['audio-file']['tmp_name'];
				$tag = $data['tag'];
				$filename = $data['audio-file']['name'];
				$mp3file = $tmpfilepath;
				$gridFS->storeFile($mp3file, array(
					'audioname' => $filename,
					'filetype' => $filetype,
					'state' => $post['state'],
					'city' => $post['city'],
					'tag' => $tag,
					'title' => $title,
					'monthyear' => $monthyear,
				));
				$flag = 1;
				
				return $this->redirect()->toRoute('audio', array(
					'action' => 'audioindb'
				));
			}
		}
		
		return array(
			'form' => $form,
			'flag' => $flag,
		);
	}
	public function updateAction()
	{
		ini_set('display_errors', '1');
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$mongo = DBConnection::instantiate();
		$gridFS = $mongo->database->getGridFS();
		$mp3 = $gridFS->findOne(array(
			'_id' => new MongoId($id)
		));
		$form = new UploadForm();
		$form->setData($mp3->file);
		$request = $this->getRequest();
		if ($request->isPost()) {
			// Make certain to merge the files info!
			$post = $request->getPost();
			$form->setData($post);
			// var_dump($data);
			// $filetype = $data['audio-file']['type'];
			$title = $post['title'];
			$audioname = $post['audioname'];
			$tag = $post['tag'];
			// $filename = $data['audio-file']['name'];
			// $mp3file = $tmpfilepath;
			// $gridFS->update(array("_id" => $id), array(
			$gridFS->update(array(
				'_id' => new MongoId($id)
			) , array(
				'$set' => array(
					'title' => $title,
					'audioname' => $audioname,
					'tag' => $tag,
				)
			));
			$flag = 1;
			
			return $this->redirect()->toRoute('audio', array(
				'action' => 'audioindb'
			));
		}
		
		return array(
			'id' => $id,
			'form' => $form
		);
	}
	public function scanAction()
	{
		$dir = Dandan::SDIR;
		$request = $this->getRequest();
		if ($request->isPost()) {
			$data = $request->getPost();
			$audioDir = $dir . $data['title'] . '/';
			// var_dump($audioDir);
			$audioArray = scandir($audioDir);
			array_splice($audioArray, 0, 3);
			// var_dump($audioArray);
			
		}
		
		foreach ($audioArray as $audioFile) {
			$tempArr[] = str_replace('./public/', '/', $audioDir) . substr($audioFile, 0, -4);
		}
		$audioRes = array_unique($tempArr);
		// var_dump($tempArr);
		sort($audioRes);
		// var_dump($audioRes);
		if (count($tempArr) == 2 * count($audioRes)) {
			
			return array(
				'audioRes' => $audioRes,
			);
		}
		echo 'something wrong';
		
		return false;
	}
	public function scandirAction()
	{
		$view = new ViewModel();
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
	
	public function audioindbAction()
	{
		ini_set('display_errors', '1');
		$mongo = DBConnection::instantiate();
		$gridFS = $mongo->database->getGridFS();
		$objects = $gridFS->find();
		
		return array(
			'objects' => $objects,
			'flashMessages' => $this->flashMessenger()->getMessages()
		);
	}
	public function fetchaudioAction()
	{
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		if (!$id) {
			$this->flashMessenger()->addMessage('You havn\'t select audio file in database .');
			
			return $this->redirect()->toRoute('audio', array(
				'action' => 'audioindb'
			));
		}
		$mongo = DBConnection::instantiate();
		$dbname = DBConnection::DBNAME;
		$gridFS = $mongo->database->getGridFS();
		$object = $gridFS->findOne(array(
			'_id' => new MongoId($id)
		));
		$form = new uploadForm();
		// var_dump($object);
		$form->bind($object);
		$form->setData($object->file);
		$audiofiledir = Dandan::RAWDIR;
		if(!file_exists($audiofiledir)) mkdir($audiofiledir);
		// $audiofiledir = './public/audiodata/';
		$audioname = $object->file['audioname'];
		$object->write($audiofiledir . $audioname);
		$files = Dandan::dirToArray($audiofiledir);
		krsort($files);
		$form->setData(array(
			'audiofiledir' => $audiofiledir
		));
		// write to raw directory
		$audioname = $object->file['audioname'];
		$object->write($audiofiledir . $audioname);
		$this->flashMessenger()->addMessage("You have fetch audio file  $audioname into  <li class='pft-directory'>$audiofiledir</li>");
		//submit Splt
		$request = $this->getRequest();
		if ($request->isPost()) {
			$forwardPlugin = $this->forward();
			$returnValue = $forwardPlugin->dispatch('Audio\Controller\Audio', array(
				'action' => 'splt'
			));
			
			return $returnValue;
		}
		
		return array(
			'files' => $files,
			'dir' => $audiofiledir,
			'form' => $form,
			'flashMessages' => $this->flashMessenger()->getMessages()
		);
	}
	function spltAction()
	{
		$request = $this->getRequest();
		if ($request->isPost()) {
			// var_dump($request->getPost());
			// $fileInfo = pathinfo($audioFile);
			$title = $request->getPost()->audioname;
			$audioFile = Dandan::RAWDIR.$title;
			// $audioTDir = Dandan::SDIR  . basename($audioFile, '.mp3') . DIRECTORY_SEPARATOR;
			$audioTDir = Dandan::RESDIR  .  pathinfo($title, PATHINFO_FILENAME) . DIRECTORY_SEPARATOR;
			var_dump($audioFile);
			var_dump($audioTDir);
			// var_dump(Dandan::deleteDirectory($audioTDir));
			// var_dump(chmod($audioTDir, '0777'));
			// var_dump(Dandan::rrmdir($audioTDir));
			if(file_exists($audioTDir)) Dandan::removeDir($audioTDir);	
			if(!file_exists(Dandan::RESDIR)) mkdir(Dandan::RESDIR);
			$resmp = Dandan::mp3splt($audioFile, $audioTDir);	
			var_dump($resmp);
			if (file_exists($audioTDir)) {
				$files = Dandan::dirToArray($audioTDir);
				krsort($files);
				$resFile = str_replace('.mp3', '', str_replace('./public', '', $audioFile));
				// return false;
				return array(
					'files' => $files,
					'audioFile' => $resFile,
					'dir' => $audioTDir,
					);
			}
		}
		
		return false;
	}
	public function editAction()
	{
	}
	public function deleteAction()
	{
		ini_set('display_errors', '1');
		$mongo = DBConnection::instantiate();
		$gridFS = $mongo->database->getGridFS();
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		if ($id == 'all') {
			$gridFS->remove();
		}
		else {
			$gridFS->remove(array(
				'_id' => new MongoId($id)
			));
		}
		$this->redirect()->toRoute('audio', array(
			'action' => 'audioindb'
		));
	}
	public function viewAction()
	{
		define("DETAIL", 5);
		define("DEFAULT_WIDTH", 500);
		define("DEFAULT_HEIGHT", 100);
		define("DEFAULT_FOREGROUND", "#FF0000");
		define("DEFAULT_BACKGROUND", "#FFFFFF");
		// get user vars from form
		$width = DEFAULT_WIDTH;
		$height = DEFAULT_HEIGHT;
		$foreground = DEFAULT_FOREGROUND;
		$background = DEFAULT_BACKGROUND;
		$draw_flat = true;
		$img = false;
		list($r, $g, $b) = $this->html2rgb($foreground);
		$filename = './2632831d77.wav';
		$handle = fopen($filename, "r");
		// wav file header retrieval
		$heading[] = fread($handle, 4);
		$heading[] = bin2hex(fread($handle, 4));
		$heading[] = fread($handle, 4);
		$heading[] = fread($handle, 4);
		$heading[] = bin2hex(fread($handle, 4));
		$heading[] = bin2hex(fread($handle, 2));
		$heading[] = bin2hex(fread($handle, 2));
		$heading[] = bin2hex(fread($handle, 4));
		$heading[] = bin2hex(fread($handle, 4));
		$heading[] = bin2hex(fread($handle, 2));
		$heading[] = bin2hex(fread($handle, 2));
		$heading[] = fread($handle, 4);
		$heading[] = bin2hex(fread($handle, 4));
		// wav bitrate
		$peek = hexdec(substr($heading[10], 0, 2));
		$byte = $peek / 8;
		// checking whether a mono or stereo wav
		$channel = hexdec(substr($heading[6], 0, 2));
		$ratio = ($channel == 2 ? 40 : 80);
		$data_size = floor((filesize($filename) - 44) / ($ratio + $byte) + 1);
		$data_point = 0;
		$img = imagecreatetruecolor($data_size / DETAIL, $height);
		imagesavealpha($img, true);
		$transparentColor = imagecolorallocatealpha($img, 0, 0, 0, 127);
		imagefill($img, 0, 0, $transparentColor);
		
		while (!feof($handle) && $data_point < $data_size) {
			if ($data_point++ % DETAIL == 0) {
				$bytes = array();
				// get number of bytes depending on bitrate
				
				for ($i = 0; $i < $byte; $i++) $bytes[$i] = fgetc($handle);
				
				switch ($byte) {
						// get value for 8-bit wav
						
						
					case 1:
						$data = findValues($bytes[0], $bytes[1]);
						
						break;
						// get value for 16-bit wav
						
						
					case 2:
						if (ord($bytes[1]) & 128) $temp = 0;
						else $temp = 128;
						$temp = chr((ord($bytes[1]) & 127) + $temp);
						$data = floor($this->findValues($bytes[0], $temp) / 256);
						
						break;
					}
					// skip bytes for memory optimization
					fseek($handle, $ratio, SEEK_CUR);
					// draw this data point
					// relative value based on height of image being generated
					// data values can range between 0 and 255
					$v = (int)($data / 255 * $height);
					// don't print flat values on the canvas if not necessary
					if (!($v / $height == 0.5 && !$draw_flat))
					// draw the line on the image using the $v value and centering it vertically on the canvas
					imageline($img,
					// x1
					(int)($data_point / DETAIL) ,
					// y1: height of the image minus $v as a percentage of the height for the wave amplitude
					$height - $v,
					// x2
					(int)($data_point / DETAIL) ,
					// y2: same as y1, but from the bottom of the image
					$height - ($height - $v) , imagecolorallocate($img, $r, $g, $b));
				}
				else {
					// skip this one due to lack of detail
					fseek($handle, $ratio + $byte, SEEK_CUR);
				}
			}
			// close and cleanup
			fclose($handle);
			// delete the processed wav file
			// unlink($filename);
			// header("Content-Type: image/png");
			var_dump($img);
			imagejpeg($img, 'simpletext.jpg');
			imagepng($img);
			imagedestroy($img);
			
			return false;
	}
	public function imageAction()
	{
		header('Content-Type: image/png');
		$im = @imagecreatetruecolor(120, 20) or die('Cannot Initialize new GD image stream');
		$text_color = imagecolorallocate($im, 233, 14, 91);
		imagestring($im, 1, 5, 5, 'A Simple Text String', $text_color);
		imagepng($im);
		imagedestroy($im);
	}
	public function imagealphaAction()
	{
		// 载入带 alpha 通道的 png 图像
		$png = imagecreatefrompng('./public/img/zf2-logo.png');
		// 做些必须的操作
		// 关闭 alpha 渲染并设置 alpha 标志
		imagealphablending($png, false);
		imagesavealpha($png, true);
		// 输出图像到浏览器
		header('Content-Type: image/png');
		imagepng($png);
		imagedestroy($png);
		
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
		
		return array(
			'logArray' => $logArray,
		);
	}
	public function insertdbAction()
	{
		ini_set('display_errors', '1');
		$mongo = DBConnection::instantiate();
		$collection = $mongo->getCollection('dandan');
		// Convert JSON to a PHP array
		$file = file_get_contents('./public/js/cityselect/city.min.js', FILE_USE_INCLUDE_PATH);
		var_dump($file);
		$citylist = json_decode($file);
		// Loop array and create seperate documents for each tweet
		
		foreach ($citylist as $id => $item) {
			$collection->insert($item);
		}
		// $collection->insert($myjson)
		
		return false;
	}
	function findValues($byte1, $byte2)
	{
		$byte1 = hexdec(bin2hex($byte1));
		$byte2 = hexdec(bin2hex($byte2));
		
		return ($byte1 + ($byte2 * 256));
	}
	/**
	 * Great function slightly modified as posted by Minux at
	 * http://forums.clantemplates.com/showthread.php?t=133805
	 */
	function html2rgb($input)
	{
		var_dump($input);
		$input = ($input[0] == "#") ? substr($input, 1, 6) : substr($input, 0, 6);
		
		return array(
			hexdec(substr($input, 0, 2)) ,
			hexdec(substr($input, 2, 2)) ,
			hexdec(substr($input, 4, 2))
		);
	}
}
