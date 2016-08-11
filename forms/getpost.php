<?php

function addslashesx($tekst)
{
    if(is_string($tekst)):
        $tekst = addslashes($tekst);
    endif;
        
    return $tekst;
}

function Get_Value($array,$key,$res='')
{
	if(is_array($array))
	{
		if(array_key_exists($key,$array))
		{
			$res = $array[$key];
		}
	}
	return ($res);
}


function Get_GlobalStr($key)
{
	if(array_key_exists($key,$GLOBALS))
	{
		$res = $GLOBALS[$key];
	} 
    else
	{
		$res = $key;
	}
	return $res;
}


function Get_Post($key)
{
	$res = '';
	if(array_key_exists($key,$_POST))
	{
		$res = $_POST[$key];
	}
	return addslashesx($res);
}

function Get_Get($key)
{
	$res = '';
	if(array_key_exists($key, $_GET))
	{
		$res = $_GET[$key];
	}
	return addslashesx($res);
}


function Check_Post($key,$value)
{
	$res = false;

	if(array_key_exists($key,$_POST))
	{
		if($_POST[$key] == $value)
		{
			$res = true;	
		}
	}
	return $res;
}


function Check_Get($key,$value)
{
	$res = false;

	if(array_key_exists($key,$_GET))
	{
		if($_GET[$key] == $value)
		{
			$res = true;	
		}
	}
	return $res;
}

function numb($text)
{
    return strtr($text, array(" " => "", ","=>"."));
}

function udnr($tekst)
{
	$tekst1 = strtr($tekst, array(" " => "", ","=>"."));
	return $tekst1;
}


function Numb2Text($text)
{
    return strtr($text, "." , ",");
}

function PrintArray($massiiv, $name='')
{
    if(defined('PrintSource')):
		if(Get_Value($_SESSION, 'SysAdmin')):
			echo "\n<!-- $name: ";

			print_r($massiiv);
			echo "-->\n";
		endif;
	endif;
    
}

function get_web_page( $url )
{
	$options = array(
		CURLOPT_RETURNTRANSFER => true,     // return web page
		CURLOPT_HEADER         => false,    // don't return headers
		CURLOPT_FOLLOWLOCATION => false,     // follow redirects
		CURLOPT_ENCODING       => "",       // handle all encodings
		CURLOPT_AUTOREFERER    => true,     // set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
		CURLOPT_TIMEOUT        => 120,      // timeout on response
		CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT']
	);

	$ch      = curl_init( $url );
	curl_setopt_array( $ch, $options );
	$content = curl_exec( $ch );
	$err     = curl_errno( $ch );
	$errmsg  = curl_error( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );

	$header['errno']   = $err;
	$header['errmsg']  = $errmsg;
	$header['content'] = $content;
	return $content;
	//return iconv("UTF-8", "ISO-8859-13//IGNORE", $content);
}
