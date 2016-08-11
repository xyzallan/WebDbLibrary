<?php




/*
  Kontrollitakse, kas sisseloginud kasutaja on ka Administraatori Ćµigustega:
  kontrollitakse nii teema kui sĆ¼steemi haldaja Ćµigusi.
*/

function CheckAdmin($sys=0)
{
	$admin = 0;
	
    if($_SESSION["UserID"]>0)
	{
		global $db;
		global $TeemaNimi;
	
		$ProjAdminID = 0;

		if ( $_SESSION["TeemaID"] > 0)
		{
			$db->set_paring(sprintf("select * from projektid where id=%d", $_SESSION["TeemaID"]));
			$projAdmin = ab_fetch_array($db->execute());
			
			$TeemaNimi = $projAdmin['nimi'];
			$ProjAdminID = $projAdmin['admin'];
		}

		$db->set_paring(sprintf("select * from isikud where id=%d", $_SESSION["UserID"]));
		$SysAdmin = ab_fetch_array($db->execute());
		
        if($SysAdmin['sysadmin'] == 1)
		{
			$admin = 1;
		}
		
        if($sys == 0)
		{
            if($ProjAdminID == $_SESSION["UserID"])
			{
				$admin = 1;
			}
		}
	}

    return $admin;
}

function CheckWrite()
{
	$admin = 0;
	
    if($_SESSION["UserID"]>0)
	{
		global $db;
		if ($_SESSION["TeemaID"] > 0)
		{
			$db->set_paring(sprintf("select * from proj_oigused where aktiivne = 1 and id_projektid=%d and id_isikud=%d", $_SESSION["TeemaID"], $_SESSION["UserID"]));
			
			$proj = ab_fetch_array($db->execute());
			$admin = Get_Value($proj, 'kirjutamine', 0);
		}
	}

    return $admin;
}


function CheckUsing()
{
    $admin = 0;
	
    if($_SESSION["UserID"]>0)
	{
		global $db;

		if ($_SESSION["TeemaID"] > 0)
		{
			$db->set_paring(sprintf("select * from projektid where id=%d", $_SESSION["TeemaID"]));
			$projAdmin = ab_fetch_array($db->execute());
			if($projAdmin['tase'] == 2)
			{
				$admin = 2;
			}
		}
		
		
		$db->set_paring(sprintf("select * from proj_oigused where aktiivne = 1 and id_projektid=%d and id_isikud=%d", $_SESSION["TeemaID"], $_SESSION["UserID"]));
		$Tulem = $db->execute(); 
		if(ab_num_rows($Tulem)>0)
		{
			$isik = ab_fetch_array($Tulem);
			if($isik['lugemine'] == 1)
			{
                $admin = 1;
			}
		}
   
      
	}

    return $admin;
}

/*
  Laetakse kasutajate loend vormi jaoks
  vajadusel lisaktakse ka "selected" valik    
*/


function UserName($userID)
{
    global $db;
    $db->set_paring(sprintf("select * from isikud where id=%d",$userID));
    $Ud = ab_fetch_array($db->execute());
    return $Ud['nimi'];
}


function spTextBox($sisu, $nimi, $oigus, $style="",$max=5, $table='', $table_id = 0, $datamet = 'update')
{
    if($oigus == 1)
    { 
        $uid = uniqid();
		$pikkus = max(min(strlen($sisu), 40),$max);
        $tekst = sprintf("<input id=\"%s\" type=\"text\" name=\"%s\" value=\"%s\" size=%s style='%s'>",$uid, $nimi, stripslashes($sisu),$pikkus,$style);
        //$tekst = sprintf("\n\t<input id=\"%s\" onblur=\"ajaxFunction(this.value,'%s')\" type=\"text\" name=\"%s\" value=\"%s\" size=%s style='%s'>\n",$uid, $nimi, $nimi, stripslashes($sisu),$pikkus,$style);
    }
    else
    {
        $tekst = stripslashes($sisu);
    }
    
    return $tekst;
}

function spTextBox2($sisu, $nimi, $oigus, $max=5, $ridu=1)
{
    if($oigus == 1)
    { 
        $pikkus = max(min(strlen($sisu), 40),$max);
        $tekst = sprintf("\n<textarea name=\"%s\" rows=\"%d\" cols=\"%s\">%s</textarea>",$nimi,$ridu,$pikkus, stripslashes($sisu));
    }
    else
    {
        $tekst = strtr(stripslashes($sisu), array("\n"=>"<br>"));
    }
      return $tekst;

}


function spCheckBox($sisu, $nimi, $oigus)
{
    if($oigus == 1)
    { 
        $tekst = sprintf("\n<input type='checkbox' name='%s' %s>\n",$nimi, $sisu ? " checked='yes' " : "");
    }
    else
    {
      $tekst = $sisu;
    }
    
    return $tekst;
}


/*
function spHidden($nimi, $value)
{
    return sprintf("\n<input type='hidden' name='%s' value='%s'>\n",$nimi, $value);
}
*/

