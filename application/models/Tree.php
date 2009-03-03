<?php
Class Tree{
	 function __construct (){
		global $db;
		$this->db=$db;
	}
	
	
	 public function createForUser($id,$nodeId=false){
		
		self::__construct();
		
		$id=(int) $id;
		$nodes=$this->db->getAssoc("select * from folders where user_id=$id or user_id=0 order by type");
		
		$htlm='<script type="text/javascript">'."\n";
		$htlm.='d = new dTree(\'d\'); '."\n";
		
		foreach( $nodes as $node ){
			if($node['parent_id']==0){
				$parent_id=-1;
			}else{
				$parent_id=$node['parent_id'];
			}
			switch($node['type']){
				case 'user-trash':
					$icon='/img/tango_icons/16x16/places/user-trash.png';
					$icon_open='';
				break;
				
				case 'user-doc':
					$icon='/img/tango_icons/16x16/places/user-home.png';
					$icon_open='/img/tango_icons/16x16/places/user-home.png';
				break;
				
				case 'projects':
					$icon='/img/tango_icons/16x16/apps/system-file-manager.png';
					$icon_open='/img/tango_icons/16x16/apps/system-file-manager.png';
				break;
				
				default:
					$icon='/img/tango_icons/16x16/places/folder.png';
					$icon_open='';
				break;
			}
			
			$htlm.='d.add('.$node['id'].','.$parent_id.',\''.$node['name'].'\',\'/folder/view/'.$node['id'].'\',\'\',\'\',\''.$icon.'\',\''.$icon_open.'\'); '."\n";	
		}
			
		
	
			
		$htlm.='document.write(d); '."\n";		
		//$htlm.='d.s(4); '."\n";
		$htlm.='</script>';		
		
		echo $htlm;
		
	}
	
	public function getNode($id){
		self::__construct();
		$folder=$this->db->getRow("SELECT * FROM folders where id=$id");
		return $folder;
	}	
	
	public function getPath($id){
		$folder=self::getNode($id);
		$this->path='<a href="/folder/view/'.$folder['id'].'">'.$folder['name'].'</a>';
		
		
		while ( $folder['parent_id']!=0 ){
			$tmp=$this->path;
			$folder=self::getNode($folder['parent_id']);
			$this->path='<a href="/folder/view/'.$folder['id'].'">'.$folder['name'].'</a>'.' &raquo; '.$tmp;		
		}
		
		return $this->path;
	}


}
?>