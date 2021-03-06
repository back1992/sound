<?php
namespace Application\Myclass;
use Mongo;
use MongoId;
use MongoDate;

class DBConnection
{
    const HOST   = 'localhost';
    // const HOST   = '127.0.0.1';
    const PORT   = 27017;
    const DBNAME = 'quiz';
    
    private static $instance;
    
    public $connection;    
    public $database;
    
    private function __construct()
    {
        $connectionString = sprintf('mongodb://%s:%d', DBConnection::HOST, DBConnection::PORT);

        try {
            
            $this->connection = new Mongo($connectionString);
            $this->database = $this->connection->selectDB(DBConnection::DBNAME);
        
        } catch (MongoConnectionException $e) {
            throw $e;
        }
    }
    
    static public function instantiate()
    {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        
        return self::$instance;
    }
    
    public function getCollection($name)
    {
        return $this->database->selectCollection($name);
    }
}