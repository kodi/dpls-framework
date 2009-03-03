<?php
include_once('config.inc.php');


$req=new DPLS_Request();
$app = new DPLS_FrontController();
$app->execute($req);

//$dbg=new DPLS_DBG($_COOKIE);
//$dbg->pr();

?>