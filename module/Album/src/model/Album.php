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

class Album implements InputFilterAwareInterface
{
    private $inputFilter;
    public $code;
    public $designation;
    public $cadence;
    public $description;

    public function exchangeArray(array $array): void
    {
        $this->code        = !empty($array['code']) ? $array['code'] : null;
        $this->designation = !empty($array['designation']) ? $array['designation'] : null;
        $this->cadence     = !empty($array['cadence']) ? $array['cadence'] : null;
        $this->description = !empty($array['description']) ? $array['description'] : null;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        // ID (if applicable)
        $inputFilter->add([
            'name' => 'id',
            'required' => false,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        // Code
        $inputFilter->add([
            'name' => 'code',
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
                        'max' => 50,
                    ],
                ],
            ],
        ]);

        // Designation
        $inputFilter->add([
            'name' => 'designation',
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
            ],
        ]);

        // Cadence
        $inputFilter->add([
            'name' => 'cadence',
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
                        'max' => 50,
                    ],
                ],
            ],
        ]);

        // Description
        $inputFilter->add([
            'name' => 'description',
            'required' => false, // Can be optional
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 0,
                        'max' => 255,
                    ],
                ],
            ],
        ]);

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

    // Implement the setInputFilter method
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException('Not used');
    }
}
