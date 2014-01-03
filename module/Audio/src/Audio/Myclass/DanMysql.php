<?php
namespace Audio\Myclass;
// use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
class DanMysql {
	public function fetchQuizId($quiz) {
		$adapter = new \Zend\Db\Adapter\Adapter(array(
			'driver' => 'Mysqli',
			'database' => 'ticool',
			'username' => 'root',
			'password' => 'Joomla8',
			'options' => array('buffer_results' => true)
			));

		$resultSet = $adapter->query('SELECT `QuizId` FROM `jos_ariquiz`   WHERE `QuizName` = ?', array($quiz));

		$rowData= $resultSet->current()->getArrayCopy();
		// var_dump($rowData);
		$quizId = $rowData['QuizId'];
		return $quizId;
	}
}