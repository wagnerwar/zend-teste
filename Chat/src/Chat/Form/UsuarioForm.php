<?php

namespace Chat\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class UsuarioForm extends Form{
	
	public function __construct($name){
		parent::__construct($name);
		
		$this->setAttribute('method','post');
		$this->setAttribute('id','login');
		$this->add(array(
			'name' => 'id_usuario',
			'attributes' => array(
				'type' => 'hidden',
			)
		));
		
		$this->add(array(
			'name' => 'apelido',
			'attributes' => array(
				'type' => 'text',
			),
			'options' => array(
				'label' => 'Apelido'
			)
		));
		
		$select = new Element\Select("sexo");
		$select->setLabel("Gênero");
		$select->setValueOptions(array(
			'ND' => 'Selecione',
			'M' => 'Masculino',
			'F' => 'Feminino'
		));
		
		$this->add($select);
		
		$this->add(array(
			'name' => 'enviar',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Gravar',
				'id' => 'enviar',
				'class' => 'btn btn-primary'
			)
		));
	}
}