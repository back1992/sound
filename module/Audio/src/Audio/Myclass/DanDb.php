<?php
namespace Audio\Myclass;
use Audio\Myclass\DBConnection;
class DanDb {

	public function insertFile($file, $data)
	{
		$mongo = DBConnection::instantiate();
		//get a MongoGridFS instance
		$gridFS = $mongo->database->getGridFS();
		$gridFS->storeFile($file, $data);
		return true;
	}
	function fetchAudio($audioFile) {
		$mongo = DBConnection::instantiate();
		// $dbname = DBConnection::DBNAME;
		$gridFS = $mongo->database->getGridFS();
		$object = $gridFS->findOne(array(
			'audioname' => basename($audioFile)
			));
		$audiofiledir = Dandan::RAWDIR;
		if(!file_exists($audiofiledir)) mkdir($audiofiledir);
		// $audiofiledir = './public/audiodata/';
		$audioname = $object->file['audioname'];
		$object->write($audiofiledir . $audioname);
		return true;
	}
}