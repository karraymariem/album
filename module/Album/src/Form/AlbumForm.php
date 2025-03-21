<?php
namespace Album\Form;

use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;

class AlbumForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('album');
        
        $this->add([
            'name' => 'id',
            'type' => Hidden::class,
        ]);
        
        $this->add([
            'name' => 'code',
            'type' => Text::class,
            'options' => [
                'label' => 'Code',
            ],
        ]);
        
        $this->add([
            'name' => 'designation',
            'type' => Text::class,
            'options' => [
                'label' => 'Designation',
            ],
        ]);
        
        $this->add([
            'name' => 'cadence',
            'type' => Text::class,
            'options' => [
                'label' => 'Cadence',
            ],
        ]);
        
        $this->add([
            'name' => 'description',
            'type' => Text::class,
            'options' => [
                'label' => 'Description',
            ],
        ]);
        
        $this->add([
            'name' => 'submit',
            'type' => Submit::class,
            'attributes' => [
                'value' => 'Ajouter',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
