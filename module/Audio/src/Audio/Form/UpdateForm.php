<?php
namespace Audio\Form;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Date;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
class UpdateForm extends Form
{
    public function __construct
    ($name = null, $options = array())
    {
        parent::__construct('upload-form');
        $this->setHydrator(new ClassMethodsHydrator(false))
        ->setInputFilter(new InputFilter());
        $this->setAttribute('role','form');
        // $this->setAttribute('class','form-horizontal');
        $this->addElements();
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Quiz Title ',
                ),
            ));
        $this->add(array(
            'name' => 'audioname',
            'type' => 'Text',
            'options' => array(
                'label' => 'Audio Name ',
                ),
            ));
        $this->add(array(
           'type' => 'Zend\Form\Element\Select',
           'name' => 'state',
           'options' => array(
               'label' => 'State: ',
               'empty_option' => 'state',
               'value_options' => array(
                   ),
               ),
           ));
        $this->add(array(
           'type' => 'Zend\Form\Element\Select',
           'name' => 'city',
           'options' => array(
               'label' => 'City',
               'empty_option' => 'City',
               'value_options' => array(
                   ),
               ),
           ));
        $this->add(array(
            'name' => 'tag',
            'type' => 'Text',
            'options' => array(
                'label' => 'Tag ',
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
        $this->add(array(
            'type' => 'Zend\Form\Element\MonthSelect',
            'name' => 'monthyear',
            'options' => array(
                'label' => 'Select a month and a year',
                'min_year' => 1986,
                ),
            ));
        $this->add(array(
            'name' => 'audiofilepath',
            'type' => 'Text',
            'options' => array(
                'label' => 'Audiofilepath ',
                ),
            ));
        $this->add(array(
            'name' => 'audiofiledir',
            'type' => 'Text',
            'options' => array(
                'label' => 'Audiofiledir ',
                ),
            ));
    }
    public function addElements()
    {
        // File Input
        $file = new Element\File('audio-file');
        $file->setLabel('Upload Audio File')->setAttribute('id', 'audio-file')->setAttribute('style', 'width: 280px')->setAttribute('class', 'form-group')->setAttribute('placeholder', 'audio-file');
        $this->add($file);
    }
}