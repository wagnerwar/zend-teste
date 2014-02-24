<?php

namespace Chat\Model;

class ChatTable{
	private $db;
	
	function __construct( $db){
		$this->db = $db;
	}
	
	function getChat($id,$outro){
		$results = $this->db->query("SELECT * FROM chat WHERE id_usuario_remetente in ('$id','$outro') and id_usuario_destinatario in ('$id','$outro') ");
		$rows = $results->execute();
		return $rows;
	}
	
	function enviaMensagem($info){
		$stmt = $this->db->createStatement("INSERT INTO chat VALUES (?,?,?,FROM_UNIXTIME(UNIX_TIMESTAMP())) ",array($info['id_usuario_remetente'],$info['id_usuario_destinatario'],$info['mensagem']));
		return $stmt->execute();
	}
}