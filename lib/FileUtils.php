<?php
/**
 * File Utils
 * @version 0.5b
 * @package utils
 */
class DPLS_FileUtils{
   
    /**
    * Usualy holds result of some listing
    *
    * @var array $dirs collection of dirs
    */
   private $dirs=array();
   
   /**
    * Path that we use
    *
    * @var string $path 
    */
   private $path;
   
   function __construct(){
      
   }
   
   /**
    * Set path
    *
    * @param string $path
    */
   function setPath($path){
       $this->path=$path;
   }
   /**
    * List ONLY dirs on given path
    *
    * @return array
    */
   function listDirs(){
	   $invalidNames=array('.svn');	
       $dirs=array();
	   $i=0;
       if ($handle = opendir($this->path)) {
           while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && is_dir($this->path.$file)) {
					if(!in_array($file,$invalidNames)){
						$fstat = stat($this->path.$file);
						$dirs[$i]['name']=$file;
						$dirs[$i]['lastModified']=date( "M d Y H:i:s", $fstat['mtime'] );
						$i++;
					}
                }
            }
            closedir($handle);
       }
	   $dirs=columnSort($dirs,array('name'));
       $this->dirs=$dirs;
       return $dirs;
   }
   
    function listFiles(){
	
	   $validExtensions=array('css','php','jpg','jpeg','gif','png','html','js','sql','flv');	
       $files=array();
	   $i=0;
       if ($handle = opendir($this->path)) {
           while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && is_file($this->path.$file)) {
					
					$extn=explode('.',$file);
					$ext=array_pop($extn);
					if(in_array($ext,$validExtensions)){
					$fstat = stat($this->path.$file);		
					$files[$i]['name']=$file;
					$files[$i]['lastModifiedNice']=date( "M d Y H:i:s", $fstat['mtime'] );
					$files[$i]['lastModified']= $fstat['mtime'] ;
					$files[$i]['size']=( $fstat['size']); //FUNCTION SCALE
					$files[$i]['ext']=$ext;
					$i++;
					}
                }
            }
            closedir($handle);
       }
       //$this->f=$dirs;
	   //$files=columnSort($files,array('name'));
	   
      return $files;
   }
   
   
   function isReadable($path){
   	if(!file_exists($path)) return false; 
   	
   	if (is_readable($path)) return true;
   	
   }
   
   function writeFile($path,$data){
       
       file_put_contents($file,$data);
       
   }
}

?>