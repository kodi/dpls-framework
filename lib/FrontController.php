<?php
/**
 * Front Controller
 * @version 0.5b
 * @package CMS_CORE
 * @author Dragan Bajcic <dragan@devpulse.net>
 *
 */
class DPLS_FrontController{
    /**
     * Execution time 
     *
     * @var string
     */
    public $execTime;
    /**
     * Memory usage
     * holds amount of memory that system uses
     *
     * @var string
     */
    public $memUsage;
	public $user;
	private $controller;
	private $action;
	private $params = array();
	/**
	 * route to controler and execute action
	 *
	 * @param object $request request object
	 */
	 
	 
	function _execute($request){
			$this->_execute($request);
	} 
	 
	function execute($request){
	    $this->controller=$request->get('c');
	    $this->action=$request->get('action');;
	    $this->params=$request->getParams();

	    $dispatcher=new DPLS_Dispatcher();
	    try{
			if($dispatcher->isDispachable($request)){
				$this->app_module = new $this->controller;
				$this->app_module->execute($request,$this->controller,$this->action,$this->params);	
			}else{
				throw new DPLS_LinkedException('Invalid Controller');
			}
		}catch(DPLS_LinkedException $e){
			echo $e->showStackTrace();
		}			
	}
	
	/**
	 * Deals with authentification of users
	 *
	 * @param object Auth object
	 */
	function auth($auth){
	    $this->user[username]=$auth->getUsername();
	    $this->user[userLevel]=$auth->getUserLevel();
	}
	/**
	 * Returns current controller
	 *
	 * @return object
	 */
	function getC(){
		return $this->controller;
	}
}




?>