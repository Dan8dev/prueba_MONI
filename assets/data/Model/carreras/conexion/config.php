<?php 
if($_SERVER['SERVER_NAME'] == 'localhost'){
	define('BASE_SERVER', "http://{$_SERVER['SERVER_NAME']}/moni/");
}elseif($_SERVER['SERVER_NAME'] == 'sandbox.conacon.org'){
	define('BASE_SERVER', "http://{$_SERVER['SERVER_NAME']}/");
}elseif($_SERVER['SERVER_NAME'] == 'conacon.org'){
	define('BASE_SERVER', "https://{$_SERVER['SERVER_NAME']}/moni/");
}

echo BASE_SERVER;
?>