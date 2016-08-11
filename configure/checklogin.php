<?php

/*
InfosÄÂ¼steemi logimine.
*/

function CheckOnline()
{
	global $db;
	global $ISConfig;

	$logQuery = "select * from isikud where lastlogin + interval 10 minute >now()";
	if($ISConfig->dbtype == "postgresql")
	{
		$logQuery = "select * from isikud where lastlogin + interval '10 minute' >now()";
	}

	$db->set_paring($logQuery);
	$Id = $db->execute();
	$OI = "";
	while($I = ab_fetch_array($Id))
	{
		$OI .= $I['nimi'] . ", ";
	}
	
	if(strlen($OI) > 2)
	{
		return substr($OI, 0, -2);
	} 
	else
	{
		return $OI;
	}
}



/*****************************************************************************************/

function Kontrolli_Login()
{
    global $db;
    global $ISConfig;

	if(Check_Post("is-login", 2))
	{

		$LogUN = strtolower(Get_Post("sp-username"));
		$LogPW = Get_Post("sp-passwd");

		if(strpos($LogUN,"@")>0)
		{

			$paring = sprintf("select * from isikud where lower(epost)='%s' and salasona=md5('%s')", addslashes($LogUN), addslashes($LogPW));

			//echo $paring;
			$db->set_paring($paring);

		
			$Res = $db->execute();

			//echo ab_num_rows($Res);

			if(ab_num_rows($Res) == 1):

				$tulem = ab_fetch_array($Res);

				if(trim(strtolower($tulem['epost'])) == trim(strtolower($LogUN)))
				{
					$_SESSION["UserName"] = $LogUN;
					$_SESSION["UserPass"] = $LogPW;
					$_SESSION["UserID"]   = Get_Value($tulem,'id');
					$_SESSION["SysAdmin"] = Get_Value($tulem,'sysadmin');
					$_SESSION["WriteFormod"] = Get_Value($tulem,'formod');
					$_SESSION["WriteGytbls"] = Get_Value($tulem,'growfunc');
					$_SESSION["WriteGrowfunc"] = Get_Value($tulem,'gytbls');
					$_SESSION["Write"] = Get_Value($tulem,'sysadmin') ? Get_Value($tulem,'sysadmin') : Get_Value($tulem,'kirjutamine');
					$_SESSION["Read"] = Get_Value($tulem,'sysadmin') ? Get_Value($tulem,'sysadmin') : 0;
					$db->set_paring(sprintf("update isikud set lastlogin=now(), logimisi = logimisi+1 where id=%d", Get_Value($tulem,'id')));
					$db->execute();

				}
			endif;
		} else {
		
		$authent = ldap_auth(strtolower($LogUN), $LogPW);

		//print_r($authent);

		if($authent["Lubatud"]){
			$db->set_paring(sprintf("select * from isikud where emunimi='%s' ", addslashes($LogUN)));
			$Res = $db->execute();
			$tulem = ab_fetch_array($Res);

			if(ab_num_rows($Res) > 0)
			{
				$_SESSION["UserName"] = Get_Value($tulem,'epost');
				$_SESSION["UserPass"] = $LogPW;
				$_SESSION["UserID"]   = Get_Value($tulem,'id');
				$_SESSION["SysAdmin"] = Get_Value($tulem,'sysadmin');
				$_SESSION["WriteFormod"] = Get_Value($tulem,'formod');
				$_SESSION["WriteGytbls"] = Get_Value($tulem,'growfunc');
				$_SESSION["WriteGrowfunc"] = Get_Value($tulem,'gytbls');
				$_SESSION["Write"] = Get_Value($tulem,'sysadmin') ? Get_Value($tulem,'sysadmin') : Get_Value($tulem,'kirjutamine');
				$_SESSION["Read"] = Get_Value($tulem,'sysadmin') ? Get_Value($tulem,'sysadmin') : 0;

				$db->set_paring(sprintf("update isikud set lastlogin=now(), logimisi = logimisi+1 where id=%d", Get_Value($tulem,'id')));
				$db->execute();

			} 
			else
			{
				$db->set_paring(sprintf("insert into isikud (nimi, emunimi, asutus, epost, sysadmin, logimisi) values ('%s','%s','EMÜ', '%s', 0, 1)", $authent["Nimi"], $authent["Tunnus"], $authent["epost"]));
				$UserID = $db->executeID();
				$_SESSION["UserName"] = Get_Value($authent,"epost");
				$_SESSION["UserPass"] = $LogPW;
				$_SESSION["UserID"] = $UserID;
				$_SESSION["SysAdmin"] = 0;
				$_SESSION["Write"] = 0;

			}
			
		}

		}
	}


	if(Check_Post("sp-login", 3))
	{
		Reg_Sess_Defa();
		header("Location: " . $ISConfig->swww_server);
	}


	$LogSees = Get_Value($_SESSION,"UserName", 0) ? 1 : 2;

	return $LogSees;

}



