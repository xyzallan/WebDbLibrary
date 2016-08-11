<?php

global $fm_GenTabClass;
global $db;

$db->set_paring($fm_GenTabClass->GenTab_SQLP);
$gt_par = $db->execute();

if(strlen($fm_GenTabClass->GenTab_HEAD)>0):
?>
<H1><?=$fm_GenTabClass->GenTab_HEAD?></H1>
<?php
endif;

if($fm_GenTabClass->GenTab_READ[tbltparTableDir] == "vert")
{

	GeneVertTableForm( 
		$gt_par , 
		$fm_GenTabClass->GenTab_COLS, 
		$fm_GenTabClass->GenTab_HFIX, 
		$fm_GenTabClass->GenTab_VIEW, 
		$fm_GenTabClass->GenTab_NAME, 
		$fm_GenTabClass->GenTab_THUL,
		$fm_GenTabClass->GenTab_READ,
		$fm_GenTabClass->GenTab_EMPY,
		$fm_GenTabClass->GenTab_BY
	);
    
}

if($fm_GenTabClass->GenTab_READ[tbltparTableDir] == "hori")
{
	GeneHoriTableForm( 
		$gt_par , 
		$fm_GenTabClass->GenTab_COLS, 
		$fm_GenTabClass->GenTab_HFIX, 
		$fm_GenTabClass->GenTab_NAME, 
		$fm_GenTabClass->GenTab_READ,
		$fm_GenTabClass->GenTab_READ[tbltparColumnCount]
	);
}
