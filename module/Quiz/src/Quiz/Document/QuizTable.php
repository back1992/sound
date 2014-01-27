<?php
namespace Quiz\Model;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class QuizTable
{
	protected $tableGateway;
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}
	public function fetchAll($paginated=false)
	{
		if($paginated) {
// create a new Select object for the table album
			$select = new Select('album');
// create a new result set based on the Quiz entity
			$resultSetPrototype = new ResultSet();
			$resultSetPrototype->setArrayObjectPrototype(new Quiz());
// create a new pagination adapter object
			$paginatorAdapter = new DbSelect(
// our configured select object
				$select,
// the adapter to run it against
				$this->tableGateway->getAdapter(),
// the result set to hydrate
				$resultSetPrototype
				);
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}
		$resultSet = $this->tableGateway->select();
		return $resultSet;

	}
	public function getQuiz($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	public function saveQuiz(Quiz $album)
	{
		$data = array(
			'artist' => $album->artist,
			'title' => $album->title,
			);
		$id = (int)$album->id;
		if ($id == 0) {
			$this->tableGateway->insert($data);
		} else {
			if ($this->getQuiz($id)) {
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
	public function deleteQuiz($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}
}
