<?php
namespace Chat\Model;

use Zend\Db\TableGateway\TableGateway;

class UsuarioTable
{
    protected $tableGateway;

    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function insere($dados){
    	
    	$data = array('id_usuario' => $dados['id_usuario'],
    	'apelido' => $dados['apelido'],
    	'sexo' => $dados['sexo']);

    	$this->tableGateway->insert($data);
    }
    
    public function getUsuario($id){
    	$rowset = $this->tableGateway->select(array('id_usuario' => $id));
    	$row = $rowset->current();
    	return $row;
    }
    
    public function desloga($id){
    	$del = $this->tableGateway->delete(array('id_usuario' => $id));
    	return $del;
    }
    
    /*Todos exceto eu*/
    public function fetchAll(){
    	$rowset = $this->tableGateway->select();
    	return $rowset;	
    }
    
}