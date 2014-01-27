<?php
namespace Snoopy\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Snoopy\Myclass\DanSnoopy;
use Snoopy\Myclass\DanFun;
use Snoopy\Myclass\Snoopy;
use Snoopy\Myclass\DBConnection;
use Snoopy\Form\LinksForm;
use Snoopy\Form\UploadForm;
use Snoopy\Form\MultiUploadForm;
use Zend\Session\Config\StandardConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;
// Create a new 'ftp' URI based on a custom class
use Zend\Uri\UriFactory;
class SnoopyController extends AbstractActionController
{
	public function indexAction()
	{
		$session = new Container('MyContainer');
			// $session->host = $host;
			// $session->pages = $pages;
		// var_dump($session->host);
		// var_dump($session->pages);
		// var_dump($session->scheme);
		$urls = array();
		$links = array();
		$start = '';
		$end = '';
		foreach ($session->pages as $page) {
				# code...
			$urls[] = $session->host.DIRECTORY_SEPARATOR.$page;
			$link = $session->scheme.'://'.$session->host.DIRECTORY_SEPARATOR.$page;
			$links[] = DanSnoopy::links($link);
		}
		// var_dump($urls);
		// var_dump($links);
		// return FALSE;
		$links = DanFun::flatten_array($links);
		$session->links = $links;
		return array(
			'links' => $links,
			);
	}
	public function displaylinksAction()
	{
		$session = new Container('MyContainer');
		$links = $session->links ;
		return array(
			'links' => $links,
			);
	}
	public function fetchcontentAction(){
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$session = new Container('MyContainer');
		$links = $session->links;
		$start = 'Part III Listening Comprehension';
		$end = '<!--box-right 完-->';
		$content = DanSnoopy::content($links[$id], $start, $end);
		$matchs = DanSnoopy::fetchQuestion($content);
		// var_dump($matchs);
		foreach ($matchs as $no => $match) {
			# code...
			$questionsArr = DanSnoopy::saveQuestion($no, $match);
		}
		// DanSnoopy::savequestion($content);
		// echo $content;
		// var_dump($content);
		return false;
	}
	public function fetchlinksAction(){
		$form = new LinksForm();
		$form->get('submit')->setValue('Add');
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			$postdata = $request->getPost();
			$url = $postdata->url;
			$start = $postdata->start;
			$end = $postdata->end;
			$content = DanSnoopy::content($url, $start, $end);
			$pages= DanSnoopy::pageCount($content);
			$uri = \Zend\Uri\UriFactory::factory($url);
			var_dump($uri);
			$host = $uri->getHost();
			$scheme = $uri->getScheme();
			var_dump($host);
			array_push($pages, substr($uri->getPath(), 1));
			$session = new Container('MyContainer');
			$session->host = $host;
			$session->pages = $pages;
			$session->scheme = $scheme;
			var_dump($session->host);
			// return false;
			return $this->redirect()->toRoute('snoopy');
		}
		return array('form' => $form);
	}
	public function fetchaudioAction(){
		$form = new LinksForm();
		$form->get('submit')->setValue('Add');
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			$postdata = $request->getPost();
			$url = $postdata->url;
			$start = $postdata->start;
			$end = $postdata->end;
			$content = DanSnoopy::content($url, $start, $end);
			$pages= DanSnoopy::pageCount($content);
			$uri = \Zend\Uri\UriFactory::factory($url);
			var_dump($uri);
			$host = $uri->getHost();
			$scheme = $uri->getScheme();
			var_dump($host);
			array_push($pages, substr($uri->getPath(), 1));
			$session = new Container('MyContainer');
			$session->host = $host;
			$session->pages = $pages;
			$session->scheme = $scheme;
			var_dump($session->host);
			// return false;
			return $this->redirect()->toRoute('snoopy');
		}
		return array('form' => $form);
	}
	
	public function uploadAction(){
		$flag = 0;
		$mongo = DBConnection::instantiate();
		//get a MongoGridFS instance
		$gridFS = $mongo->database->getGridFS();
		$form = new UploadForm('upload-form');
		$multiForm = new MultiUploadForm('multi-form');
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
			'multiForm' => $multiForm,
			'flag' => $flag,
			);
	}
	public function multiuploadAction(){
		$flag = 0;
		$mongo = DBConnection::instantiate();
		//get a MongoGridFS instance
		$gridFS = $mongo->database->getGridFS();
		$form = new MultiUploadForm('multi-form');
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
	public function fetchquizidAction(){
		// $url = "http://www.cet4v.com/exam/10975.asp";
		$res = DanMysql::fetchQuizId('cet4_200101');
		var_dump($res);
		return false;
	}
	public function fetchlistenAction(){
		$url = "http://www.cet4v.com/exam/10975.asp";
		$content = DanSnoopy::fetchtext($url);
		// print $content;
		$begin = 'Listening Comprehension' ;
		$end = '答案：'; 
		$res = DanAudio::slice($content, $begin, $end);
		$listen = Dandan::readquestion($res);
		// var_dump($listen);
		// print $res;
		return array('listen' => $listen);
	}
	public function curlAction(){
		$ch = curl_init();
		// $url = "http://www.cet4v.com/exam/10975.asp";
		$url = "http://www.tingclass.net/show-5406-3632-1.html";
		curl_setopt_array(
			$ch, array( 
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true
				));
		$output = curl_exec($ch);
		echo $output;
		return false;
		return array('listen' => $listen);
	}
	public function addquizsAction() {
        //set the $url and $refurl
		$url = "http://localhost/ticool/administrator/index.php?option=com_ariquizlite&task=quiz_add";
		$refurl = "http://localhost/ticool/administrator/index.php";
//        $url = "http://highschool3.local/administrator/index.php?option=com_ariquizlite&task=question_add&quizId=1";
//        $refurl = "	http://highschool3.local/administrator/index.php";
		DanCurl::addquizs($url, $refurl);
		return False;
	}
	public function addquestionsAction() {
		$quiz = $this->getEvent()->getRouteMatch()->getParam('id');
        //set the $url and $refurl
		$url = "http://localhost/ticool/administrator/index.php?option=com_ariquizlite&task=question_add";
		$refurl = "http://localhost/ticool/administrator/index.php";
		DanCurl::newcurl3($url, $refurl, $quiz);
		return False;
	}
	public function deletelinkAction(){
		$id = $this->getEvent()->getRouteMatch()->getParam('id');
		$session = new Container('MyContainer');
		$links = $session->links;
		unset($links[$id]);
		sort($links);
		$session->links = $links;
		var_dump($session->links);
		return FALSE;
	}
}
