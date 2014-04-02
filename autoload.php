<?php
    spl_autoload_register(function($class){
	$myAppInstall=__DIR__;
	$myAppDirs = array('view','modele','controller','fb');
	
	foreach($myAppDirs as $cdir){
		$file = $myAppInstall .DIRECTORY_SEPARATOR. $cdir .
			DIRECTORY_SEPARATOR. $class.'.php';
		if(is_file($file)){
			require_once $file;
			break;
		}
	}

});
?>

