<?php
Class Categories{
	public function __construct ($adb){
		global $db;
		$this->db=$db;
	}
	
	
	public function getAll(){
		$rez=$this->db->getAssoc("select * from categories");
		return $rez;
	}
	
	public function updatePhotoTime ($categoryId){
		$rez=$this->db->Update("UPDATE categories SET updatePhoto=NOW() WHERE id=$categoryId");
		return $rez;
	}
	
	public function updateVideoTime ($categoryId){
		$rez=$this->db->Update("UPDATE categories SET updateVideo=NOW() WHERE id=$categoryId");
		return $rez;
	}
		
	public function getItemCount(){
		$rez=$this->db->getAssoc("	
		
		select categories.name,categories.id,ME.cnt,episodeNum from categories
	
		LEFT JOIN (
		select count(media.id)as cnt, category_id from media 
	
		group by media.category_id
		) AS ME ON
		categories.id  = ME.category_id
		
		where !ISNULL(cnt)
		order by episodeNum desc ");
		return $rez;

	}
	
	public function getCategoryName ($categoryId){
		$rez=$this->db->getRow("select name from categories where id=$categoryId");
		return $rez['name'];
	}
}
?>