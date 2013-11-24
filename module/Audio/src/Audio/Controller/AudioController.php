<?php

namespace Audio\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Audio\Myclass\DBconnection;
use Audio\Form\UploadForm;

class AudioController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
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
			var_dump($post);
			if ($form->isValid()) {
				$data = $form->getData();
				var_dump($data);
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
				
				return $this->redirect()->toRoute('Audio', array(
					'action' => 'audioindb'
				));
			}
		}
		
		return array(
			'form' => $form,
			'flag' => $flag,
		);
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
		return false;
		$this->redirect()->toRoute('Audio', array(
			'action' => 'audioindb'
		));
	}
}

