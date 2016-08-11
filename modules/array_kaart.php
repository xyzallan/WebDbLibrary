<?php

function LaePolygonfromDB($tabel, $id, $type)
{
	global $db;
	$db->set_paring(sprintf("select st_astext(kaart_ala) as wkt from %s where id =%d", $tabel, $id));
	$Rida = ab_fetch_array($db->execute());
	$wkb = Get_Value($Rida, 'wkt');
	//echo "\n" . $wkb;
	$polygon = geoPHP::load($wkb, 'wkt');
	return $polygon->out($type);
	
}

function LaeGeofromDB($tabel, $id, $Tulp = 'kaart_ala')
{
	global $db;
	$db->set_paring(sprintf("select st_astext(%s) as wkt from %s where id =%d", $Tulp, $tabel, $id));
	$Rida = ab_fetch_array($db->execute());
	$wkb = Get_Value($Rida, 'wkt');
	//echo "\n" . $wkb;
	$polygon = geoPHP::load($wkb, 'wkt');
	return $polygon;
	
}

function LaeGMLfromDB($tabel, $id, $Tulp = 'kaart_ala')
{
	global $db;
	$db->set_paring(sprintf("select ST_AsGML(%s) as gml from %s where id =%d", $Tulp, $tabel, $id));
	$Rida = ab_fetch_array($db->execute());
	$wkb = Get_Value($Rida, 'gml');

	$gmlobj = "";
	$gmlobj .= "\n<gml:featureMember>";
	$gmlobj .= "<ogr:geometryProperty>";
	$gmlobj .= sprintf("\n\t<ogr:id>%d</ogr:id>", $id);
	$gmlobj .= "\n\t" . $wkb;
	$gmlobj .= "</ogr:geometryProperty>";
	$gmlobj .= "\n</gml:featureMember>";
	
	
	return $gmlobj;
	
}



function geo_lest($north, $east) {

$LAT = ($north*(pi()/180));
$LON = ($east*(pi()/180));
$a = 6378137.000000000000;
$F = 298.257222100883;
$RF = $F;
$F = (1/$F);
$B0 = ((57.000000000000 + 31.000000000000 / 60.000000000000 + 3.194148000000 / 3600.000000000000)*(pi()/180));
$L0 = ((24.000000000000)*(pi()/180));
$FN = 6375000.000000000000;
$FE = 500000.000000000000;
$B1 = ((59.000000000000 + 20.000000000000 / 60.000000000000)*(pi()/180));
$B2 = ((58.000000000000)*(pi()/180));
$xx = ($north - $FN);
$yy = ($east - $FE);
$f1 = (1 / $RF);
$er = ((2.000000000000 * $f1) - ($f1 * $f1));
$e = sqrt($er);
$t1 = sqrt(((1.000000000000 - sin($B1)) / (1.000000000000 + sin($B1))) * (pow(((1.000000000000 + $e * sin($B1)) / (1.000000000000 - $e * sin($B1))), $e)));
$t2 = sqrt(((1.000000000000 - sin($B2)) / (1.000000000000 + sin($B2))) * (pow(((1.000000000000 + $e * sin($B2)) / (1.000000000000 - $e * sin($B2))), $e)));
$t0 = sqrt(((1.000000000000 - sin($B0)) / (1.000000000000 + sin($B0))) * (pow(((1.000000000000 + $e * sin($B0)) / (1.000000000000 - $e * sin($B0))), $e)));
$t = sqrt(((1.000000000000 - sin($LAT)) / (1.000000000000 + sin($LAT))) * (pow(((1.000000000000 + $e * sin($LAT)) / (1.000000000000 - $e * sin($LAT))), $e)));

$m1 = (cos($B1) / (pow((1.000000000000 - $er * sin($B1) * sin($B1)), 0.500000000000)));
$m2 = (cos($B2) / (pow((1.000000000000 - $er * sin($B2) * sin($B2)), 0.500000000000)));
$n = ((log($m1) - log($m2)) / (log($t1) - log($t2)));
$FF = ($m1 / ($n * pow($t1, $n)));
$p0 = ($a * $FF * (pow($t0, $n)));
$FII = ($n * ($LON - $L0));
$p = ($a * $FF * pow($t, $n));
$n = ($p0 - ($p * cos($FII)) + $FN);
$e = ($p * sin($FII) + $FE);

return array($n,$e);

}

