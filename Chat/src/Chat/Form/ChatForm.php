<?php

namespace Chat\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class ChatForm extends Form{
	
	public function __construct($name=null){
		parent::__construct("envia_mensagem");
		$this->setAttribute('method','post');
		
		$this->add(array(
		'name' => 'id_usuario_remetente',
		'attributes' => array(
			'type' => 'hidden',
		)
		));
		
		$this->add(array(
		'name' => 'id_usuario_destinatario',
		'attributes' => array(
			'type' => 'hidden',
		)
		));
		
		$this->add(array(
			'name' => 'mensagem',
			'attributes' => array(
				'type' => 'textarea',
				'rows' => '4',
				'class' => 'form-control'				
			),
			'options' => array(
				'label' => 'Mensagem',
			),
		));
		
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