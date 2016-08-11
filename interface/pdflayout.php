<?php
ob_start();

global $Page_Cont_Row;
global $Page_Conf_Array;
global $ISConfig;
global $db;

$Dokey = Get_Get('do');
$Page_Cont_Row = Get_Value($Page_Conf_Array, $Dokey);

global $pdf;
$pdf = new MYPDF();

$pdf->LaeFondid();

$pdf->SetCreator("");
$pdf->SetAuthor('Allan Sims');
$pdf->SetTitle(($ISConfig->Title));
$pdf->SetSubject('');
$pdf->SetKeywords('');


$Lndskp = Get_Value($Page_Cont_Row["tblt"], pdf_Landscape, 0);
$PrnYldNo = Get_Value($Page_Cont_Row["tblt"], pdf_PrintLevel, 0);

$Suund = $Lndskp ? "L" : "P";
$Korgus = $Lndskp ? 210 : 297;
$Laius = $Lndskp ? 297 : 210;


$TblSuund = Get_Value($Page_Cont_Row["tblt"], tbltparTableDir);


$pdf->SetMargins(20, 20, 20, true);
$pdf->AddPage($Suund, "A4");

$pdf->SetFont($pdf->_FontPealkiri, '', 12);
//$pdf->Cell(0,10,'', 0, 1);
$pdf->Write(0, html_entity_decode(($Page_Cont_Row["head"]))  , '', 0, 'C', 1, 0, false, false, 0);


/*
 * Teeb päringu
 */
$db->set_paring($Page_Cont_Row["csql"]);
$Read = $db->execute();


/* Seadistab üldised asjad*/
$pdf->SetFont($pdf->_FontTavatekst, '', 8);
//$pdf->Cell(0,10,'', 0, 1);

$THTulp = array();
$PrnTulp = array();

$TulbaNr = 0;

if(isset($Page_Cont_Row["thul"])):
    if(is_array($Page_Cont_Row["thul"])):
        $THUL = $Page_Cont_Row["thul"];

    
    foreach($THUL as $key=>$value)
    {
        $THTulp["$key"] = 0;
        for($i = 0; $i<$value; $i++)
        {
            $TpNimi = array_keys($Page_Cont_Row["cols"])[$TulbaNr];
            $TpIse = $Page_Cont_Row["cols"]["$TpNimi"];
            if(Get_Value($TpIse, pdf_NoPrint, 0) == 0):
                
                $THTulp["$key"] += Get_Value($TpIse, pdf_ColWidth, 12);
                
            endif;
            $TulbaNr++;
        }
        
    }
    
    endif;
endif;


function TeeSummTyhjaks($PrnTulp)
{
    $TulpSummad = array();
    foreach($PrnTulp as $key=>$value)
    {
        $TulpSummad["$key"] = 0;
    } 
    return $TulpSummad;
}

$KoguLaius = 0;

foreach($Page_Cont_Row["cols"] as $key=>$value):

	$TulbaNr++;
    
    if(Get_Value($value, pdf_NoPrint, 0) == 0):
        
        $PrnTulp["$key"] = $value;
        $PrnTulp["$key"][pdf_ColWidth] = Get_Value($value, pdf_ColWidth, 12); // ? Get_Value($value, 'width') : 12;
        $KoguLaius += $PrnTulp["$key"][pdf_ColWidth];
    endif;
    
endforeach;



if($KoguLaius < ($Laius - 40)):
    $lm = ($Laius-$KoguLaius)/2;
    $pdf->SetMargins($lm, 20, 20, true);
endif;

$pdf->Cell(0,2,'', 0, 1);

$TulpSummad = TeeSummTyhjaks($PrnTulp);

$PaiseJoon = array(
	'L' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150)),
	'T' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
	'B' => array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)),
	'R' => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150))
	);

$TabelJoon = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150));
$pdf->SetLineStyle($TabelJoon);


function MoodustaPais($THTulp, $PrnTulp, $PaiseJoon)
{
global $pdf;
	
	if(isset($THTulp)):
		$Tulp = 0;

		foreach($THTulp as $key=>$value)
		{
			$Tulp++;
			$RV = $Tulp == count($THTulp) ? 1 : 0;
			$Pikkus = $value;
			$pdf->Cell($Pikkus, 5, html_entity_decode(($key)), $PaiseJoon, $RV, 'C', 0, '', 0);
		}
	endif;

	$Tulp = 0;
	foreach($PrnTulp as $key=>$value)
	{
		$Tulp++;
		$RV = ($Tulp == count($PrnTulp)) ? 1 : 0;
		$Pikkus = Get_Value($value, pdf_ColWidth, 0);
		$pdf->Cell($Pikkus, 5, html_entity_decode((Get_Value($value, parsFieldDescr))), $PaiseJoon, $RV, 'C', 0, '', 0);
	}	
	
}

