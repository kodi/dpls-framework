<?php
define('SERVER_NAME','engine.local');
ini_set('session.cookie_domain', SERVER_NAME);



define('MAIN_PATH',substr(str_replace('config.inc.php','',__FILE__),0,-1));
define('INSTALL_DIR','/');
define('DEFAULT_CONTROLLER','home');
define('SHOP_CONTROLLER','user_shop');


define('USER_LOGIN_URL','/user/login/');
define('DEBUG',true);
define('DEFAULT_ACTION','defaultAction');

session_start();
error_reporting(E_ALL);

//DATABASE CONFIG
define('DB_Database','pdfbase');
define('DB_User','root');
define('DB_Pass','');
define('DB_Server','127.0.0.1');


$db=new DPLS_Db();
$db->ExecuteReplace('set names utf8');

//MODELS
$libIndex['autoloadClass']['Categories']='/application/models/Categories.php';
$libIndex['autoloadClass']['Users']='/application/models/Users.php';
$libIndex['autoloadClass']['Media']='/application/models/Media.php';
$libIndex['autoloadClass']['Tree']='/application/models/Tree.php';
$libIndex['autoloadClass']['Twitter']='/application/models/Twitter.php';
$libIndex['autoloadClass']['Curl_HTTP_Client']='/lib/curl_http_client/curl_http_client.php';
$libIndex['autoloadClass']['Log']='/application/models/Log.php';
$libIndex['autoloadClass']['Store']='/application/models/Store.php';
$libIndex['autoloadClass']['Cache_Lite']='/lib/cache_lite/Lite.php';


$roles = array(
    'administrator' => array(
        'DeleteStoreItems', 
        'EditStoreItems', 
        'DeleteStore',
        'AccessAdminArea'
    ),
	'user' => array(
        'CreateHisStore', 
        'EditHisStore', 
        'DeleteHisStore',
		'Login'
    ),
    'moderator' => array(
        'DeleteStoreItems',
        'EditStoreItems'
    )
);

$acl=new DPLS_Acl($roles);
$_user=new DPLS_Auth();





// DO NOT EDIT BELOW THIS LINE
// ----------------------------------------


function __autoload($class)
{
	global $libIndex;
	
	if(isset($libIndex['autoloadClass'][$class])){ 
		$filename= $libIndex['autoloadClass'][$class]; 
	}else{

		$filename=implode('/',explode('_',$class));
		$filename=str_replace('DPLS/','/lib/',$filename.'.php');
	}
	try{
		if(!include_once(MAIN_PATH.$filename)){ throw new DPLS_LinkedException('Cant\'t Load Class ::: '.$filename ); }
	}catch(DPLS_LinkedException $e){
		echo $e->showStackTrace();
	}
} 

function str_replace_once($needle, $replace, $haystack) {
	// Looks for the first occurence of $needle in $haystack
	// and replaces it with $replace.
	$pos = strpos($haystack, $needle);
	if ($pos === false) {
		// Nothing found
		return $haystack;
	}
	return substr_replace($haystack, $replace, $pos, strlen($needle));
} 

 
?>