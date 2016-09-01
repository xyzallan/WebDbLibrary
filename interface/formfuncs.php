<?php

/**
 * GeoPort()
 * 
 * @param mixed $ip
 * @param mixed $pl
 * @return
 */
 
function GeoPort($ip, $pl)
{
	$tulem = "";
	if($ip > 21 & $ip<29 & $pl > 57 & $pl < 60)
	{
		$tulem =  sprintf("<a href=\"http://geoportaal.maaamet.ee/url/xgis-latlon.php?lat=%f&lon=%f&out=xgis\" target=\"xgis\">Vaata</a>", $pl, $ip);

//	http://geoportaal.maaamet.ee/url/xgis-latlon.php?lat=58.2532694444&lon=26.4598111111&out=xgis
	}
	return $tulem;
}

function GeoPortMuld($ip, $pl)
{
    $tulem =  sprintf("<a href=\"http://xgis.maaamet.ee/xGIS/XGis?app_id=UU38&user_id=at&punkt=%f,%f&zoom=2000\" target=\"xgis\">Vaata</a>", $pl, $ip);

	return $tulem;
}


function GeoPortAd($ip, $pl)
{

	$tulem = "";
	if($ip > 21 & $ip<29 & $pl > 57 & $pl < 60)
	{
		$tulem =  sprintf("<a href=\"http://geoportaal.maaamet.ee/url/xgis-latlon.php?lat=%f&lon=%f&out=pikkaadress\" target=\"xgis_ad\">Vaata</a>", $pl, $ip);

	}
	return $tulem;
    
    
}

function KonvKoord($ip, $pl)
{
	return sprintf("<input type='submit' onclick='MuudaKoord(%d,%d)' value='WGS'>",$ip,$pl);
}


if(!function_exists("d2dms"))
{
	/**
	 * d2dms()
	 * 
	 * @param mixed $degr
	 * @return
	 */
     
	function d2dms($degr)
	{
		$kr = floor($degr);
		$mn = floor(($degr - $kr)*60);
		$sk = round(($degr - ($kr + $mn/60))*3600,0);
		return substr("00".$kr,-2,2) . "'" . substr("00".$mn,-2,2) . "'" . substr("00".$sk,-2,2);
	}
}


function wgs2lest1($ip_kr, $pl_kr)
{
//para _pohja, _itta, _kumb

$ip = deg2rad($ip_kr);
$pl = deg2rad($pl_kr);

// sisestatakse sellistes formaatides, millest esimene on kraadised ja teine variant on radiaanides
// GPS failis on andmed radiaanides
// kummas formaadis tahetakse sisestada, selle eest vĆµtta "&&" mĆ¤rgid ja sisestada sinna koordinaadid

//&&_Pohja = "58-12-10"
//&&_Itta = "26-10-15" 
//&&_Pohja = "+1.0153915506E+00" XX
//&&_Itta = "4.5723850505E-01" YY

$L0 = 0.41887902;
$e  = 0.081819191;
$a  = 6378137;
$F  = 1.798847851;
$P0 = 4020205.479;
$N  = 0.854175858;


$t1peal = (1 - sin($pl)) / (1 + sin($pl));
$t2peal = (1 + sin($pl) * $e) / (1 - sin($pl) * $e);

$t = sqrt($t1peal * pow($t2peal,$e));

$P = $a * $F * pow($t,$N);
$Q = $N*($ip - $L0);
$YY = $P0 - $P * cos($Q) + 6375000;
$XX = $P * sin($Q) + 500000;

return array(sprintf("%6.0f", $XX), sprintf("%7.0f",$YY));
}

function lest2wgs($x=null, $y=null)
{
    
    $Wgs = array(array(22,58), array(29,60));
    $Lest = array(wgs2lest1($Wgs[0][0],$Wgs[0][1]), wgs2lest1($Wgs[1][0],$Wgs[1][1]));
    
    if($x != null){
        for ($i = 1; $i <= 6; $i++) 
        {
            $IPz = $Wgs[0][0] + ($Wgs[1][0] - $Wgs[0][0])/($Lest[1][0] - $Lest[0][0])*($x-$Lest[0][0]);
            $PLz = $Wgs[0][1] + ($Wgs[1][1] - $Wgs[0][1])/($Lest[1][1] - $Lest[0][1])*($y-$Lest[0][1]);
            $Koef = pow(10,-$i);
            $Wgs = array(array($IPz - $Koef, $PLz - $Koef), array($IPz + $Koef, $PLz + $Koef));
            $Lest = array(wgs2lest1($Wgs[0][0],$Wgs[0][1]), wgs2lest1($Wgs[1][0],$Wgs[1][1]));
        }
    }
    
    
    return(array(round($IPz,7),round($PLz,7)));
    
}        

