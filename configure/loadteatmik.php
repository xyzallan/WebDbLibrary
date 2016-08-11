<?php

global $db;
$db->set_paring("show tables");
$tmd = $db->execute();
while($tm = ab_fetch_row($tmd))
{
	$Tables_in = $tm[0];
	$suf = substr($Tables_in,0,3);
	$tbl = substr($Tables_in,3);
	if($suf=="tm_")
	{
		if($tbl != "strings")
		{
			$db->set_paring("select * from ".$suf.$tbl. " order by 1" );
			$tmtbld = $db->execute();
			
			global ${$tbl};

			${$tbl} = array("K0"=>"");

			while($tmtbl = ab_fetch_array($tmtbld))
			{
				$key = "K".$tmtbl['id'] ;
				$value = $tmtbl['nimi'];
				$ua = array($key => $value);
				${$tbl} = array_merge(${$tbl},$ua);
			}
		}
	}
	
}

?>