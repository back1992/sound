<?php
namespace Audio\Form;
use Zend\Form\Element;
// use Zend\Form\Element\Url;
use Zend\Form\Form;
// use Zend\InputFilter;
class ViewAudioForm extends Form
{
	public function __construct($name = null)
	{
// we want to ignore the name passed
		parent::__construct('ViewAudio');

		$this->setAttribute('method', 'post');
		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
			));
		
		$this->add(array(
			'name' => 'mp3',
			'type' => 'file',
			'options' => array(
				'label' => 'MP3 File: ',
				),
			));
		$this->add(array(
			'name' => 'width',
			'type' => 'text',
			'options' => array(
				'label' => 'Image Width: ',
				),
			));
		$this->add(array(
			'name' => 'height',
			'type' => 'text',
			'options' => array(
				'label' => 'Image Height: ',
				),
			));
		$this->add(array(
			'name' => 'foreground',
			'type' => 'color',
			'options' => array(
				'label' => 'Foreground Color: (HEX/HTML color code)',
				),
			));
		$this->add(array(
			'name' => 'background',
			'type' => 'color',
			'options' => array(
				'label' => 'Background Color: (Leave blank for transparent background) (HEX/HTML color code)',
				),
			));
		$this->add(array(
			'type' => 'Zend\Form\Element\Checkbox',
			'name' => 'flat',
			'options' => array(
				'label' => 'Draw flat-line?',
				'use_hidden_element' => true,
				'checked' => 'checked',
				'checked_value' => '1',
				'unchecked_value' => '0'
				)
			));
		$this->add(array(
			'type' => 'Zend\Form\Element\Checkbox',
			'name' => 'stereo',
			'options' => array(
				'label' => 'Stereo waveform? ',
				'use_hidden_element' => true,
				'checked' => 'checked',
				'checked_value' => '1',
				'unchecked_value' => '0'
				)
			));
		$this->add(array(
			'name' => 'process',
			'type' => 'Zend\Form\Element\Button',
			'type' => 'Submit',
			'options' => array(
				'value' => 'Process',
				'label' => 'Process'
				)
			));
		$this->add(array(
			'name' => 'submit',
			'type' => 'Submit',
			'attributes' => array(
				'value' => 'Generate Waveform',
				'id' => 'submitbutton',
				),
			));
	}
}
