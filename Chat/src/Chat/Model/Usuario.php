<?php

namespace Chat\Model;


use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Usuario{

	public $id_usuario;
	public $apelido;
	public $sexo;
	protected $inputFilter;
	

	
	
  public function exchangeArray($data)
    {
        $this->id_usuario     = (isset($data['id_usuario'])) ? $data['id_usuario'] : null;
        $this->apelido = (isset($data['apelido'])) ? $data['apelido'] : null;
        $this->sexo  = (isset($data['sexo'])) ? $data['sexo'] : null;
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    } 
    
	public function getInputFilter(){
		$inputFilter = new InputFilter();
		$f = new InputFactory();
		
		$inputFilter->add($f->createInput(array(
			'name' => 'id_usuario',
			'required' => true,
			'filters' => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
				#array('name' => 'Int'),
				
			),

			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'min' => 2,
						'max' => 20
					)	
				),
			)
		)));
		
		$inputFilter->add($f->createInput(array(
			'name' => 'apelido',
			'required' => true,
			'filters' => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				array(
					'name' => 'StringLength',
					'options' => array(
						'min' => 3,
						'max' => 20
					)	
				),
			)
		)));
		
		$inputFilter->add($f->createInput(array(
			'name' => 'sexo',
			'required' => true,

			'validators' => array(
				array(
					'name' => 'inArray',
					'options' => array(
						'haystack' => array('M','F'),
					)
				)
			)
		)));
		
		$this->inputFilter = $inputFilter;
		
		return $this->inputFilter;
	}
}