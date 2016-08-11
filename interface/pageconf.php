<?php

$FilesInMenu = scandir(FPath . '/menuvert/');
foreach($FilesInMenu as $SingFile)
{
    if(substr($SingFile, -4) == '.php')
    {
        LoadLibrary('menuvert.' . substr($SingFile, 0, -4), FPath);
    }
}

global $db;
global $ISConfig;
global $Page_Conf_Array;


foreach($Page_Conf_Array as $moodul=>$level1):
    foreach($level1['tblt'] as $tblt_key=>$tblt_value):
    
        if (strpos($tblt_value,'SESSION_') !== false):
            list($a, $b) = explode('_', $tblt_value);
            $Page_Conf_Array[$moodul]['tblt'][$tblt_key] = Get_Value($_SESSION, $b);
        endif;
        
    endforeach;
endforeach;