function kaart_array($id, $table, $idfield, $offsetx = 0, $offsety = 0, $Koef = 1, $PrnSql = false)
{
	global $db;

$Eraldaja_Grp = '|';
$Eraldaja_XY = 'x';


	$Par_MaxRn = sprintf("select max(regnr) as maxregnr from %s where %s = %d", $table, $idfield, $id) ;
	$db->set_paring($Par_MaxRn);
	$PolyYld = ab_fetch_array($db->execute());


	$SeeKoord = array_fill(0, $PolyYld['maxregnr'], array("Koord"=>"", "Boundary" => ""));

	$Par_Kaart = sprintf("select *, y as yy, x as xx from %s where %s = %d order by regnr, reaid", $table, $idfield, $id);
	if($offsety > 0)
	{
		$Par_Kaart = sprintf("select *, (%f - y)*%f as yy, (x - %f)*%f as xx from %s where %s = %d order by regnr, reaid", $offsety, $Koef, $offsetx, $Koef, $table, $idfield, $id);
	}
	$db->set_paring($Par_Kaart);
	$Row_Resource = $db->execute();

	while($Row = ab_fetch_array($Row_Resource))
	{
		$SeeKoord[($Row['regnr']-1)]["Koord"] .=  sprintf("%06.2f%s%07.2f%s", $Row['xx'],$Eraldaja_XY, $Row['yy'],$Eraldaja_Grp);
	}

	$SeePoly = array();
	$Array_MySQL = array("x"=>" ", "|"=>",");
	$Array_XML   = array("x"=>",", "|"=>" ");

	if($PolyYld['maxregnr'] > 1)
	{
		for($x = 0; $x <$PolyYld['maxregnr']; $x++)
		{
			$Sees = 0;
			$Poly1 = "GeomFromText('Polygon((" . substr(strtr($SeeKoord[$x]["Koord"], $Array_MySQL),0,-1) . "))')"; 
			for($y = 0; $y <$PolyYld['maxregnr']; $y++)
			{
				if($x != $y)
				{
					$Poly2 = "GeomFromText('Polygon((" . substr(strtr($SeeKoord[$y]["Koord"], $Array_MySQL),0,-1) . "))')"; 
					$Kask = sprintf("SELECT MBRWithin(%s,%s) as onsees", $Poly1, $Poly2);
					if($PrnSql)
					{
						echo "<br>" . $Kask;
					}
					$db->set_paring($Kask);
					$Kontroll = ab_fetch_array($db->execute());
					$Sees = $Sees + $Kontroll['onsees'];
				}
				$SeeKoord[$x]["Boundary"] = $Sees ? 'outer' : 'inner';
			}

			if($Sees == 0)
			{
				$SeePoly["Grp" . $x]["Poly" . $y] = array("Koord" => substr(strtr($SeeKoord[$x]["Koord"], $Array_XML),0,-1), "Boundary" => "outer");
				for($y = 0; $y <$PolyYld['maxregnr']; $y++)
				{
					if($x != $y)
					{
						$Poly2 = "GeomFromText('Polygon((" . substr(strtr($SeeKoord[$y]["Koord"], $Array_MySQL),0,-1) . "))')"; 
						$Kask = sprintf("SELECT MBRWithin(%s,%s) as onsees", $Poly2, $Poly1);
						$db->set_paring($Kask);
						$Kontroll = ab_fetch_array($db->execute());
						if($Kontroll['onsees'])
						{
							$SeePoly["Grp" . $x]["Poly" . $y] = array("Koord" => substr(strtr($SeeKoord[$y]["Koord"], $Array_XML),0,-1), "Boundary" => "inner");
						}

					}

				}
			
			}
		}
	}
	else
	{
		$SeeKoord[0]["Koord"] = substr(strtr($SeeKoord[0]["Koord"], $Array_XML),0,-1);
		$SeeKoord[0]["Boundary"] = 'outer';
		$SeePoly["Grp0"] = $SeeKoord;
	}

return $SeePoly;
}

