<?php
namespace System\Form;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Date;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
class HostsForm extends Form
{
    public function __construct
    ($name = null, $options = array())
    {
        parent::__construct('hosts-form');
        $this->
        setAttribute('method', 'post')
        ->setHydrator(new ClassMethodsHydrator(false))
        ->setInputFilter(new InputFilter());
        $this->setAttribute('role', 'form');
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Url in Wall ',
                ),
            'attributes' => array(
                'class' => 'form-control form-group',
                'id'=>"host-url" ,
                'style'=>"width: 500px" ,
                'placeholder'=> 'web url '
                ),
            ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Submit',
                'class' => 'btn btn-default',
                ),
            ));
    }
}