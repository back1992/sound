<?php
namespace System\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;

class DbAdapterForm extends Form
{
    protected $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->setDbAdapter($dbAdapter);

        parent::__construct('db-adapter-form');

        $this->add(array(
            'name'    => 'db-select',
            'type'    => 'Zend\Form\Element\Select',
            'options' => array(
                'label'         => 'Dynamic DbAdapter Select',
                'value_options' => $this->getOptionsForSelect(),
                'empty_option'  => '--- please choose ---'
            )
        ));
    }

    // more later...

    // Also: create SETTER and GETTER for $dbAdapter!
        public function getOptionsForSelect()
    {
        $dbAdapter = $this->getDbAdapter();
        $sql       = 'SELECT t0.id, t0.title FROM selectoptions t0 ORDER BY t0.title ASC';
        $statement = $dbAdapter->query($sql);
        $result    = $statement->execute();

        $selectData = array();

        foreach ($result as $res) {
            $selectData[$res['id']] = $res['title'];
        }

        return $selectData;
    }
}