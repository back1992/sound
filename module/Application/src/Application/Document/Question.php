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


/** @ODM\Document(collection="question") */
class Question
{
        /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $no;

    /** @ODM\Field(type="string") */
    private $title;

    /** @ODM\Field(type="string") */
    private $A;

    /** @ODM\Field(type="string") */
    private $B;

    /** @ODM\Field(type="string") */
    private $C;

    /** @ODM\Field(type="string") */
    private $D;

    /** @ODM\Field(type="string") */
    private $ANS;

    protected $inputFilter;

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

    /**
     * Gets the value of no.
     *
     * @return mixed
     */
    public function getNo()
    {
        return $this->no;
    }
    
    /**
     * Sets the value of no.
     *
     * @param mixed $no the no
     *
     * @return self
     */
    public function setNo($no)
    {
        $this->no = $no;

        return $this;
    }

    /**
     * Gets the value of title.
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the value of title.
     *
     * @param mixed $title the title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the value of A.
     *
     * @return mixed
     */
    public function getA()
    {
        return $this->A;
    }
    
    /**
     * Sets the value of A.
     *
     * @param mixed $A the a
     *
     * @return self
     */
    public function setA($A)
    {
        $this->A = $A;

        return $this;
    }

    /**
     * Gets the value of B.
     *
     * @return mixed
     */
    public function getB()
    {
        return $this->B;
    }
    
    /**
     * Sets the value of B.
     *
     * @param mixed $B the b
     *
     * @return self
     */
    public function setB($B)
    {
        $this->B = $B;

        return $this;
    }

    /**
     * Gets the value of C.
     *
     * @return mixed
     */
    public function getC()
    {
        return $this->C;
    }
    
    /**
     * Sets the value of C.
     *
     * @param mixed $C the c
     *
     * @return self
     */
    public function setC($C)
    {
        $this->C = $C;

        return $this;
    }

    /**
     * Gets the value of D.
     *
     * @return mixed
     */
    public function getD()
    {
        return $this->D;
    }
    
    /**
     * Sets the value of D.
     *
     * @param mixed $D the d
     *
     * @return self
     */
    public function setD($D)
    {
        $this->D = $D;

        return $this;
    }

    /**
     * Gets the value of ANS.
     *
     * @return mixed
     */
    public function getANS()
    {
        return $this->ANS;
    }
    
    /**
     * Sets the value of ANS.
     *
     * @param mixed $ANS the a n s
     *
     * @return self
     */
    public function setANS($ANS)
    {
        $this->ANS = $ANS;

        return $this;
    }

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}