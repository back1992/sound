<?php
namespace Snoopy\Form;
use Zend\Form\Element;
use Zend\Form\Form;
// use Zend\Form\Element\Date;
// use Zend\InputFilter\InputFilter;
use Zend\InputFilter;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
class MultiUploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('image-file');
        $file->setLabel('Avatar Image Upload')
        ->setAttribute('id', 'image-file')
             ->setAttribute('multiple', true);   // That's it
             $this->add($file);
         }

         public function addInputFilter()
         {
            $inputFilter = new InputFilter\InputFilter();

        // File Input
            $fileInput = new InputFilter\FileInput('image-file');
            $fileInput->setRequired(true);

        // You only need to define validators and filters
        // as if only one file was being uploaded. All files
        // will be run through the same validators and filters
        // automatically.
            $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 204800))
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png'))
            ->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100));

        // All files will be renamed, i.e.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
            $fileInput->getFilterChain()->attachByName(
                'filerenameupload',
                array(
                    'target'    => './data/tmpuploads/avatar.png',
                    'randomize' => true,
                    )
                );
            $inputFilter->add($fileInput);

            $this->setInputFilter($inputFilter);
        }
    }