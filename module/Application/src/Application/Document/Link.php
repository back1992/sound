<?php
namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


use Doctrine\ODM\MongoDB\SoftDelete\Configuration;
use Doctrine\ODM\MongoDB\SoftDelete\UnitOfWork;
use Doctrine\ODM\MongoDB\SoftDelete\SoftDeleteManager;
use Doctrine\Common\EventManager;


/** @ODM\Document(collection="link") */
class Link
{
    /** @ODM\Id */
    public $id;

    /** @ODM\Field(type="string") */
    public $url;

    /** @ODM\Field(type="string") */
    public $title;

    protected $inputFilter;

    /**
     * @return the $id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return the $url
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param field_type $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param field_type $url
     */
    public function setTitle($title) {
        $this->title = $title;
    }


    /**
     * Gets the value of url.
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * Sets the value of url.
     *
     * @param mixed $url the url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory
            = new InputFactory();
            $inputFilter->add($factory->createInput(array(
                'name'
                => 'id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                    ),
                )));
            $inputFilter->add($factory->createInput(array(
                'name'
                => 'url',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    ),
                'validators' => array(
                    array(
                        'name'
                        => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'
                            => 1,
                            'max'
                            => 100,
                            ),
                        ),
                    ),
                )));
            $inputFilter->add($factory->createInput(array(
                'name'
                => 'title',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    ),
                'validators' => array(
                    array(
                        'name'
                        => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'
                            => 1,
                            'max'
                            => 100,
                            ),
                        ),
                    ),
                )));
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}