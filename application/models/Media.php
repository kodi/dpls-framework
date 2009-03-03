<?php
Class Media{
	public function __construct ($db){
		$this->db=$db;
	}
	
	
	public function getAll(){
		$rez=$this->db->getAssoc("select * from media");
		return $rez;
	}
	
	public  function getPhotosInCategory ( $categoryId ){
		$rez=$this->db->getAssoc("select * from media where type='photo' and category_id=$categoryId ");
		return $rez;
	}
	
	public  function getPhoto ( $photoId ){
		$rez=$this->db->getRow("select media.id,media.name,path,categories.name as category_name,categories.id as category_id from media 
		
		LEFT JOIN categories ON
		categories.id=media.category_id
		
		where type='photo' and media.id=$photoId  ");
		return $rez;
	}
	

	
	
}
?>