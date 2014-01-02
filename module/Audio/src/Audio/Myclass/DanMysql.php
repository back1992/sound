<?php
namespace Audio\Myclass;
// use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
class DanMysql {
	public function fetchQuizId($quiz) {
		$adapter = new \Zend\Db\Adapter\Adapter(array(
			'driver' => 'Mysqli',
			'database' => 'ticool',
			'username' => 'root',
			'password' => 'Joomla8'
			));
		// $quizId = $adapter->query('SELECT `QuizId` FROM `jos_ariquiz`  WHERE `QuizName` = ?', array($quiz));

		$sql = new Sql($adapter);
		$select = $sql->select();
		$select->from('jos_ariquiz');
		$select->where(array('QuizName' => $quiz));

		$statement = $sql->prepareStatementForSqlObject($select);
		$quizId = $statement->execute();
		return $quizId;
	}
}