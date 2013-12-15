<?php
namespace System\Form;


use Zend\Form\Form;
use System\Model\SelectTable;

class TableForm extends Form
{
    protected $selectTable;

    public function __construct(SelectTabSystemle $selectTable)
    {
        $this->setSelectTable($selectTable);

        parent::__construct('db-adapter-form');

        $this->add(array(
            'name'    => 'db-select',
            'type'    => 'Zend\Form\Element\Select',
            'options' => array(
                'label'         => 'Dynamic TableGateway Select',
                'value_options' => $this->getOptionsForSelect(),
                'empty_option'  => '--- please choose ---'
            )
        ));
    }

    // more soon...

    // Getter and Setter for $selectTable here
}