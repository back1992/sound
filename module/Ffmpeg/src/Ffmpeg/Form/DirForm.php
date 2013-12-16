<?php
namespace Ffmpeg\Form;
use Zend\Form\Element;
// use Zend\Form\Element\Url;
use Zend\Form\Form;
// use Zend\InputFilter;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
class dirForm extends Form
{
	public function __construct($name = null)
	{
// we want to ignore the name passed
		parent::__construct('dir');
		$this->setAttribute('method', 'post')
		->setHydrator(new ClassMethodsHydrator(false))
		->setInputFilter(new InputFilter());
		$this->setAttribute('class', 'form-horizontal form-fatter');
		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
			));
		$this->add(array(
			'type' => 'Zend\Form\Element\Checkbox',
			'name' => 'check',
			'options' => array(
				'label' => 'A checkbox',
				'use_hidden_element' => true,
				'checked' => 'checked',
				'checked_value' => '1',
				'unchecked_value' => '0'
				)
			));
		$this->add(array(
			'name' => 'title',
			'type' => 'text',
			'options' => array(
				'label' => 'Title: ',
				),
			'attributes' => array(
				'class' => 'form-control',
				'size' => 130,
				// 'margin-left' => '10px',
				),
			));
		$this->add(array(
			'name' => 'inputvalue',
			'type' => 'text',
			'options' => array(
				'label' => 'inputValue: ',
				),
			'attributes' => array(
				// 'id' => 'inputValue',
				'style' => 'background-color:#b0e0e6;',
				'class' => 'text-center'
				// 'class' => 'text-center col-xs-4'
				),
			));
		$this->add(array(
			'name' => 'range',
			'type' => 'Range',
			'options' => array(
				'label' => 'Range',
				),
			'attributes' => array(
				'onchange' => "updateTextInput(this, this.value);",
				),
			));
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			// 'type' => 'Zend\Form\Element\Button',
			'options' => array(
				'separator' => '<br />',
				),
			'attributes' => array(
				'value' => 'Submit',
				'class' => 'btn btn-default',
				),
			));
	}
}
	