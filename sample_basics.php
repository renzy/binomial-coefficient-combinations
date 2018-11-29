<?php
//allows script to be ran anywhere on system
define('ROOT',realpath(dirname(__FILE__).'/').'/');

//debugging show errors and stuff
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once ROOT.'binco.class.php';

//--- basic test -> c(25,5)
	$info = "basic test -> c(25,5)";
	echo "[processing] ".$info."\n";

	//n = 25, total items to use
	$pool = range(1,25);

	//r = 5, combo length
	$length = 5;

	//initialize class
	$app = new binoco($pool,$length);

	//process data
	$app->process();
//--- basic test -> c(25,5)

//--- basic test -> c(25,5) [no output]
	$info = "basic test -> c(25,5) [no output]";
	echo "[processing] ".$info."\n";
	//n = 25, total items to use
	$pool = range(1,25);

	//r = 5, combo length
	$length = 5;

	//initialize class
	$app = new binoco($pool,$length);

	//process data
	$app->process(0);

	echo "[complete] ".$info."\n\n";
//--- basic test -> c(25,5)


//--- basic test -> c(50,5) [output throttled]
	$info = "basic test -> c(50,5) [output throttled]";
	echo "[processing] ".$info."\n";

	//n = 50, total items to use
	$pool = range(1,50);

	//r = 5, combo length
	$length = 5;

	//initialize class
	$app = new binoco($pool,$length);

	//process data
	$app->process();
//--- basic test -> c(50,5)

//--- basic test -> c(50,5) [only show completion summary]
	$info = "basic test -> c(50,5) [only show completion summary]";
	echo "[processing] ".$info."\n";

	//n = 50, total items to use
	$pool = range(1,50);

	//r = 5, combo length
	$length = 5;

	//initialize class
	$app = new binoco($pool,$length);

	//process data
	$app->process(2);
//--- basic test -> c(50,5)

?>