<?php

function a_href_moot_yld($id)
{
	return sprintf("<a href=\"?do=mootloend&mid=%d\"  target='MootYld'>Vaata</a>",$id);
}

function a_href_moot_kord($id)
{
	return sprintf("<a href=\"?do=singpuud&mootid=%d\" target='MootKord'>Vaata</a>",$id);
}

function a_href_singprt($id)
{
	return sprintf("<a href=\"?do=singprt&prtid=%d\">Vaata</a>",$id);
}

function a_href_apket($id, $tekst = "Vaata")
{
	return sprintf("<a href=\"?do=ap_ketas&apid=%d\">%s</a>",$id,$tekst);
}

function a_href_apketjk($id, $tekst = "Vaata")
{
	return sprintf("<a href=\"?do=ap_ketas_jk&akid=%d\">%s</a>",$id,$tekst);
}

function PdfYldBlank($mid)
{
	return sprintf("<a href='pdf.php?mid=%d&t=3' target='_blank'>PDF</a>", $mid);
}

function PdfMootBlank($mid)
{
	return sprintf("<a href='pdf.php?mid=%d&t=1' target='_blank'>PDF</a>", $mid);
}

function PdfMootJarg($mid)
{
	return sprintf("<a href='pdf.php?mid=%d&t=4' target='_blank'>PDF</a>", $mid);
}

function PdfMootSkeem($mid)
{
	return sprintf("<a href='pdf.php?mid=%d&t=5' target='_blank'>PDF</a>", $mid);
}


?>