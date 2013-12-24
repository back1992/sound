<?php
namespace Audio\Form;
use Zend\Form\Element;
// use Zend\Form\Element\Url;
use Zend\Form\Form;
// use Zend\InputFilter;
class QuestionForm extends Form
{
	public function __construct($name = null)
	{
// we want to ignore the name passed
		parent::__construct('Question');

		$this->setAttribute('method', 'post');
		$this->setAttribute('class','form-inline');
		 $this->setAttribute('role','form');
		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
			));
		
		$this->add(array(
			'name' => 'begin',
			'type' => 'text',
			'options' => array(
				'label' => 'Begin: ',
				),
			'attributes' => array(
				'placeholder' => 'begin text',
				'class' => 'form-control',
				'id' => 'begin',
				),
			));
		$this->add(array(
			'name' => 'end',
			'type' => 'text',
			'options' => array(
				'label' => 'End: ',
				),
			'attributes' => array(
				'placeholder' => 'end text',
				'class' => 'form-control',
				'id' => 'end',
				// 'size' => "135",
				),
			));
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Curl Questions',
				'class' => 'btn btn-primary',
				'id' => 'submitbutton',
				),
			));
	}
}