function PaneMPEraldis($Koordin, $ID, $InfArray)
{
echo "\n<gml:featureMember>";
if($ID):
echo "\n <ms:multipolygon fid=\"" . $ID ."\">";
    else:
echo "\n <ms:multipolygon>";
endif;
echo "\n  <ms:msGeometry>";
echo "\n   <gml:MultiPolygon srsName=\"EPSG:3301\">";
foreach($Koordin as $Koord)
	{
		echo "\n    <gml:polygonMember>";
		echo "\n     <gml:Polygon>";
		foreach($Koord as $grp)
			{
			echo "\n      <gml:" . $grp["Boundary"] . "BoundaryIs>";
			echo "\n       <gml:LinearRing>";
			echo "\n        <gml:coordinates>\n";
			echo substr($grp["Koord"],0,-1);
			echo "\n        </gml:coordinates>";
			echo "\n       </gml:LinearRing>";
			echo "\n      </gml:" . $grp["Boundary"] . "BoundaryIs>";
			}
		echo "\n     </gml:Polygon>";
		echo "\n    </gml:polygonMember>";
	}
echo "\n   </gml:MultiPolygon>";
echo "\n  </ms:msGeometry>";
foreach($InfArray as $key=>$value)
	{
	echo "\n  <ms:" . $key . ">" . ($value) . "</ms:" . $key . ">";
	}
echo "\n </ms:multipolygon>";
echo "\n</gml:featureMember>";
}

function kaart_array_grp($id, $table, $idfield, $offsetx = 0, $offsety = 0, $Koef = 1, $PrnSql = false)
{
	global $db;

	$Eraldaja_Grp = '|';
	$Eraldaja_XY = 'x';


	$Par_MaxRn = sprintf("select max(regnr) as maxregnr from %s where %s = %d", $table, $idfield, $id) ;
	$db->set_paring($Par_MaxRn);
	$PolyYld = ab_fetch_array($db->execute());


	$SeeKoord = array_fill(0, $PolyYld['maxregnr'], array("Koord"=>"", "Boundary" => ""));

	$Par_Kaart = sprintf("select *, y as yy, x as xx from %s where %s = %d order by regnr, reaid", $table, $idfield, $id);
	if($offsety > 0)
	{
		$Par_Kaart = sprintf("select *, (%f - y)*%f as yy, (x - %f)*%f as xx from %s where %s = %d order by regnr, reaid", $offsety, $Koef, $offsetx, $Koef, $table, $idfield, $id);
	}

	$db->set_paring($Par_Kaart);
	$Row_Resource = $db->execute();

	while($Row = ab_fetch_array($Row_Resource))
	{
		//"%06.2f%s%07.2f%s"
		$SeeKoord[($Row['regnr']-1)]["Koord"] .=  sprintf("%06.3f%s%07.3f%s", $Row['xx'],$Eraldaja_XY, $Row['yy'],$Eraldaja_Grp);
	}

	$SeePoly = array();
	$Array_XML   = array("x"=>",", "|"=>" ");

	$par = sprintf("select * from %s_grp where %s = %d and isouter=1", $table, $idfield, $id);	
	$db->set_paring($par);
	$Regid = $db->execute();
	while($Regi = ab_fetch_array($Regid))
	{
		$outerID = $Regi['regnr'];
		$SeePoly["Grp" . $outerID]["Poly" . $outerID] = array("Koord" => substr(strtr($SeeKoord[$outerID-1]["Koord"], $Array_XML),0,-1), "Boundary" => "outer", "Center" => array((Get_Value($Regi, 'centx') - $offsetx)*$Koef, ($offsety - Get_Value($Regi, 'centy'))*$Koef));

		$par = sprintf("select * from %s_grp where %s = %d and outerid=%d", $table, $idfield, $id, $outerID);	
		$db->set_paring($par);
		$Innerid = $db->execute();
		while($Inner = ab_fetch_array($Innerid))
		{
			$innerID = $Inner['regnr'];
			$SeePoly["Grp" . $outerID]["Poly" . $innerID] = array("Koord" => substr(strtr($SeeKoord[$innerID-1]["Koord"], $Array_XML),0,-1), "Boundary" => "inner", "Center" => array((Get_Value($Regi, 'centx') - $offsetx)*$Koef, ($offsety - Get_Value($Regi, 'centy'))*$Koef));

		}
	}
	

return $SeePoly;
}


