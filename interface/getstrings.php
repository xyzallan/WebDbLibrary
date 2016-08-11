<?php
global $db;

$Language = Get_Value($_SESSION,'Language','et');

$db->set_paring("select * from tm_strings");
$Strs = $db->execute();

while($Str = ab_fetch_array($Strs)):
	$Muutuja = Get_Value($Str, 'nimi');
	$Vaartus = Get_Value($Str, $Language);
	global ${$Muutuja};
	${$Muutuja} = $Vaartus;
endwhile;
