<?php 
/**
 * * Controller class
 * @version 0.5b
 * @abstract 
 * @package modules
 * @category abstract_controllers
 * 
 */
class DPLS_Controller{
    /**
     * Action that we execute
     *
     * @var string
     */
    public $action;
    /**
     * Controler that we use
     *
     * @var string
     */
    public $controller;
    /**
     * Additional params that we pass
     * to the action
     *
     * @var stying|array
     */
    public $params = array();
	public $auth;
    private $err;
    /**
     * Execute action in controler
     *
     * @param string $controller
     * @param string $action
     * @param string|array $params
     * @return unknown
     */
    function execute($request,$controller,$action='',$params=null) {
		$this->_req=$request;
       	$this->setAction($action); 
		$this->setController($controller);
		$this->setParams($params);

		//if action is valid/exists

		if($this->isvalidAction($this->action)) {
			global $acl;
			$this->acl=$acl;
			global $_user;
			$this->user=$_user;
			
			
			
			$this->$action();
			return true;
		} else {
			throw new DPLS_LinkedException('Invalid action: '.$action);
		}
	}// execute


	function isValidAction($action){

	  // Get reserved names and check action name...
	  $reserved_names = DPLS_Controller::getReservedActionNames();
      if(is_array($reserved_names) && in_array($action, $reserved_names))return false;

      // Get methods of this class...
      $methods = get_class_methods(get_class($this));

      // If we don't have defined action return false
      if(!in_array($action, $methods)) return false;

      // All fine...
      return true;
	}

	function getReservedActionNames() {
      static $names;
      
      // Get and check controller class
      $controller_class = 'DPLS_Controller';
      if(!class_exists($controller_class)) throw new DPLS_LinkedException("Controller class '$controller_class' does not exists");

      // If we don't have names get them
      
      if(is_null($names)) {
        $names = get_class_methods($controller_class);
        foreach($names as $k => $v) $names[$k] = strtolower($v);
      } // if

      
      // And return...
      return $names;
    } // getReservedActionNames

	

	function setAction($value) {
      $this->action = $value;
    } // setAction

	function setController($value) {
      $this->controller = $value;
    } // setController
    
    function setParams($value) {
      $this->params = $value;
    } // setParams

    /*function isAuth($level=0){
	    $this->auth=new DPLS_Auth();
		if($this->auth->isAuth($level)) return true;
	}
	*/
	function ifUserCan($role){
		if ($this->acl->allows($this->user, $role)){
			return true;
		}else{
			
			//header('Location: '.USER_LOGIN_URL);
		}
	}
	
	function setParam($key,$value){
  	    $this->params[$key]=$value;
	}
	
	public function getCleanPOST ($name,$stripSlashes=false){
		
		$value=$this->_req->getPOST($name);
		if($stripSlashes){
			$value=stripslashes($value);
		}
		
		$rez=mysql_real_escape_string($value);
		return $rez;
	}
	
	function attach(DPLS_iEventObserver $obs) {
		$this->observers["$obs"] = $obs;
	}

	function detach(DPLS_iEventObserver $obs) {
		delete($this->observers["$obs"]);
	}
	
	protected function notify($event,$message) {

		foreach ($this->observers as $obs) {
			$obs->update($event, $message);
		}
	}
	
	
	
	
}
?>