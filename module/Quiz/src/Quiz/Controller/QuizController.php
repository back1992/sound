<?php

namespace Quiz\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Document\ViewDocument;
use Quiz\Document\Quiz;
// <-- Add this import
use Quiz\Form\QuizForm;
// <-- Add this import
use Quiz\MyClass\DBConnection;
use Application\Document\Link;
use Application\Document\Question;
use Snoopy\Form\LinksForm;
use Snoopy\Myclass\DanSnoopy;
use Snoopy\Myclass\DanFun;
use Zend\Session\Container;

use Zend\Stdlib\Hydrator\ObjectProperty;
use PhlyMongo\HydratingMongoCursor;
use PhlyMongo\HydratingPaginatorAdapter as MongoPaginatorAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;


class QuizController extends AbstractActionController
{
	protected $quizDoc;	

	public function indexAction()
	{
/*		$dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');  
		$cursor = $dm->createQueryBuilder('Application\Document\Link')
		->hydrate(false)
		->getQuery()
		->execute();*/

			$mongo = DBConnection::instantiate();
		$collection = $mongo->getCollection('link');

		// var_dump($query->current());
		$cursor = $collection->find();
		// var_dump($cursor);
		$adapter   = new MongoPaginatorAdapter(new HydratingMongoCursor(
			$cursor,
			new ObjectProperty,
			new Link
			));
		$paginator = new Paginator($adapter);
		$paginator->setCurrentPageNumber(5);
		$paginator->setItemCountPerPage(10);

		// foreach ($paginator as $item) {
/*		foreach ($cursor as $item) {
    // only receiving up to 10 items, starting at offset 50
			// printf('%s <%s>: %s', $status->name, $status->email, $status->status);
			var_dump($item);
		}
		return false;*/
		return new ViewModel(array(
			'paginator' => $paginator
			));


	}
	public function index2Action()
	{
		$dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');  
		$qb = $dm->createQueryBuilder('Application\Document\Link')
		->eagerCursor(true);
		$query = $qb->getQuery();
		$cursor = $query->execute();

		// return false;
		return array(
			'cursor' => $cursor
			);


	}
	public function linkAction()
	{
		// grab the paginator from the QuizTable
		$dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');  
		$link = new Link();
		$link->setTitle("cet4");
		$link->setUrl("https://www.google.com.hk/search?q=zend2+odm&newwindow=1&safe=active&ei=HdHZUu7lAqe0iQfY0oCYBQ&start=10&sa=N&biw=1366&bih=619");
		$dm->persist($link);
		$dm->flush();
		return false;
	}
	public function listlinkAction()
	{
		// grab the paginator from the QuizTable
		$dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');  
/*		$qb = $dm->createQueryBuilder('Link');
		$query = $qb->getQuery();
		$links = $query->execute();*/
		// var_dump($dm);
		$links = $dm->find('Application\Document\Link', "52da36f67f8b9a86258b4567");
		var_dump($links);
		// $user = $dm->getRepository('Application\Document\User')->findOneBy(array('username' => 'Gembul'));
		// var_dump($dm->getRepository('Application\Document\User')->find(array('username' => 'Gembul')));
		// var_dump($user);
		$qb = $dm->createQueryBuilder();

// ...

		$qb = $dm->createQueryBuilder('Application\Document\Link')
		->eagerCursor(true);
		$query = $qb->getQuery();
		$cursor = $query->execute();
foreach ($cursor as $link) { // queries for all users and data is held internally
    // each User object is hydrated from the data one at a time.
	var_dump($link);
}
return false;
}
public function addAction()
{
	$form = new QuizForm();
	$form->get('submit')->setValue('Add');
	$request = $this->getRequest();
	if ($request->isPost()) {
		$quiz = new Link();
		$form->setInputFilter($quiz->getInputFilter());
		$form->setData($request->getPost());
		if ($form->isValid()) {
			var_dump($form->getData());
// 			$quiz->exchangeArray($form->getData());
// 			$this->getQuizTable()->saveQuiz($quiz);
// // Redirect to list of quizs
			$data = $form->getData();
			var_dump($data);
			$dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');  
			$link = new Link();
			$link->setTitle($data['title']);
			$link->setUrl($data['url']);
			$dm->persist($link);
			$dm->flush();
			return $this->redirect()->toRoute('quiz');
		}
	}
	return array('form' => $form);

}
public function editAction()
{
	$session = new Container('MyContainer');
	$id = $this->params()->fromRoute('id', 0);
	if (!$id) {
		return $this->redirect()->toRoute('quiz', array(
			'action' => 'add'
			));
	}
// Get the Quiz with the specified id.
// if it cannot be found, in which case go to the index page.
	$dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');  
	// var_dump($id);
	$link = $dm->getRepository('Application\Document\Link')->find($id);
	// var_dump($link);
	$url = $link->getUrl();
	$form = new LinksForm();
	$start = '';
	$end = '';
	$content = DanSnoopy::content($url, $start, $end);
	$pages= DanSnoopy::pageCount($content);
	$uri = \Zend\Uri\UriFactory::factory($url);
	// var_dump($uri);
	$host = $uri->getHost();
	$scheme = $uri->getScheme();
	// var_dump($host);
	array_push($pages, substr($uri->getPath(), 1));
	foreach ($pages as $page) {
				# code...
		$urls[] = $host.DIRECTORY_SEPARATOR.$page;
		$link = $scheme.'://'.$host.DIRECTORY_SEPARATOR.$page;
		$links[] = DanSnoopy::links($link);
	}
	$session->links = $links = DanFun::flatten_array($links);
	return array(
		'links' => $links,
		'form' => $form,
		);

}
public function deleteAction()
{
	$id = $this->params()->fromRoute('id', 0);
	if (!$id) {
		return $this->redirect()->toRoute('quiz');
	}
	$dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');  
	$link = $dm->getRepository('Application\Document\Link')->find($id);
	$qb = $dm->createQueryBuilder('Application\Document\Link')->field('id')->equals($id)->eagerCursor(true);
	$query = $qb->getQuery();
	$cursor = $query->execute();
	$request = $this->getRequest();
	if ($request->isPost()) {
		$del = $request->getPost('del', 'No');
		if ($del == 'Yes') {
			// $id = $request->getPost('id');
			$dm->remove($link);
			$dm->flush();
		}
// Redirect to list of quizs
		return $this->redirect()->toRoute('quiz');
	}
	// var_dump($id);
	foreach ($cursor as $value) {
		# code...
		// var_dump($value);
		$quiz = $value;
	}
	return array(
		'id'
		=> $id,
		'quiz' => $quiz
		);

}
public function fetchcontentAction(){
	$dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');  
	
	$id = $this->getEvent()->getRouteMatch()->getParam('id');
	$session = new Container('MyContainer');
	$links = $session->links;
	$start = 'Part III Listening Comprehension';
	$end = '<!--box-right 完-->';
	$content = DanSnoopy::content($links[$id], $start, $end);
	$matchs = DanSnoopy::fetchQuestion($content['content']);
		// var_dump($matchs);
	foreach ($matchs as $no => $match) {
			# code...
		$questionsArr = DanSnoopy::saveQuestion($match);
		$question = new Question();
		$question->setNo($no);
		$question->setTitle($content['title']);
		$question->setA($questionsArr['A']);
		$question->setB($questionsArr['B']);
		$question->setC($questionsArr['C']);
		$question->setD($questionsArr['D']);
		$question->setANS($questionsArr['ANS']);
		$dm->persist($question);
		$dm->flush();
	}
		// DanSnoopy::savequestion($content);
		// echo $content;
		// var_dump($content);
	return false;
}
	// module/Quiz/src/Quiz/Controller/QuizController.php:
public function getQuizDoc()
{
	if (!$this->quizDoc) {
		$dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');  
		
		$this->quizDoc = $dm->createQueryBuilder('Application\Document\Link')
		->eagerCursor(true);;
	}
	return $this->quizDoc;
}

}