/**
 * MetReg()
 * 
 * @param mixed $ip
 * @param mixed $pl
 * @return
 */
function MetReg($ip, $pl)
{
	$tulem = "";
	if($ip > 20 & $ip<28 & $pl > 57 & $pl < 60)
	{
		$ip_str = d2dms($ip);
		$pl_str = d2dms($pl);
                list($ip_lest, $pl_lest) = wgs2lest1($ip, $pl);
		
                //$tulem = "<a href=\"http://mets.keskkonnainfo.ee/?koordinaat="   . $pl_lest . ":". $ip_lest . "\" target='MR'>Vaata</a>";
                $tulem = "$pl_lest : $ip_lest";
	}
	return $tulem;
}




/**
 * Num2Text()
 * 
 * @param mixed $Number
 * @return
 */
function Num2Text($Number, $Komakohti = null)
{
	global $time_start;
//	echo "\n<!-- Aeg, Num2Text:" . (microtime_float() - $time_start) . " -->\n";

	$Nr = sprintf("%f", $Number);
	if(!is_null($Komakohti))
	{
		$koma = $Komakohti;
	}
	else
	{
		$koma = strpos(strrev($Number),".");
	}
	return @number_format($Nr, $koma, ","," ");

}




/**
 * spf_TB()
 * 
 * @param mixed $value
 * @param mixed $name
 * @param mixed $oigus
 * @param string $style
 * @param integer $max
 * @param string $tbl_name
 * @param integer $row_id
 * @return
 */
function spf_TB($Value, $Name, $Table = '', $RowID = 0, $oigus=0, $style='', $max=5, $FFUlName='', $FFUlValue='')
{
    $tekst = stripslashes($Value);

    if($oigus == 1)
        { 
        $pikkus = max(min(strlen($Value), 40),$max);
		$valja_tyyp = substr($Name, 2,1);

		$TextBox = new InputTextBox($Name, $Table, substr($Name, 4), $RowID, $valja_tyyp, stripslashes($Value), 'update');
		$TextBox->set_size($pikkus);
		if($RowID>0)
			$tekst = $TextBox->get_Input();
		else
			$tekst = $TextBox->get_InputInsert($FFUlName, $FFUlValue);

	} 
		else
	{
		//echo $tekst; 
	}
   
    return $tekst;
}



function spf_PW($value, $name, $tbl_name = '', $row_id = 0, $oigus=0, $style='', $max=5, $FFUlName='', $FFUlValue='')
{
    $tekst = stripslashes($value);
    if($oigus == 1)
    { 
        $uid = substr(md5(rand()),0,20);
        $pikkus = max(min(strlen($value), 40),$max);
	$UDfunc = sprintf("onchange=\"SendSaveData(this.value,'%s','%s', %d, '%s','update')\"", $name, $tbl_name, $row_id, $uid);

        $tekst = sprintf("\n\t<input id=\"%s\" type=\"password\" name=\"%s\" value=\"%s\" size='%s' style='%s' autocomplete=off %s />\n", 
			$uid, $name, $name, stripslashes($value), $pikkus, $style, $UDfunc);
    }
   
    return $tekst;
}

/**
 * spf_ML()
 * 
 * @param mixed $sisu
 * @param mixed $nimi
 * @param mixed $oigus
 * @param integer $max
 * @param integer $ridu
 * @return
 */
function spf_ML($Value, $Name, $Table = '', $RowID = 0, $oigus=0, $max=5, $ridu=1, $FFUlName='', $FFUlValue='')
{
    if($oigus == 1)
    { 
        $pikkus = max(min(strlen($Value), 40),$max);

		$TextBox = new TextAreaTextBox($Name, $Table, substr($Name, 4), $RowID, 'c', stripslashes($Value));
		$TextBox->set_rows($ridu);
		$TextBox->set_cols($pikkus);
		if($RowID>0)
			$tekst = $TextBox->get_Input();
		else
			$tekst = $TextBox->get_InputInsert($FFUlName, $FFUlValue);


    }
    else
    {
        $tekst = strtr(stripslashes($Value), array("\n"=>"<br>"));
    }
      return $tekst;

}

