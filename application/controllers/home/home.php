<?php

class home extends DPLS_Controller{

	function defaultAction(){
		
		// initialize template engine;
		$template=new DPLS_Template();
		
		$username='John B.';
		
		$template->assign('user',$username);
		
		// show index.tpl file
		$template->show('index');
		
	}

}