function RegTeema($TeemaID)
{
	if($_SESSION["UserID"]>0)
	{
		global $db;
		$db->set_paring(sprintf("select * from proj_oigused where id_isikud=%d and id_projektid=%d",$_SESSION["UserID"], $TeemaID));
		$tulem = ab_fetch_array($db->execute());
		$_SESSION["Read"] = Get_Value($tulem,'lugemine', 0);
		$_SESSION["Write"] = Get_Value($tulem,'kirjutamine', 0);
		$_SESSION["TeemaID"] = $TeemaID;
	}
}

function ldap_auth($LogUserName, $LogPassWord)
{

	global $ISConfig;

	$allowLogin = 0;

	$Kasutaja = array("Nimi" => "", "Tootaja" => 0, "Tudeng" => 0, "Tunnus" => "", "Lubatud" => 0);

	$Kasutaja['Tunnus'] = $LogUserName;
	
	$allowTootaja = array('emytootaja','tootajad');
	$allowTudeng = array('emytudeng');


	if( $LogPassWord && $LogUserName ){
	
	$ldaprdn  = $ISConfig->bind_dn;
	$ldappass = $ISConfig->bind_pw;


	$baseDN    = "ou=isikud,dc=emu,dc=ee";
	$ldaphost = "ldaps://olp1.emu.ee ldaps://olp2.emu.ee";

	$ldapconn = ldap_connect($ldaphost);
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) ;

	if ($ldapconn) {

		$BindOK = ldap_bind($ldapconn, $ldaprdn, $ldappass);

	}

	if($BindOK)
	{
		$filter = 'SAMAccountName=' . $LogUserName;
		 
		$result = ldap_search($ldapconn, $baseDN, $filter);
		$search = false;
		
		if(ldap_count_entries($ldapconn, $result)>0){
			$search = $result;
		}
		
		if($search)
		{
			$info = ldap_get_entries($ldapconn, $search);

			$Kasutaja['Nimi'] = $info[0]["name"][0];
			$Kasutaja['epost'] = strtolower($info[0]["mail"][0]);

			$sudn = $info[0]['distinguishedname'][0];

//echo "<!--";
//print_r($info);
//echo "-->";
			
			$allowLoginx = @ldap_bind($ldapconn, $sudn, $LogPassWord);

			foreach($info[0]['memberof'] as $key => $value)
			{
				$grupp = explode(",", strtolower($value));
				
				foreach($grupp as $vaartus)
				{
					$grp = substr($vaartus,3);

					if(in_array($grp, $allowTootaja))
						{
						$Kasutaja['Tootaja'] = 1;
						$Kasutaja['Lubatud'] = $allowLoginx;
						}
				}

				foreach($grupp as $vaartus)
				{
					$grp = substr($vaartus,3);
					if(in_array($grp, $allowTudeng))
						{
						$allowAccess = true;
						$Kasutaja['Tudeng'] = 1;
						$Kasutaja['Lubatud'] = $allowLoginx;
						}
				}

			}

		} 	

	}
}
return $Kasutaja;

}