/*

*/


/**
 * spf_SelO()
 * 
 * @param mixed $value
 * @param mixed $name
 * @param mixed $table
 * @param mixed $order
 * @return
 */
function spf_SelO($Value, $Name, $Table = '', $RowID = 0, $src_table='', $order='', $idcol='id', $namecol='nimi', $FFUlName='', $FFUlValue='', $KirjuOigus = 0)
{
    $uid = substr(md5(rand()),0,20);
    PrintArray($namecol, '$namecol');
	$TextBox = new SelectTextBox($Name, $Table, substr($Name, 4), $RowID, 'c', stripslashes($Value));
	$TextBox->set_selnames($idcol, $namecol);
	$TextBox->set_select($src_table, $order);

	if($RowID>0)
		$tekst = $TextBox->get_Input();
	else
		$tekst = $TextBox->get_InputInsert($FFUlName, $FFUlValue);

   if($KirjuOigus):
        return $tekst;
   else:
        return $TextBox->SingleText;
   endif;
}

/**
 * spf_SelO()
 * 
 * @param mixed $value
 * @param mixed $name
 * @param mixed $table
 * @param mixed $order
 * @return
 */

function spf_SelOg($Value, $Name, $Table = '', $RowID = 0, $src_table='', $order='', $idcol='id', $namecol='nimi', $FFUlName='', $FFUlValue='', $KirjuOigus = 0, $group)
{
	$uid = substr(md5(rand()),0,20);
    
	$TextBox = new SelectGroupTextBox($Name, $Table, substr($Name, 4), $RowID, 'c', stripslashes($Value));
	$TextBox->set_selnames($idcol, $namecol);
	$TextBox->set_select($src_table, $order, $group);

	if($RowID>0)
		$tekst = $TextBox->get_Input();
	else
		$tekst = $TextBox->get_InputInsert($FFUlName, $FFUlValue);

   if($KirjuOigus):
        return $tekst;
   else:
        return $TextBox->SingleText;
   endif;
}

/**
 * spf_SelF()
 * 
 * @param mixed $value
 * @param mixed $name
 * @param mixed $table
 * @param mixed $order
 * @param mixed $filter
 * @return
 */
function spf_SelF($Value, $Name, $Table = '', $RowID = 0, $src_table='', $order='', $filter='', $idcol='id', $namecol='nimi', $FFUlName='', $FFUlValue='', $KirjuOigus = 0)
{

	
    $uid = substr(md5(rand()),0,20);
    
	$TextBox = new SelectTextBox($Name, $Table, substr($Name, 4), $RowID, 'c', stripslashes($Value));
//	$TextBox = new SelectFiltTextBox($Name, $Table, substr($Name, 4), $RowID, 'c', stripslashes($Value));
	$TextBox->set_selnames($idcol, $namecol);
	$TextBox->set_select($src_table, $order, $filter);

	if($RowID>0)
		$tekst = $TextBox->get_Input();
	else
		$tekst = $TextBox->get_InputInsert($FFUlName, $FFUlValue);

   if($KirjuOigus):
        return $tekst;
   else:
        return $TextBox->SingleText;
   endif;

	
}


function spf_SB($cell, $Row)
{
	$idname = Get_Value($Row, $cell['param']);
	
	printf("<input type='submit' value='%s' onClick='%s(\"%s\")'>", Get_Value($cell, 'text'), Get_Value($cell, 'fn'), $idname);
}

function spf_CB($Value, $Name, $Table = '', $RowID = 0, $KirjuOigus=0, $FFUlName='', $FFUlValue='')
{

    $tekst = $Value;

    $valja_tyyp = substr($Name, 2,1);

    $TextBox = new CheckBox($Name, $Table, substr($Name, 4), $RowID, 'n', stripslashes($Value), 'update');
    
    //if($RowID>0):
//        $tekst = $TextBox->get_Input();
    //else:
//        $tekst = "";
//    endif;   

	if($RowID>0)
		$tekst = $TextBox->get_Input();
	else
		$tekst = $TextBox->get_InputInsert($FFUlName, $FFUlValue);
		
		
    if($KirjuOigus) return $tekst ;
    else  return ($Value ? Get_GlobalStr('strYes') : Get_GlobalStr('strNo')) ;
   
}

?>