function MoodustaSummaRida($PrnTulp, $TulpSummad, $PaiseJoon)
{
global $pdf;
	$Tulp = 0;
	//$pdf->SetFillColor(100,100,100);
	foreach($PrnTulp as $key=>$value)
	{
		$Tulp++;
		$RV = $Tulp == count($PrnTulp) ? 1 : 0;
		$Vaartus = $TulpSummad["$key"];
		$TulpTyyp = Get_Value($value, parsFieldType);
		$Koma = Get_Value($value, 'koma', '0');
		$Sepa = Get_Value($value, parsNumSep, ' ');

		$Vaartus = number_format($Vaartus, $Koma, ',', $Sepa);    

		$Pikkus = Get_Value($value, pdf_ColWidth, 12);
		$Joond = $TulpTyyp == "c" ? "L" : "R";

		if(is_array(Get_Value($value, 'keskmine')))
		{
			$Jagamine = Get_Value($value, 'keskmine');
			$UL = Get_Value($TulpSummad, $Jagamine['nom']);
			$AL = Get_Value($TulpSummad, $Jagamine['den']);
			$KO = Get_Value($Jagamine,'kordaja', 1);
			$Koma = Get_Value($value, 'koma', 0);
			$KE = round($UL/$AL*$KO, $Koma);
			$Vaartus = number_format($KE, $Koma, ',', $Sepa);
		}
		
		
		if(Get_Value($value, tbltparSumRow, 0)):
			$pdf->Cell($Pikkus, 5, $Vaartus , $PaiseJoon, $RV, $Joond, 0, '', 0);
		else:    
			$pdf->Cell($Pikkus, 5, "" , $PaiseJoon, $RV, $Joond, 0, '', 0);
		endif;
	}  	
}

MoodustaPais($THTulp, $PrnTulp, $PaiseJoon);


$by = null;

$byTulp = Get_Value($Page_Cont_Row["tblt"], tbltparRowsBy, false);


while($Rida = ab_fetch_array($Read))
{

	/* Grupeeritud tabelis grupi summa kirjutamine */
	if($byTulp):
        $curBy = Get_Value($Rida, $byTulp);

        if($by == null):
            $by = $curBy;
        endif;
    
        if($curBy != $by):

			MoodustaSummaRida($PrnTulp, $TulpSummad, $PaiseJoon);
			$TulpSummad = TeeSummTyhjaks($PrnTulp);

        endif;        
		$by = $curBy;
    endif;        
    
    if($pdf->GetY()> ($Korgus - 30))
    {
        $pdf->AddPage($Suund, "A4");

		MoodustaPais($THTulp, $PrnTulp, $PaiseJoon);
    }
    
    $Tulp = 0;
    foreach($PrnTulp as $key=>$value)
    {
        $Tulp++;
        $RV = $Tulp == count($PrnTulp) ? 1 : 0;
        $Vaartus = Get_Value($Rida, $key);
        $TulpTyyp = Get_Value($value, parsFieldType);
        $Koma = Get_Value($value, 'koma', '0');
		$Sepa = Get_Value($value, parsNumSep, ' ');
		
        if($TulpTyyp == 'n'):
            if(Get_Value($value, tbltparSumRow, 0)):
                $TulpSummad["$key"] += $Vaartus;
            endif;

            
            if($Vaartus+0 == 0):
                $Vaartus = '';
            else:
                $Vaartus = number_format($Vaartus, $Koma, ',', $Sepa);
            endif;
		endif;
		
        if($TulpTyyp == 'd'):
            if(!is_null($Vaartus)):
				list($yy, $mm, $dd) = explode('-', $Vaartus);
				$Vaartus = sprintf("%02d.%02d.%04d", $dd, $mm, $yy);
			endif;
		endif;
        $Cell = Get_Value($value, 'cell', '');
        if(isset($Cell[parsFieldType])):
            if(isset($Cell['tbl'])):
				if($Cell['tbl'] != ''):
					$Tabel = $Cell['tbl'];
					$IDcol = Get_Value($Cell,'idcol', 'id');
					if(in_array(Get_Value($value, parsFieldType), array('n','i'))):
						$db->set_paring(sprintf("select * from %s where %s='%d'", $Tabel, $IDcol, $Vaartus));
					else:
						$db->set_paring(sprintf("select * from %s where %s='%s'", $Tabel, $IDcol, $Vaartus));
					endif;
					$SelOtul = ab_fetch_array($db->execute());
					$Vaartus = Get_Value($SelOtul, 'nimi');
					$TulpTyyp = "c";
				endif;
			endif;
        endif;
        
        $Pikkus = Get_Value($value, pdf_ColWidth, 12);
        $Joond = $TulpTyyp == "c" ? "L" : "R";
        $pdf->Cell($Pikkus, 5, html_entity_decode(($Vaartus)) , 'RLB', $RV, $Joond, 0, '', 0);
    }

}

MoodustaSummaRida($PrnTulp, $TulpSummad, $PaiseJoon);


ob_end_clean();
$pdf->Output($Dokey . '.pdf', 'I');
unset($pdf);