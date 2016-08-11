<?php

$tahti = strlen($_SERVER["DOCUMENT_ROOT"]);
$server = $_SERVER['SERVER_NAME'];

$DirPath = "https://". $server . substr(dirname(__FILE__), $tahti) ;

$JQ_Path = $DirPath . '/jquery-1.11.0.min.js';
$JQ_UI_Path = $DirPath . '/jquery-ui-1.10.3.js';


echo "<script src='$JQ_Path' type='text/javascript'></script>\n";
echo "<script src='$JQ_UI_Path' type='text/javascript'></script>\n";