function spSelect($value,$name, $table)
{
    global $db;
    echo "\n<!-- tabel:  $table -->";
    
    $db->set_paring(sprintf("select * from %s order by 1",$table));
    $Rows = $db->execute();
    
    $select = sprintf("\n<select name='%s'>",$name);
    $select .= "<option value='0'>-- Puudub --</option>\n";
    
    while($Row = ab_fetch_array($Rows))
    {
        $select .= sprintf("<option value='%s' %s>%s - %s</option>\n",$Row['id'], $Row['id']==$value ? " selected " : "", $Row['id'], $Row['nimi']);
    }
    $select .= "</select>";
    
    return $select;
}

function spSelectOrder($value,$name, $table, $order)
{
    global $db;
    
    $db->set_paring(sprintf("select * from %s order by %s",$table, $order));
    $Rows = $db->execute();
    
    $select = sprintf("\n<select name='%s'>",$name);
    $select .= "<option value='0'>-- Puudub --</option>\n";
    
    while($Row = ab_fetch_array($Rows))
    {
        $select .= sprintf("<option value='%s' %s>%s - %s</option>\n",$Row['id'], $Row['id']==$value ? " selected " : "", $Row['id'], $Row['nimi']);
    }
    $select .= "</select>";
    
    return $select;
}


function spSelectFilter($value,$name, $table, $field, $filter,$order=2)
{
    global $db;
    
    $db->set_paring(sprintf("select * from %s where %s order by %s",$table, $filter, $order));
    $Rows = $db->execute();
    
    $select = sprintf("<select name='%s'>",$name);
    $select .= "<option value='0'>-- Puudub --</option>\n";
    
    while($Row = ab_fetch_array($Rows))
    {
        $select .= sprintf("<option value='%s' %s>%s</option>\n",$Row['id'], $Row['id']==$value ? " selected " : "", $Row[$field]);
    }
    
    $select .= "</select>";
    
    return $select;
}

function spSubmit($tekst)
{
	return sprintf("<input type='submit' value='%s'>", $tekst);
}

function udCheckBox($tekst)
{
    return strtolower($tekst)=="on" ? 1 : 0;
}

if(!function_exists("d2dms"))
{
	function d2dms($degr)
	{
		$kr = floor($degr);
		$mn = floor(($degr - $kr)*60);
		$sk = round(($degr - ($kr + $mn/60))*3600,0);
		return substr("00".$kr,-2,2) . "'" . substr("00".$mn,-2,2) . "'" . substr("00".$sk,-2,2);
	}
}

function CheckInMap($IP)
{
	if($IP < 20 | $IP > 30) 
	{
		return 0;
	}
		else
	{
		return 1;
	}
}

function CheckLongitude($IP)
{
	if($IP < 20 | $IP > 30) 
	{
		return "style='background-color: red;'";
	}
		else
	{
		return "";
	}
}

function CheckLatitude($PL)
{
	if($PL < 57 | $PL > 60) 
	{
		return "style='background-color: red;'";
	}
		else
	{
		return "";
	}
}

function L_EST($x,$y) {

	// code by Argo Vilberg. argo@elavtoit.com, www.elavtoit.com";

	// echo "Algpunkti geodeetilised koordinaadid";

	$B=58+5/60;
	$L=25+20/60;

	// echo "Projektsiooni parameetrid:";
	// echo "Ristkoordinaatide alguspunti geodeetilised koordinaadid:";

	$B0=57+31/60.0+3.19415/3600;
	$L0=24;

	// echo "Ristkoordinaatide alguspunkti ristkoordinaadid";

	$X0=6375000.000;
	$Y0=500000.000;

	// echo "Koonuse lĆµikeparalleelid";

	$B1=58;
	$B2=59+20/60;

	// echo "Ellipsoidi GRS 80 parameetrid";
	$a=6378137;
	$e=0.081819191;

	$m1 = cos(deg2rad(58))/sqrt(1-pow($e,2)*pow(sin(deg2rad(58)),2));
	$m2 = cos(deg2rad(59+1/3))/sqrt(1-pow($e,2)*pow(sin(deg2rad(59+1/3)),2));
	$m = cos(deg2rad(58+5/60))/sqrt(1-pow($e,2)*pow(sin(deg2rad(58+5/60)),2));
	$m = cos(deg2rad(58+5/60))/sqrt(1-pow($e,2)*pow(sin(deg2rad(58+5/60)),2));

	$t1=tan(M_PI/4-deg2rad($B1/2))/pow(((1-$e*sin(deg2rad($B1)))/(1+$e*sin(deg2rad($B1)))),$e/2);
	$t2=tan(M_PI/4-deg2rad($B2/2))/pow(((1-$e*sin(deg2rad($B2)))/(1+$e*sin(deg2rad($B2)))),$e/2);
	$t0=tan(M_PI/4-deg2rad($B0/2))/pow(((1-$e*sin(deg2rad($B0)))/(1+$e*sin(deg2rad($B0)))),$e/2);

	$t=tan(M_PI/4-deg2rad($B/2))/pow(((1-$e*sin(deg2rad($B)))/(1+$e*sin(deg2rad($B)))),$e/2);

	$n=(log($m1)-log($m2))/(log($t1)-log($t2));
	$F=$m1/($n*pow($t1,$n));
	$p0=$a*$F*pow($t0,$n);
	$p=$a*$F*pow($t,$n);
	$Q=$n*deg2rad($L-$L0);

	$X=$p0-$p*cos($Q)+$X0;
	$Y=$p*sin($Q)+$Y0;

	return array($X,$Y); 
}


?>