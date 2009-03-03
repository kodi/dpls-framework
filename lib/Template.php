<?php
class DPLS_Template{
	
	
	public function assign ($name='', $value='' ){
		$name='v_'.$name;
		$this->$name=$value;
	}
	
	function show($templateName){
		try{
			if(!include_once(MAIN_PATH.'/application/templates/'.$templateName.'.tpl')) throw new DPLS_LinkedException("Cant't find template $templateName.tpl");
		}catch(DPLS_LinkedException $e){
			echo $e->showStackTrace();
		}
		
	}

}
?>