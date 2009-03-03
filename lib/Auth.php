<?php
/**
 * Auth class
 * @version 0.5b
 * @package Auth
 *
 */


class DPLS_Auth{
    /**
     * Username
     *
     * @var string
     */
    var $username;
    /**
     * Password
     *
     * @var string
     */
    var $password;
    /**
     * User Level
     *
     * @var integer
     */
    
 	var $db;
 	var $userLevel;

	private $_roles=array();

   
 
    function DPLS_Auth($mdb=false){
		if(!$mdb){
     		global $db;
	        $this->db=$db;
	      }else{
	          $this->db=$db;
	      }

 		
		self::assignRoles();

		
    }



    public function getRoles(){
        return $this->_roles;
    }
	
	public function assignRoles(){
		
		global $roles;
		
		if(self::isLevel(4)){
			$this->setRoles('administrator');
			
		}
		
		if(self::isLevel(1)){
			$this->setRoles('user');
			$this->username=$_SESSION['username'];
			$this->userLevel=$_SESSION['userLevel'];
			$this->shopURL=self::generateShopURL();
			
		}
		
	}
	


    public function setRoles($roles){
        array_push($this->_roles, $roles);
    }



	public function generateShopURL(){
		$url='http://'.$this->username.'.'.SERVER_NAME;
		return $url;
	}




   
    
    
    function isAuth($level=0){
        global $req;	
        
        if (DPLS_Auth::justCheckAuth($level)){
            return true;
        }else{
            header('location: /user/login');
        } 
    }
    
	function checkAuth($level=0){
        global $req;	
        
        if (DPLS_Auth::justCheckAuth($level)){
            return true;
        }else{
            return false;
        } 
    }
	
	
    function justCheckAuth($level){
        if (isset($_SESSION['username'])){
            if($_SESSION['userLevel']>=$level){
                   return  true;
               }    
          }
    }
    
	function isLevel($level){
        if (isset($_SESSION['username'])){
            if($_SESSION['userLevel']==$level){
                   return  true;
               }    
          }
    }


    /**
     * Do the actual auth proccess
     *
     * @param string $user
     * @param string $pass
     * @return boolean
     */
    function authUser($user,$pass){
        //global $db;
        $this->username=$user;
        $this->password=$pass;
    	$pass=md5($pass);
    	$data=array($user,$pass);
    	$user_count=$this->db->getRow("select user_level,id,user_status from users where username='$user' and password='$pass' ");
    	$this->dbg=$user_count;
        $rezz=$this->db->numRows;
        //print_r($rezz);
    	if($this->db->numRows){
	
			if($user_count['user_status']=='pending'){
				header('Location: /user/thanks/?msg=1');
				die();
			}
            $_SESSION['username']=$user;
	        $_SESSION['userLevel']=$user_count['user_level']; 
	        $_SESSION['userID']=$user_count['id'];
			
		    
		    $time=time();
		    $ip=$_SERVER['REMOTE_ADDR'];	
		    $this->db->Update("UPDATE users SET last_login_time='$time',last_login_ip='$ip' where id='$user_count[id]'");
		  
			//LOG EVENT IF ADMIN 
			if($user_count['user_level']==4){
				$log=new Log($this->db);				
		  		$log->add('User '.$user.' logged in from '.$ip,$user_count['id'],2);
         	}
			self::assignRoles();
			return  true;
    	}else{
            return false;
			self::assignRoles();
        }//if    
    }//authUser

    //geters and setters
    
    /**
     * Get Username from session vars
     *
     * @return string
     */
    function getUsername(){
        return $_SESSION['username'];
    }
    
    /**
     * Get User level from session vars
     *
     * @return integer
     */
    function getUserLevel(){
        return $_SESSION['userLevel'];
    }
}
?>