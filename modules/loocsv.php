<?php

/**************************************************************************************************************/
function CreaArray($buff)
{

echo "\n";
$Read = explode("\n", $buff);
$Pais = explode(";", strtolower(str_replace('"', '', substr($Read[0], 0, -1))));

$Pikk = count($Read);

$Tulbad = array();
$Tulp = array();
for($i = 1; $i<$Pikk - 1; $i++)
{

	$xRida = explode(";", str_replace('"', '', substr($Read[$i], 0, -1)));
	$Nr = 0;
	for($xT = 0; $xT < count($xRida); $xT++)
		{
			$Tulp[$Pais[$Nr]] = $xRida[$Nr];
			$Nr++;
		}
$Tulbad["Rida" . $i] = $Tulp;
}



return $Tulbad;

}

/**************************************************************************************************************/

function LooParing($Rida, $xTulbad, $tabel)
{

$sql = "";
$parx = "";

//print_r($Rida);
//print_r($xTulbad);

foreach($xTulbad as $Tulp=>$Vorming)
	{
		//print_r($Vorming);
		$parx .= $Vorming["dbtulp"] . ", ";
		$sql .= sprintf($Vorming["Vorming"] . ", ", $Rida[$Tulp]);
	}
return "insert into " . $tabel . " (" . substr($parx, 0, -2) . ") values (" . substr($sql, 0, -2) . ")";
}



/***************************************************************************************************************/

function csv_eraldis($Buffer)
{
global $db;


$Massiiv = CreaArray($Buffer);

$xTulbad = array(
		"invent_kpv"      =>array("Vorming"=>"'%s'","dbtulp"=>"invent_kuup"),
		"eraldise_nr"     =>array("Vorming"=>'%d'  ,"dbtulp"=>"eraldis"),
		"katastri_nr"     =>array("Vorming"=>"'%s'","dbtulp"=>"katastrinumber"),
		"pindala"         =>array("Vorming"=>"%f"  ,"dbtulp"=>"pindala"),
		"kaitse_pind"     =>array("Vorming"=>"%f"  ,"dbtulp"=>"kaitse_pind"),
		"kaitsepohj_kood" =>array("Vorming"=>"'%s'","dbtulp"=>"kaitsepohjus"),
		"korgus"          =>array("Vorming"=>"%f"  ,"dbtulp"=>"h100"),
		"kuivendatud"     =>array("Vorming"=>"%f"  ,"dbtulp"=>"kuivendus"),
		"juurdekasv"      =>array("Vorming"=>"%f"  ,"dbtulp"=>"juurdekasv"),
		"rpindala_1"      =>array("Vorming"=>"%f"  ,"dbtulp"=>"a_g1"),
		"rpindala_2"      =>array("Vorming"=>"%f"  ,"dbtulp"=>"a_g2"),
		"taius_1"         =>array("Vorming"=>"%d"  ,"dbtulp"=>"a_t1"),
		"taius_2"         =>array("Vorming"=>"%d"  ,"dbtulp"=>"a_t2"),
		"tagavara_1"      =>array("Vorming"=>"%d"  ,"dbtulp"=>"a_v1"),
		"tagavara_2"      =>array("Vorming"=>"%d"  ,"dbtulp"=>"a_v2"),
		"tagavara_y"      =>array("Vorming"=>"%d"  ,"dbtulp"=>"a_vy"),
		"tagavara_s"      =>array("Vorming"=>"%d"  ,"dbtulp"=>"a_vs"),
		"tagavara_l"      =>array("Vorming"=>"%d"  ,"dbtulp"=>"a_vl"),
		"kolviku_kood"    =>array("Vorming"=>"'%s'","dbtulp"=>"kolviku_kood"),
		"kasvukoha_kood"  =>array("Vorming"=>"'%s'","dbtulp"=>"kasvukoht"),
		"boniteedi_kood"  =>array("Vorming"=>"'%s'","dbtulp"=>"boniteet"),
		"arengukl_kood"   =>array("Vorming"=>"'%s'","dbtulp"=>"arenguklass"),
		"tuleohukl_kood"  =>array("Vorming"=>"'%s'","dbtulp"=>"tuleohuklass"),
		"kvartal_id"      =>array("Vorming"=>"%d"  ,"dbtulp"=>"kvartal_id"),
		"kvkorrid_id"     =>array("Vorming"=>"%d"  ,"dbtulp"=>"kvkorrid_id")
	);

/*

ID
YKSUS_ID
INVENT_KPV
KATASTRI_NR
KVARTALI_NR
ERALDISE_NR
PINDALA
KUIVENDATUD
KORGUS
KORRALDAJA_ID
TAGAVARA
TAKSEERIJA_ID
JUURDEKASV
INVENT_VIISI_KOOD
TAIUS_1
TAIUS_2
RPINDALA_1
RPINDALA_2
TAGAVARA_1
TAGAVARA_2
TAGAVARA_Y
TAGAVARA_S
TAGAVARA_L
KOLVIKU_KOOD
KAITSEPOHJ_KOOD
KASVUKOHA_KOOD
BONITEEDI_KOOD
TULEOHUKL_KOOD
ARENGUKL_KOOD
MKOOD
VKOOD
MARKUS
KAITSE_PIND
*/

$VanaID = array();
//$ID = 0;

$KasIDolemas = 0;

foreach($Massiiv as $Rida)
{
	$KvNr = $Rida["kvartali_nr"];

	$db->set_paring(sprintf("select * from kvartal where number='%s'",$KvNr));
	$KvID = ab_fetch_array($db->execute());
	$Rida["kvartal_id"] = Get_Value($KvID, "id");

	$Rida["tagavara_1"] = $Rida["tagavara_1"] / $Rida["pindala"];
	$Rida["tagavara_2"] = $Rida["tagavara_2"] / $Rida["pindala"];
	$Rida["tagavara_y"] = $Rida["tagavara_y"] / $Rida["pindala"];
	$Rida["tagavara_s"] = $Rida["tagavara_s"] / $Rida["pindala"];
	$Rida["tagavara_l"] = $Rida["tagavara_l"] / $Rida["pindala"];
	
	if($KasIDolemas == 0){
		$Par = sprintf("insert into kvkorrid (kvartal_id, aasta, kehtiv) values (%d, 2011, 1)", $Rida["kvartal_id"] );
		$db->set_paring($Par);
		$KvKorrID = $db->executeID();
		$KasIDolemas = 1;
	}

	$Rida["kvkorrid_id"] = $KvKorrID;

	$Paring = LooParing($Rida, $xTulbad, "eraldis");	
	$db->set_paring($Paring);
	$ID = $db->executeID();
	//echo $Paring."\n";
	//$ID++;
	$VanaID["ID" . $Rida["id"]] = $ID;
}

/*
echo "<pre>";
print_r($Rida);
echo "</pre>";
*/



return $VanaID;
}


