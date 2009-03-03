<?php
Class Players{
	public function __construct ($db){
	
		$this->db=$db;
	}
	
	
	public function getAll(){
		$rez=$this->db->getAssoc("select * from players");
		return $rez;
	}



}
?>