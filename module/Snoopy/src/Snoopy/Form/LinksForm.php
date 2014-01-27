<?php
namespace Snoopy\Form;
use Zend\Form\Form;
class LinksForm extends Form
{
	public function __construct($name = null)
	{
// we want to ignore the name passed
		parent::__construct('album');
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
			'name' => 'start',
			'type' => 'Text',
			'options' => array(
				'label' => 'Unique Start Position',
				),
			));
		$this->add(array(
			'name' => 'end',
			'type' => 'Text',
			'options' => array(
				'label' => 'Unique end Position',
				),
			));
		$this->add(array(
			'name' => 'artist',
			'type' => 'Text',
			'options' => array(
				'label' => 'Artist',
				),
			));
		$this->add(array(
			'type' => 'Zend\Form\Element\Url',
			'name' => 'url',
			'options' => array(
				'label' => 'Webpage URL'
				)
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
