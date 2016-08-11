<?php

function TBL_JahEi()
{
return array(
	parsFieldType => 'CB', 
	'tbl'  => 'tm_jah',
	'ord'  => 'kood',
	'idcol'=>'kood'
	);
}

function TBL_Puuliik()
{
return array(parsFieldType => "SelO",
	"tbl" => "tm_puuliik",
	"ord" => "nimi",
	"idcol" => "kood"
	);
	
}

function TBL_Tooliik()
{
return array(parsFieldType => "SelO",
	"tbl" => "tm_tookood",
	"ord" => "nimi",
	"idcol" => "kood"
	);
	
}


function TBL_Jarjekord()
{
return array(
	parsFieldType => "SelO",
	"tbl" => "tm_jarjekord",
	"ord" => "jk",
	"idcol" => "id"
	);
}