<?php 
namespace Album\Model;

use DomainException;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Validator\StringLength;
use Laminas\Validator\NotEmpty;

class Album implements InputFilterAwareInterface
{
    public $id;
    public $artist;
    public $title;

    private $inputFilter;

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->artist = !empty($data['artist']) ? $data['artist'] : null;
        $this->title  = !empty($data['title']) ? $data['title'] : null;
    }

    // We don't allow external input filters to be injected
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }

    // Define the input filter for the Album model
    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        // ID field filter (may or may not be required depending on your use case)
        $inputFilter->add([
            'name' => 'id',
            'required' => false, // Assuming ID is auto-generated in the database
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        // Artist field filter and validation
        $inputFilter->add([
            'name' => 'artist',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                [
                    'name' => NotEmpty::class, // Optional, if you want to ensure it's not empty
                    'options' => [
                        'message' => 'Artist name cannot be empty',
                    ],
                ],
            ],
        ]);

        // Title field filter and validation
        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                [
                    'name' => NotEmpty::class, // Optional, if you want to ensure it's not empty
                    'options' => [
                        'message' => 'Title cannot be empty',
                    ],
                ],
            ],
        ]);

        // Cache the input filter
        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}
