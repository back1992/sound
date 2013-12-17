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
}