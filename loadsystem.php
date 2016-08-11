<?php

/**
 * Short description for file
 * @ Author      : Allan Sims
 * @ Package     : WebIS_basesystem
 * @ Last modify : 18.05.2012
*/

global $ISConfig;

if(!isset($ISConfig->Test))
{
    die("Wrong path!");
}

session_start();

if(array_key_exists('SysAdmin', $_SESSION)):
    if($_SESSION['SysAdmin']):
        
    //error_reporting(E_ALL);
    //ini_set('display_errors', 1);
    endif;
endif;


function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


global $time_start;
global $fm_GenTabClass;
global $db;

global $Page_Conf_Array;
global $Page_Cont_Row;
global $Page_Array;

global $Language;

global $MenuKirjed;

$time_start = microtime_float();

$Page_Conf_Array = array();
$Page_Cont_Row = array();
$Page_Array = array();


ob_implicit_flush(true);

ini_set('session.bug_compat_42',0);
ini_set('session.bug_compat_warn',0);

LoadLibrary("database." . $ISConfig->dbtype, LPath);

if(class_exists('is_data'))
{
    $db = new is_data();
}
else
{
    die();
}

LoadLibrary("forms.getpost", LPath);
LoadLibrary("configure.constants", LPath);

LoadLibrary("classes.formarray", LPath);
LoadLibrary("configure.sessvars", LPath);
LoadLibrary("forms.hrefs", LPath);
LoadLibrary("forms.spfunctions", LPath);
LoadLibrary("classes.formelems", LPath);

LoadLibrary("interface.form_table", LPath);
LoadLibrary("database.struktuur", LPath);

$Language = Get_Value($_SESSION,'Language','et');

LoadLibrary("interface.getstrings", LPath);
LoadLibrary("interface.checkrights", LPath);
LoadLibrary("configure.checklogin", LPath);
LoadLibrary("interface.gentabclass", LPath);
LoadLibrary("modules.array_kaart", LPath);

Kontrolli_Login();

$fm_GenTabClass = new GenTabClass();

LoadLibrary("interface.pageconf", LPath);
LoadLibrary("interface.pageload", LPath);

// see on vajalik kaardiandmete muutmiseks
include_once LPath . "/classes/geoPHP/geoPHP.inc"; 
