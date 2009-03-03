<?php
/**
 * Deals with requests
 * @version 0.5b
 * @package CMS_CORE
 */

class DPLS_Request {
    /**
     * _request holds controller, action 
     *
     * @var array
     */
    var $_request = array();
    
    /**
     * Additional params
     *
     * @var array
     */
    var $_params = array();
	private $_rurl;
	var $_segment;
    
	/**
	 * GET REQUEST URI and extract Controller, action and params
 	 *
	 * @return Request
	 */
	function __construct() {
		$RR=str_replace('%20',' ',$_SERVER['REQUEST_URI']);
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'POST')) {
            
			$this->post_params=& $_POST;
			
			 
        } else {

			$this->get_params=& $_GET;
        }
		
		if(INSTALL_DIR==''){
                $this->_request=explode ('/',str_replace_once('/','',$RR));
            }else{
							
                $this->_request=explode ('/',(str_replace_once(INSTALL_DIR.'/','',$RR)));
            }
		
		
		
		$this->_rurl=$_SERVER['SERVER_NAME'];
		
		if(SERVER_NAME!=$_SERVER['SERVER_NAME']){
			$this->_segment=str_replace('.'.SERVER_NAME,'',$_SERVER['SERVER_NAME']);
		}



		$size= count($this->_request)-3;
        
		$this->_request['action']='';
		if(isset($this->_request[2])){
        	$this->_request['action']=$this->_request[2];
    	}
		$this->_request['c']=$this->_request[1];
  
      	if($this->_segment==''){
        	if($this->_request['c']==''){ 
				$this->_request['c'] = DEFAULT_CONTROLLER; 
			}
		
		}else{
			if($this->_request['c']==''){ 
				$this->_request['c'] = SHOP_CONTROLLER; 
			}
		}
        if($this->_request['action']==''){
			$this->_request['action'] = DEFAULT_ACTION; 
		}
        
        
        

	
		for($i=0;$i<$size;$i++){
			  
			    $this->_params[] = $this->_request[$i+3];  
			    
			}
		
		
        ///$this->_request['PATH_INFO'] = $_SERVER['PATH_INFO'];
       // if (! get_magic_quotes_gpc()) {
       //      $this->removeSlashes($this->_request);
       //}
    }

   
    function removeSlashes(&$str) {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                if (is_array($val)) {
                    $this->removeSlashes($val);
                } else {
                    $array[$key] = stripslashes($val);
                }
           }
        } else {
            $str = stripslashes($str);
        }
    }

    function get($name) {
        return $this->_request[$name];
    }

    function set($name, $value) {
		if ($name) {
            $this->_request[$name] = $value;
        }
    }
    
	function getPOST($name) {
        return $this->post_params[$name];
    }
    
    function getParams(){
        return $this->_params;
    }

	function getParam($name){
		if(isset($this->get_params[$name])){
			return $this->get_params[$name];
		}else{
			return false;
		}
	}
	

}

?>