/**************************************************************************************************************/
function csv_element($Buffer, $Uid)
{

global $db;


// "ERALDIS_ID";"RINDE_KOOD";"PUULIIGI_KOOD";"PARITOLU_KOOD";"VANUS";"AASTA";"KORGUS";"DIAMEETER";"ARV";"TAGAVARA";"RAIE_PROTSENT";"OSAKAAL";"G_SUMMA";"ENAMUS"

$Massiiv = CreaArray($Buffer);

$xTulbad = array(
		"rinde_kood"      =>array("Vorming"=>"'%s'" ,"dbtulp"=>"rinne"),
		"puuliigi_kood"   =>array("Vorming"=>"'%s'" ,"dbtulp"=>"puuliik"),
		"paritolu_kood"   =>array("Vorming"=>"'%s'" ,"dbtulp"=>"paritolu"),
		"vanus"           =>array("Vorming"=>"%d"   ,"dbtulp"=>"vanus"),
		"korgus"          =>array("Vorming"=>"%f"   ,"dbtulp"=>"korgus"),
		"diameeter"       =>array("Vorming"=>"%f"   ,"dbtulp"=>"diameeter"),
		"osakaal"         =>array("Vorming"=>"%d"   ,"dbtulp"=>"osakaal"),
		"raie_protsent"   =>array("Vorming"=>"%d"   ,"dbtulp"=>"raieprots"),
		"arv"             =>array("Vorming"=>"%d"   ,"dbtulp"=>"puudearv"),
		"enamus"          =>array("Vorming"=>"%d"   ,"dbtulp"=>"enamus"),
		"ERID"            =>array("Vorming"=>"%d"   ,"dbtulp"=>"eraldis_id")
	);

$VanaID = array();

foreach($Massiiv as $Rida)
{
	
	$Rida["ERID"] = $Uid["ID" . $Rida["eraldis_id"]];
	$Paring = LooParing($Rida, $xTulbad, "element");	
	//echo $Paring;
	$db->set_paring($Paring);
	$ID = $db->executeID();
	echo "- ID=".$ID."\n";
	$VanaID[$Rida["eraldis_id"] . $Rida["rinde_kood"] . $Rida["puuliigi_kood"]] = $ID;

}

return $VanaID;


}

