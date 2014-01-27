<?php
namespace Quiz\Form;
use Zend\Form\Form;
use Zend\Form\Element\Url;
class QuizForm extends Form
{
	public function __construct($name = null)
	{
// we want to ignore the name passed
		parent::__construct('quiz');
		$this->setAttribute('method', 'post');
		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
			));
		$this->add(array(
			'name' => 'title',
			'type' => 'Text',
			'options' => array(
				'label' => 'Title',
				),
			));
		$this->add(array(
			'name' => 'url',
			'type' => 'Url',
			'options' => array(
				'label' => 'Url',
				),
			));
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Go',
				'id' => 'submitbutton',
				),
			));
	}
}
