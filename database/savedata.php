<?php

global $db;
global $KirjuOigus;
global $ISConfig;


function CheckColumn($table, $column)
{
	global $db;
	if(substr($column, -2, 2) == "_h")
	{
		$column = substr($column, 0, -2);
	}

	$sqlQuery = sprintf("SELECT * FROM information_schema.columns WHERE table_name ='%s' and column_name = '%s'", $table, $column);

	$db->set_paring($sqlQuery);
	$tul = $db->execute();
	return ab_num_rows($tul);
}


/********************************************************

*********************************************************/

function Save_Post($RowID, $Update)
{
	global $db;
	$Table_Name = Get_Post("table");
	

	foreach($_POST as $key=>$value)
	{
		if(substr($key,0,2) == "f-")
		{
			$vali = substr($key, 4);
			$FN_OK = CheckColumn($Table_Name, $vali) == 1 ? 1 : 0;
			//printf("<br>Tabeli '%s' v&auml;li '%s' on %s", $Table_Name, $vali, $FN_OK);
			if($FN_OK == 1)
			{

				if(substr($key,0,4) == "f-n-")
				{
					if($Update)
					{
						$asim = numb(Get_Post("f-n-asimuut"));
						if(strpos($asim,"'")>0)
						{
							list($kr, $mi) = explode("'", $asim);
							$asim = $kr + $mi/60;
							$value = $asim;
						}

						
						$par_check = sprintf("select %s from %s where id = %d", $vali, $Table_Name,  $RowID);
						$db->set_paring($par_check);
						$tul_check = ab_fetch_array($db->execute());
						
						$Old_Value = Get_Value($tul_check, $vali)+0;
						$New_Value = udnr($value)+0;

						if($Old_Value != $New_Value)
						{
							$paring = sprintf("update %s set %s = %f where id = %d", $Table_Name, $vali, udnr($value), $RowID);
							$db->set_paring($paring);
							$db->execute();

							if($Old_Value != 0)
							{
								$paring = sprintf("insert into andmelogi (tabel, vali, vana, uus, rowid, muutja, aeg) values ('%s','%s','%s','%s',%d,%d, now())", $Table_Name, $vali, $Old_Value, $New_Value,$RowID, $_SESSION["UserID"]);
							}

							$db->set_paring($paring);
							$db->execute();

						}

					}
					else
					{
						$paring = sprintf("update %s set %s = %f where id = %d", $Table_Name, $vali, udnr($value), $RowID);
						$db->set_paring($paring);
						$db->execute();
					}
					


				}

				if(substr($key,0,4) == "f-c-")
				{
					if(strpos($key,"puuliik") > 0)
					{
						$value = strtoupper($value);
						if(in_array($value, array('1','2','3','4','5','6')))
						{
							$a_pl = array('','MA','KU','KS','HB','LV','LM');
							$value = $a_pl[$value];
						}
					}
					
					$paring = sprintf("update %s set %s = '%s' where id = %d", $Table_Name, $vali, addslashes($value), $RowID);
					$db->set_paring($paring);
					$db->execute();
				}

				if(substr($key,0,4) == "f-p-")
				{
					$paring1 = sprintf("select %s from %s where id = %d", $vali, $Table_Name, $RowID);
					$db->set_paring($paring1);
					$SeeRida = ab_fetch_array($db->execute());
					if($SeeRida['salasona'] != $value):
						$paring = sprintf("update %s set %s = md5('%s') where id = %d", $Table_Name, $vali, addslashes($value), $RowID);
						$db->set_paring($paring);
						$db->execute();
					endif;


				}


				if(substr($key,0,4) == "f-b-")
				{
					$chVali = substr($key, 4);
					if(substr($chVali,-2, 2) == "_h")
					{
						$chVali = substr($chVali, 0, -2);
					}
					$paring = sprintf("update %s set %s = %d where id = %d", $Table_Name, $chVali, $value == "on" ? 1 : 0, $RowID);
					$db->set_paring($paring);
					$db->execute();
				}

				if(substr($key,0,4) == "f-d-")
				{
					$year = 0;
					if(substr_count($value, '-') == 2)
					{
						list($year, $month, $day) = sscanf($value, "%d-%d-%d");
						$value = ArvKuup($year, $month, $day);
					}
					if(substr_count($value, '/') == 2)
					{
						list($day, $month, $year) = sscanf($value, "%d/%d/%d");
						$value = ArvKuup($year, $month, $day);
					}
					
					if(substr_count($value, '.') == 2)
					{
						list($day, $month, $year) = sscanf($value, "%d.%d.%d");
						$value = ArvKuup($year, $month, $day);
					}

					if(substr_count($value, ',') == 2)
					{
						list($day, $month, $year) = sscanf($value, "%d,%d,%d");
						$value = ArvKuup($year, $month, $day);
					}

                    if ($value == 'NULL'):
                        $paring = sprintf("update %s set %s = %s where id = %d", Get_Post("table"), $vali, $value, $RowID);
                    else:
                        $paring = sprintf("update %s set %s = '%s' where id = %d", Get_Post("table"), $vali, $value, $RowID);
                    endif; 
					$db->set_paring($paring);
					$db->execute();
				}
			}
		}
	}
}

/************************************************


******************************************************/

function ArvKuup($year, $month, $day)
{
	
	if (($year + $month + $day) > 0) :
        
        if($year < 100):
        
    		$sy = date("%Y");

    		if($sy > $year + 2000)
    		{
    			$year = $year + 1900;
    		}
    		else
    		{
    			$year = $year + 2000;
    		}
        endif;
        
        $value = sprintf("%04d-%02d-%02d", $year, $month, $day);

    else:
        $value = "NULL";        
    endif;
    
	return $value;
}

//echo "<!--";
//printf("%s, %s", Get_Post('update-data'), $KirjuOigus);
//echo "-->";

if(Get_Post('update-data') == 3 & $KirjuOigus)
{
	Save_Post(Get_Post('ID'),1);
}

if(Get_Post('insert-data') == 3 & $KirjuOigus)
{

	$db->set_paring(sprintf("insert into %s (%s) values (%d)", Get_Post('table'), Get_Post('ul_name'), Get_Post('ul_value')));
	$RowID = $db->executeID();

	Save_Post($RowID, 0);
}

if(Get_Post('delete-data') == 3 & $KirjuOigus)
{
	if(Check_Post("kontroll", "on"))
	{
		$paring = sprintf("delete from %s where id=%d",Get_Post('table'), Get_Post('ID'));
		$db->set_paring($paring);
		$db->execute();
	}
}

if(Get_Post('insert-proc') == 3 & $KirjuOigus)
{

	
	$host = $ISConfig->hostname;
	$user = $ISConfig->username;
	$pass = $ISConfig->password;
	$data = $ISConfig->database;




	$mysqli = new mysqli($host, $user, $pass, $data);

	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}

	$query = sprintf("call %s(%s);", Get_Post('call'), Get_Post('param'));
//	echo "Panin uue";
	if ($mysqli->multi_query($query)) 
		{
		do {
			/* store first result set */
			if ($result = $mysqli->store_result()) 
				{
					while ($row = $result->fetch_row())
					{
					}
					$result->free();
				}
			/* print divider */
			if ($mysqli->more_results()) 
				{
				}
			} 
		while ($mysqli->next_result());
	}
}

?>
