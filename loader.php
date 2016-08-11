<?php
/**
* Short description for file
* @ Author      : Allan Sims
 * @ Package     : WebIS_basesystem
 * @ Last modify : 15.10.2012
*/

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}


global $ISConfig;

// Esmalt kontrollime, kas on vaja mingisse kataloogi suunata

if(array_key_exists("go", $_GET))
{
    $suunamine = $_GET["go"];
    $UusAadress = strtr(strtr($ISConfig->www_server. $suunamine, array("//"=>"/", ":/"=>"://")), array("///"=>"//"));
    header("location: " . $UusAadress);
	echo "<!-- $UusAadress -->";
}


/*
Funktsioon, mis laadib igasugu PHP faile sisse
*/
function LoadLibrary($filePath, $FullPath)
{
	$path = str_replace('.', DS , $filePath);
	$File = $FullPath . DS . $path.".php";
	if(file_exists($File))
	{
		try {
			include_once($File);
		} catch (Exception $e) {
			echo 'Mingi viga: ',  $e->getMessage(), "\n";
		}
	}
}


/*
Funktsioon, mis laadib igasugu faile sisse
*/
function SetRootFilePath($filePath, $fileName)
{
    global $ISConfig;
    $Server = (Get_Value($_SERVER,'HTTPS') ? $ISConfig->swww_server : $ISConfig->www_server);

    $path = str_replace('.', DS , $filePath);
    $File = '/' . $path . DS . $fileName;
    return $File;
}

function SetFilePath($filePath, $fileName)
{
    global $ISConfig;
    $Server = (Get_Value($_SERVER,'HTTPS') ? $ISConfig->swww_lib : $ISConfig->www_lib);

    $path = str_replace('.', DS , $filePath);
    $File = $Server . $path . DS . $fileName;
    return $File;
}
