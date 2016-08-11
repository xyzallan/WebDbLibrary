<?php
/**
 * Short description for file
 * @ Author      : Allan Sims
 * @ Package     : WebIS_basesystem
 * @ Last modify : 15.10.2012
*/


function CheckRights()
{

$A_Teema = 0;
$A_Syst = 0;
$U_Kirjut = 0;
$U_Kasut = 0;


    if(Get_Value($_SESSION, "UserID")>0)
	{
		global $db;
		global $TeemaNimi;
	
		$ProjAdminID = 0;

		if ( Get_Value($_SESSION, "TeemaID") > 0)
		{
			$db->set_paring(sprintf("select * from projektid where id = %d;", Get_Value($_SESSION, "TeemaID")));
			$projAdmin = ab_fetch_array($db->execute());
			
			$TeemaNimi = $projAdmin['nimi'];
			$ProjAdminID = $projAdmin['admin'];
                                                $_SESSION['Admin'] = $projAdmin['admin'] == Get_Value($_SESSION, "UserID") ? 1 : 0;

			$db->set_paring(sprintf("select * from proj_oigused where id_projektid = %d and id_isikud = %d", Get_Value($_SESSION, "TeemaID"), Get_Value($_SESSION, "UserID")));
			
			$proj = ab_fetch_array($db->execute());
			$U_Kirjut = Get_Value($proj, 'kirjutamine', 0);
			$U_Kasut  = Get_Value($proj, 'lugemine', 0);
			$_SESSION['Write'] = Get_Value($proj, 'kirjutamine', 0);
			$_SESSION['Read'] = Get_Value($proj, 'lugemine', 0);
		}

		$db->set_paring(sprintf("select * from isikud where id = %d", Get_Value($_SESSION, "UserID")));
		$SysAdmin = ab_fetch_array($db->execute());
		$A_Syst = $SysAdmin['sysadmin'];
		$_SESSION['SysAdmin'] = $SysAdmin['sysadmin'];
		$U_Kirjut = $SysAdmin['kirjutamine'];

		$U_Kirjut = $A_Syst ? $A_Syst : $U_Kirjut;
                
		$_SESSION['Admin'] = $_SESSION['SysAdmin']  ? $_SESSION['SysAdmin']  : $_SESSION['Admin'] ;

		if($ProjAdminID == Get_Value($_SESSION, "UserID"))
		{
			$A_Teema = 1;
		}

	}

}

