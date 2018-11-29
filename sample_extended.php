<?php
//allows script to be ran anywhere on system
define('ROOT',realpath(dirname(__FILE__).'/').'/');

//debugging show errors and stuff
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once ROOT.'binco.class.php';

//--- extended test -> c(25,5)
	$info = "extended test -> c(25,5)";
	echo "[processing] ".$info."\n";

	//n = 25, total items to use
	$pool = range(1,25);

	//r = 5, combo length
	$length = 5;

	//initialize class
	$app = new binoco($pool,$length);

	$app->result = function($combo){
		//custom processing of combination match, combo actual pool values
		echo implode(', ',$combo)."\n";
	};

	//process data
	$app->process(0,'result');
//--- extended test -> c(25,5)