<?php
class DPLS_Db{
    
    private $dbh;
    public $dbc;
   

    
    function __construct($auto=true){
        if($auto===true){
            $this->connect();
        }
    }
    
    function connect($host=DB_Server,$user=DB_User,$pass=DB_Pass,$database=DB_Database){
		try{
            if(!$this->dbc = mysql_connect($host,$user,$pass))  throw new DPLS_LinkedException('ERROR CONNECTING TO THE DATABASE: '.mysql_error());
            $this->dbh = mysql_select_db($database,$this->dbc);
            return $this->dbc;
		}catch(DPLS_LinkedException $e){
            echo $e->showStackTrace();
            die();
        }
             
    }
    
    function Execute($sql){
        try{  
            if(!$result=mysql_query($sql,$this->dbc)){
                throw new DPLS_LinkedException(mysql_error().' <br/>SQL Query: '.self::formatSql($sql));
            }
			$this->numRows=mysql_num_rows($result);
		    $this->last_id=mysql_insert_id();
            return $result;
        }catch(DPLS_LinkedException $e){
            echo $e->showStackTrace();
            die();
        }	
    }
    
    
    public function Update ($SQL){
        $rez=$this->ExecuteReplace($SQL);
        return $rez;
    }
    public function Insert ($SQL){
        
			$rez=$this->ExecuteReplace($SQL);
        	return $rez;
		
    }  
        
	function ExecuteReplace($sql){
        try{
        	if(!$result=mysql_query($sql,$this->dbc)) throw new DPLS_LinkedException(mysql_error() .'<br/>SQL Query: '.self::formatSql($sql));
		}catch(DPLS_LinkedException $e){
            echo $e->showStackTrace();
            die();
        }
		
		$this->numRows=$result;
       
	    
        $this->last_id=mysql_insert_id();
        return $result;
    }
	
    function getArray($sql){
        $result=array();
        
        $stmt=$this->dbh->prepare($sql);
        $stmt->execute();
        
        while ($row = $stmt->fetch($this->fetch_mode)) {
            $result[]=$row;
        }
        return $result;
    }
    
    function getNumRows($q){
        
        $numr=mysql_num_rows($q);
        return  $numr;
        
        
    }
    
    function getAssoc($SQL){
        
        $result=self::Execute($SQL);
        
      while ($row = mysql_fetch_assoc($result)) {
            
            $out[]= $row;    
            
        }
        return $out;
    }
    
     function getRow($SQL){
         $result=self::Execute($SQL);
         
         $out=mysql_fetch_assoc($result);
         
         return $out;
         	
         
         
         
     }
	 
	public static function formatSql($sql){
        $e = explode("\n",$sql);
        $color = "ffff00";
        $l = $sql;
        $l = str_replace("SELECT ", "<br /><font color='$color'><b>SELECT </b></font><br \>  ",$l);
        $l = str_replace("FROM ", "<br/><font color='$color'><b>FROM </b></font><br \>",$l);
        $l = str_replace("LEFT JOIN ", "<br \><font color='$color'><b> LEFT JOIN </b></font>",$l);
        $l = str_replace("INNER JOIN ", "<br \><font color='$color'><b> INNER JOIN </b></font>",$l);
        $l = str_replace("WHERE ", "<br \><font color='$color'><b> WHERE </b></font>",$l);
        $l = str_replace("GROUP BY ", "<br \><font color='$color'><b> GROUP BY </b></font>",$l);
        $l = str_replace(" HAVING ", "<br \><font color='$color'><b> HAVING </b></font>",$l);
        $l = str_replace(" AS ", "<font color='$color'><b> AS </b></font><br \>  ",$l);
        $l = str_replace("ON ", "<br><font color='$color'><b> ON </b></font>",$l);
        $l = str_replace("ORDER BY ", "<br /><font color='$color'><b> ORDER BY </b></font>",$l);
        $l = str_replace("LIMIT ", "<font color='$color'><b> LIMIT </b></font><br \>",$l);
        $l = str_replace("OFFSET ", "<font color='$color'><b> OFFSET </b></font><br \>",$l);
        $l = str_replace("  ", "<dd>",$l);
        return $l;
    }
	
	 
	 function getRowById($fields=true,$table,$id){
		$id= (int) $id;
		if($fields!==true){
		$fields=implode(',',$fields);
		}else{
			$fields='*';
		}
		$SQL="SELECT $fields FROM $table WHERE id=$id ";
		$result=$this->getRow($SQL);
		return $result;
		
	 }
    
    function closeConnection($link){
       mysql_close($this->dbc);    
    }
    
    function setFetchMode($mode){
        $this->fetch_mode=$mode;
    }
    
}
?>