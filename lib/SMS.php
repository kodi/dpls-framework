<?php
class DPLS_SMS implements DPLS_iEventObserver {
	function __toString(){
		return '';
	}
	
	function update(DPLS_Event $event,$msg){
		
		echo "SMS reaguje na poruku: $msg <br />";
		if($event->getStatus()==2){
			echo "status 2: sve je ok <br />";	
		}
		
		if($event->getStatus()==0){
			echo "NEKI OPAK ERROR SE DESIO, NEMA SLANJA PORUKE <br />";	
		}
	
	}



}


?>