<?php
class DPLS_Acl{
    
	// a 2-D array of roles and the permissions assigned to them
    private $_roles;
 

    public function __construct($roles_permissions){
        $this->_roles = $roles_permissions;
    }

    public function allows($user, $action){
		
        // for each role the user is assigned, check and see if the specified
        // action is part of that role
        
		$roles=$user->getRoles();
		//
		foreach ($roles as $role){
  			if (in_array( $action,$this->_roles[$role])) return TRUE;
        }    
       	return FALSE;
    }
}

?>