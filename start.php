<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once "classes/File.php";

try {
	$file = new File('data.txt');
	// $file->close();
	$file->read();

	$file->prepend("1qwrqwq qsdfq qscwd cd1");

	// $file2 = new File('info.txt');
	// $file->close();
	// $file2->read();

	// $file2->write("qwrqwq qsdfq qscwd cd");
	
	echo '<pre>';
	var_dump($file->read());
	echo '</pre>';
}
catch(Exception $e)
{
	$message = $e->getMessage();
	echo $message;
}

// echo "hello world";