function kaart_array_pdf($id, $table, $idfield, $offsetx = 0, $offsety = 0, $Koef = 1, $PrnSql = false)
{
	global $db;

	$Eraldaja_Grp = '|';
	$Eraldaja_XY = 'x';


	$Par_MaxRn = sprintf("select max(regnr) as maxregnr from %s where %s = %d", $table, $idfield, $id) ;
	$db->set_paring($Par_MaxRn);
	$PolyYld = ab_fetch_array($db->execute());


	$SeeKoord = array_fill(0, $PolyYld['maxregnr'], array("Koord"=>"", "Boundary" => ""));

	$Par_Kaart = sprintf("select *, y as yy, x as xx from %s where %s = %d order by regnr, reaid", $table, $idfield, $id);
	if($offsety > 0)
	{
		$Par_Kaart = sprintf("select *, (%f - y)*%f as yy, (x - %f)*%f as xx from %s where %s = %d order by regnr, reaid", $offsety, $Koef, $offsetx, $Koef, $table, $idfield, $id);
	}

	$db->set_paring($Par_Kaart);
	$Row_Resource = $db->execute();

	while($Row = ab_fetch_array($Row_Resource))
	{
		//"%06.2f%s%07.2f%s"
		$SeeKoord[($Row['regnr']-1)]["Koord"] .=  sprintf("%f%s%f%s", $Row['xx'],$Eraldaja_XY, $Row['yy'],$Eraldaja_Grp);
	}

	$SeePoly = array();
	$Array_XML   = array($Eraldaja_XY=>" ", $Eraldaja_Grp=>" ");

	$par = sprintf("select * from %s_grp where %s = %d and isouter=1", $table, $idfield, $id);	
	$db->set_paring($par);
	$Regid = $db->execute();
	while($Regi = ab_fetch_array($Regid))
	{
		$outerID = $Regi['regnr'];
		$SeePoly["Grp" . $outerID]["Poly" . $outerID] = array("Koord" => substr(strtr($SeeKoord[$outerID-1]["Koord"], $Array_XML),0,-1), "Boundary" => "outer");

		$par = sprintf("select * from %s_grp where %s = %d and outerid=%d", $table, $idfield, $id, $outerID);	
		$db->set_paring($par);
		$Innerid = $db->execute();
		while($Inner = ab_fetch_array($Innerid))
		{
			$innerID = $Inner['regnr'];
			$SeePoly["Grp" . $outerID]["Poly" . $innerID] = array("Koord" => substr(strtr($SeeKoord[$innerID-1]["Koord"], $Array_XML),0,-1), "Boundary" => "inner");

		}
	}
	

return $SeePoly;
}

function PaneTKEraldis($Koordin,$ID, $ErNr)
{
echo "\n<gml:featureMember>";
echo "\n <ms:multipolygon fid=\"" . $ID ."\">";
echo "\n  <ms:msGeometry>";
echo "\n   <gml:MultiPolygon srsName=\"EPSG:3301\">";
foreach($Koordin as $Koord)
	{
		echo "\n    <gml:polygonMember>";
		echo "\n     <gml:Polygon>";
		foreach($Koord as $grp)
			{
			echo "\n      <gml:" . $grp["Boundary"] . "BoundaryIs>";
			echo "\n       <gml:LinearRing>";
			echo "\n        <gml:coordinates>\n";
			echo substr($grp["Koord"],0,-1);
			echo "\n        </gml:coordinates>";
			echo "\n       </gml:LinearRing>";
			echo "\n      </gml:" . $grp["Boundary"] . "BoundaryIs>";
			}
		echo "\n     </gml:Polygon>";
		echo "\n    </gml:polygonMember>";
	}
echo "\n   </gml:MultiPolygon>";
echo "\n  </ms:msGeometry>";
echo "\n  <ms:name>" . ($ErNr) . "</ms:name>";
echo "\n </ms:multipolygon>";
echo "\n</gml:featureMember>";
}

function TeeKML($Koordin, $ID, $InfArray)
{
    
echo "\n<Placemark>";
foreach($InfArray as $key=>$value)
	{
	echo "\n  <" . $key . ">" . ($value) . "</" . $key . ">";
	}

echo "\n<MultiGeometry>";     
        foreach($Koordin as $Koord)
	{
		echo "\n     <Polygon>";
		foreach($Koord as $grp)
			{
			echo "\n      <" . $grp["Boundary"] . "BoundaryIs>";
			echo "\n       <LinearRing>";
			echo "\n        <coordinates>\n";
			echo substr($grp["Koord"],0,-1);
			echo "\n        </coordinates>";
			echo "\n       </LinearRing>";
			echo "\n      </" . $grp["Boundary"] . "BoundaryIs>";
			}
		echo "\n     </Polygon>";
	}
echo "\n</MultiGeometry>";
echo "\n</Placemark>";
    
}


?>
