<?php

class DPLS_Dispatcher{
	
	function isDispachable(DPLS_Request $request){
		
		$this->_controller=$request->get('c');
		$classFile=MAIN_PATH.'/application/controllers/'.$this->_controller.'/'.$this->_controller.'.php';
		

	    	
		if (DPLS_FileUtils::isReadable($classFile)){
			if(!class_exists($this->_controller,false)){include_once($classFile);}	
			return true;
		}
		return false;
	}
	
}


?>