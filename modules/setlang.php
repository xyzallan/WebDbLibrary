<?php

session_start();

if(array_key_exists('lang', $_REQUEST))
{
	$_SESSION['Language'] = $_REQUEST['lang'];
}

?>