/***************************************************************************************************************/
function csv_kahjustus($Buffer, $Er_ID, $El_ID)
{
	global $db;

	//"ERALDIS_ID";"PUULIIGI_KOOD";"POHJUSE_KOOD";"OSAKAAL";"ASTME_KOOD";"RINDE_KOOD"

	// 1416;"KU";"10";10;"N";"1"

	$Massiiv = CreaArray($Buffer);

	$xTulbad = array(
			"pohjuse_kood"   =>array("Vorming"=>"'%s'", "dbtulp"=>"pohjusekood"),
			"osakaal"        =>array("Vorming"=>"%d", "dbtulp"=>"osakaal"),
			"astme_kood"     =>array("Vorming"=>"'%s'"  , "dbtulp"=>"astmekood"),
			"ELID"           =>array("Vorming"=>"%d"  , "dbtulp"=>"element_id"),
			"ERID"           =>array("Vorming"=>"%d"  , "dbtulp"=>"eraldis_id")
		);

	foreach($Massiiv as $Rida)
	{
		
		$Rida["ERID"] = $Er_ID["ID" . $Rida["eraldis_id"]];
		$Rida["ELID"] = $El_ID[$Rida["eraldis_id"] . $Rida["rinde_kood"] . $Rida["puuliigi_kood"]];
		$Paring = LooParing($Rida, $xTulbad, "kahjustus");	
		//echo implode(";", $Rida) . "\n";
		//echo $Paring."\n";
		$db->set_paring($Paring);
		$ID = $db->executeID();

	}

}

/***************************************************************************************************************/


function csv_tood($Buffer, $Uid)
{
global $db;

//"Eraldis_ID";"TOO_KOOD";"JARJEKORD";"U_ARV";"PUULIIGI_KOOD";"PINDALA";"AASTA"

// 1416;"KU";"10";10;"N";"1"
$Massiiv = CreaArray($Buffer);


$xTulbad = array(
		"too_kood"       =>array("Vorming"=>"'%s'", "dbtulp"=>"tookood"),
		"jarjekord"      =>array("Vorming"=>"%d", "dbtulp"=>"jarjekord"),
		"u_arv"          =>array("Vorming"=>"%d", "dbtulp"=>"uuend_arv"),
		"puuliigi_kood"  =>array("Vorming"=>"'%s'"  , "dbtulp"=>"puuliik"),
		"aasta"          =>array("Vorming"=>"%d"  , "dbtulp"=>"tehtud_aasta"),
		"pindala"        =>array("Vorming"=>"%f"  , "dbtulp"=>"pindala"),
		"ERID"           =>array("Vorming"=>"%d"  , "dbtulp"=>"eraldis_id")
	);

foreach($Massiiv as $Rida)
{
	
	$Rida["ERID"] = $Uid["ID" . $Rida["eraldis_id"]];
	$Paring = LooParing($Rida, $xTulbad, "too");	
	//echo $Paring."\n";
	$db->set_paring($Paring);
	$ID = $db->executeID();

}


}



/***************************************************************************************************************/
function csv_isearasus($Buffer, $Uid)
{
global $db;

//"ERALDIS_ID";"ISEARASUSE_KOOD"

// 1416;"KU";"10";10;"N";"1"
$Massiiv = CreaArray($Buffer);

//echo $Buffer;

$xTulbad = array(
		"isearasuse_kood"  =>array("Vorming"=>"'%s'", "dbtulp"=>"isearasus"),
		"ERID"             =>array("Vorming"=>"%d"  , "dbtulp"=>"eraldis_id")
	);

	foreach($Massiiv as $Rida)
	{
		
		$Rida["ERID"] = $Uid["ID" . $Rida["eraldis_id"]];
		$Paring = LooParing($Rida, $xTulbad, "isearasus");	
		//echo $Paring."\n";
		$db->set_paring($Paring);
		$ID = $db->executeID();

	}


}

/***************************************************************************************************************/

function csv_mif($bfMid, $bfMif, $Eid)
{


$bf_mid = explode("\n", str_replace('"','', $bfMid));

global $db;
$Read = explode("\n", $bfMif);
$Pikk = count($Read);

$idCount = 0;

for($i = 1; $i<$Pikk - 1; $i++)
{
	$buffer =  substr($Read[$i], 0, -1);
	if(substr($buffer, 0, 6) == "Region")
	{
		$RegCount = substr($buffer, 6, 3) + 0;
		for($P = 0; $P<$RegCount; $P++)
		{
			$i++;
			$buffer =  substr($Read[$i], 0, -1);
			$PolyRidu = $buffer + 0;

			for($r = 0; $r < $PolyRidu; $r++)
			{
				$i++;
				$buffer =  substr($Read[$i], 0, -1);
				$ViimKoord = str_replace(" ", ",", $buffer);
				list($vx, $vy) = explode(",", $ViimKoord);

				$Erid = Get_Value($Eid, substr("ID" . $bf_mid[$idCount],0,-1));

				$paring = "insert into erkaart (eraldis_id, region, regnr, reaid, x, y) values " . 
						sprintf("(%d, %d, %d, %d, %f, %f)\n", $Erid, $RegCount, $P+1, $r, $vx, $vy);
				$db->set_paring($paring);
				$db->execute();
			}
		}

	$idCount++;
	}


}
}
?>
