<?php

LoadLibrary("interface.formfuncs", LPath);


/**
 * GeneTableRow()
 * 
 * @param mixed $Row
 * @param mixed $cols
 * @param mixed $KirjuOigus
 * @param string $table_name
 * @return
 */

function GeneTableRow($Row, $cols, $KirjuOigus, $hidden_fix, $isSumRow = false, $numCols = null)
{
	
	global $ISConfig;
    $table_name = Get_Value($hidden_fix, 'table','');

    $FFUlName = Get_Value($hidden_fix,'ul_name', '');
    $FFUlValue = Get_Value($hidden_fix,'ul_value', '');

	
	
    PrintArray($Row);

    if (is_array($cols)) :

	    $colcount = 0;
        foreach($cols as $col=>$type):

            if(!is_null($numCols)):
                    echo $colcount%$numCols == 0 ? "\n<tr>" : ""; 
                    echo "\n<td class='parem'><i>" . Get_Value($type, parsFieldDescr) . "</i>: ";
            endif;
		
		
            $txtSisu = Get_Value($Row, $col);
            $txtType = Get_Value($type, parsFieldType);
            $txtRead = Get_Value($type, parsReadWrite);
            $txtForm = Get_Value($type,'koma', null);
            $celType = '';
            $style = '';
			$IDvali = Get_Value($type, 'otid','id');
			
            $FormFieldTable = Get_Value($type, 'table', $table_name);

            if(array_key_exists('cell', $type)):
				$celType = $type['cell'][parsFieldType];
            endif;

            $ct = '';
			
            /*
            Kui sisutüübiks on funktsioon
            */
            if($txtType == "f"):

                $vahe = array();
                foreach($type['params'] as $key=>$value)
                {
                        $vahe = array_merge($vahe, array("$value"=>Get_Value($Row,$value)));
                }

                $txtSisu = call_user_func_array($col, array_values($vahe));
            
            endif;

            if(array_key_exists('cell', $type))
            {
                $ct = $type['cell'][parsFieldType];
            }

			$VoibKirjutada = ($txtRead == constRoW_W && $KirjuOigus);	
				
			$ip_taust = "";
			switch($txtType)
			{
				case 'n':
					$txtSisu = Num2Text($txtSisu, $txtForm);
					if($ct == 'CB'):
						$style='kesk';
					else:
						$style='parem';
					endif;
					break;

				case 'i':
					$style='kesk';
					break;
				case 'c':
					$style='vasak';
					break;

				case 'd':
					list($year, $month, $day) = sscanf($txtSisu, "%d-%d-%d");
					$txtSisu = sprintf("%02d.%02d.%04d", $day, $month, $year);
			}

			$FormFieldName = 'f-'.Get_Value($type, parsFieldType).'-'.$col;

			echo "\n\t<td class='".$style."'>";

			PrintArray($type, 'form_table: $type');
			
			switch($ct)
			{
				case 'pdf':
					$Link = Get_Value($type, 'link');
					printf("<a href='%s%s?%s=%d' target='_blank'>PDF</a>", $ISConfig->www_server, Get_Value($Link, "FileName"), Get_Value($Link, "idname"), Get_Value($Row, Get_Value($Link, "idfield")));
					break;
				case 'SelO':
					$idcol = Get_Value($type['cell'], 'idcol','id');
					$namecol = Get_Value($type['cell'], 'namecol', 'nimi');
					echo spf_SelO($txtSisu, $FormFieldName, $FormFieldTable, Get_Value($Row, $IDvali), $type['cell']['tbl'], $type['cell']['ord'], $idcol, $namecol, $FFUlName, $FFUlValue, $VoibKirjutada);
					break;

				case 'SelOg':
					$idcol = Get_Value($type['cell'], 'idcol','id');
					$namecol = Get_Value($type['cell'], 'namecol', 'nimi');
					$grupp = $type['cell']['groupcol'];
					echo spf_SelOg($txtSisu, $FormFieldName, $FormFieldTable, Get_Value($Row, $IDvali), $type['cell']['tbl'], $type['cell']['ord'], $idcol, $namecol, $FFUlName, $FFUlValue, $VoibKirjutada, $grupp);
					break;

				case 'SelF':
					$idcol = Get_Value($type['cell'], 'idcol','id');
					$namecol = Get_Value($type['cell'], 'namecol', 'nimi');
					echo spf_SelF($txtSisu, $FormFieldName, $FormFieldTable, Get_Value($Row, $IDvali), $type['cell']['tbl'], $type['cell']['ord'], $type['cell']['fil'], $idcol, $namecol, $FFUlName, $FFUlValue, $VoibKirjutada);
					break;

				case 'CB':
					echo spf_CB($txtSisu, $FormFieldName, $FormFieldTable, Get_Value($Row, $IDvali), $VoibKirjutada, $FFUlName, $FFUlValue);
					break;

				case 'Submit':
					echo spf_SB($type['cell'], $Row);
					break;

				case 'ML':
					echo spf_ML($txtSisu, $FormFieldName, $FormFieldTable, Get_Value($Row, $IDvali), $VoibKirjutada, $type['cell']['col'], $type['cell']['row'], $FFUlName, $FFUlValue);
					break;

				default:
					echo spf_TB($txtSisu, $FormFieldName, $FormFieldTable, Get_Value($Row, $IDvali), $VoibKirjutada, $ip_taust, 5, $FFUlName, $FFUlValue);
			}
        
			$colcount++;

		endforeach;
        
    endif;
}


/**
 * GeneTableTH1()
 * 
 * @param mixed $th_up
 * @param mixed $KasLisaTyhiRida
 * @param mixed $view
 * @return
 */
function GeneTableTH1($th_up, $KasLisaTyhiRida, $view, $Kust = 0)
{

    if(is_array($th_up)):

        echo "\n<tr>";

        foreach($th_up as $col=>$span)
        {
            printf("\n<th colspan='%d'>%s",$span , $col);
        }

        if(is_array($view)):
            echo "\n<th>";
        endif;

        // systeemi adminni jaoks kustutamise tulp
        if(Get_Value($_SESSION, 'SysAdmin') && $Kust):
            echo "\n<th>";
        endif;
    endif;
}



/**
 * GeneTableTH2()
 * 
 * @param mixed $cols
 * @param mixed $KasLisaTyhiRida
 * @param mixed $view
 * @return
 */
function GeneTableTH2($cols, $KasLisaTyhiRida, $view, $Kust = 0)
{
    global $ISConfig;
    $nTulp = 0;

    if(is_array($cols)):
            echo "\n<tr>";

        foreach($cols as $col=>$type)
        {
            echo "\n<th>" . Get_Value($type, parsFieldDescr);
            if(Get_Value($type,'help','')):
                echo " <a href='". $ISConfig->DocsPath . Get_Value($type,'help','') . "' target=_blank><img src='" . SetFilePath('images','s_really.png') . "'></a>";
            endif;
            $nTulp++;
        }

        if(is_array($view)):
                echo "\n<th>&nbsp;" . Get_Value($GLOBALS, 'strView', 'Vaata'). "&nbsp;";
                $nTulp++;
        endif;

        if(Get_Value($_SESSION, 'SysAdmin')  && $Kust):
                echo "\n<th>&nbsp;" . Get_Value($GLOBALS, 'strDelete', 'Kustuta'). "&nbsp;";
                $nTulp++;
        endif;
    endif;

return 	$nTulp;

}


/**
 * GeneVertTableForm()
 * 
 * @param string $ab_res
 * @param string $cols
 * @param string $hidden_fix
 * @param string $view
 * @param string $name
 * @param string $th_up
 * @param string $tbl_type
 * @param string $add_empty
 * @return
 */
function GeneVertTableForm($ab_res='', $cols='', $hidden_fix='', $view='', $name='', $th_up='', $tbl_type = '', $add_empty = '', $tbl_by='')
{
    
    PrintArray($hidden_fix, '$hidden_fix');
    PrintArray($tbl_type, '$tbl_type');
    PrintArray($add_empty,'$add_empty');
    
    $KasLisaTyhiRida = false;
    $Kirjutamine = 0;
	$ColByStat = false;
    if(is_array($tbl_type))
    {
        $KasLisaTyhiRida = ($tbl_type[tbltparHasEmptyRow] == constTblHasNewRowYes);
        $LisaRida = Get_Value($tbl_type, constAddNewRow, 'Write');
        $Kirjutamine = Get_Value($tbl_type, tbltparCanWrite, 0);
        $ColByStat = isset($tbl_type[tbltparsColbyStat]);
    }

    $AllRows = array();
	while($Row = ab_fetch_array_name($ab_res)):
		$AllRows[] = $Row;
	endwhile;
	
	
	echo "<!-- Tühi rida $KasLisaTyhiRida -->";
	
    $editbtn = "<img width='16' height='16' src='" . SetFilePath('images','b_browse.png') . "' alt='edit'>";

    $RiduYhesTabelis = 50;
    
    $RiduKokku = ab_num_rows($ab_res);

    $aURI = explode('&', $_SERVER['QUERY_STRING']);
    foreach($aURI as $key=>$value)
    {
        if(substr($value, 0, 7) == 'minrida'):
            unset($aURI[$key]);
        endif;
    }

	$LabelCol = Get_Value($tbl_type, tbltparsRowIDCol, '');
	
    if($RiduKokku > $RiduYhesTabelis):
        echo "<br><span style='font-weight:bold; color: red;'> Read ";
        
        for($i = 1; $i<$RiduKokku; $i=$i+$RiduYhesTabelis):
            $RowIDmin = $i;
            $RowIDmax = min($i+$RiduYhesTabelis-1, $RiduKokku);
            if($LabelCol):
                    $Label1 = $AllRows[$RowIDmin-1][$LabelCol];
                    $Label2 = $AllRows[$RowIDmax-1][$LabelCol];
            else:
                    $Label1 = $RowIDmin;
                    $Label2 = $RowIDmax;
            endif;
            $aURI['minrida'] = "minrida=$i";
            printf("<a href='%s?%s'>[%s-%s]</a> ", $_SERVER['SCRIPT_NAME'], implode('&amp;', $aURI) ,$Label1, $Label2);
        endfor;
        
        echo "</span><br>";
    endif;
    
    $MinRida = Get_Value($_GET, 'minrida', 1);
    
	
	
    echo "\n<br>\n<table class='grid' id='gentbl'>\n";

    echo "<thead>";
    GeneTableTH1($th_up, $KasLisaTyhiRida, $view, Get_Value($tbl_type, tbltparsDeleteRow, 0));

    $nTulp = GeneTableTH2($cols, $KasLisaTyhiRida, $view, Get_Value($tbl_type, tbltparsDeleteRow, 0));
    echo "</thead>";
    $SumRida = Get_Value($tbl_type, tbltparSumRow, 0);

    /*
    Lisame kõik read 
    */

    $Rida = 0;
    $Sums = $cols;

    if (is_array($Sums)) :
        foreach($Sums as $key=>$value)
        {
            if(Get_Value($value, tbltparSumRow , 0) == 1 )
            {
                $Sums[$key] = 0;
            }
            else
            {
                $Sums[$key] = "";
            }

        }
    endif;

    foreach($AllRows as $Row):
		$Rida++;

		if (is_array($cols)) : 

			foreach($cols as $col=>$type)
			{
				if(Get_Value($type, tbltparSumRow , 0) == 1 ):
					$Sums[$col] = $Sums[$col] + $Row[$col];
				endif;
			}

		endif;

		

		if($Rida >= $MinRida & $Rida <= $MinRida+$RiduYhesTabelis-1):


			if($ColByStat):
				$RowClass = "Stat_" . $Row[$tbl_type[tbltparsColbyStat]];
			else:
				$RowClass = "Rida" . ($Rida%2);
			endif;
			
		printf("\n\n<tr id='X_sisu_%d' class='%s'>", $Rida, $RowClass);

		GeneTableRow($Row, $cols, $Kirjutamine, $hidden_fix);


		if(is_array($view))
		{
			echo "\n\t<td class='kesk'>";

			$ViewID = Get_Value($Row, $view['idfield']);

			if (!empty($ViewID)):
				printf("\n\t<a href = '?do=%s&amp;%s=%s'>%s</a>", $view['do'], $view['idname'], $ViewID, $editbtn);
			endif;
		}

		if(Get_Value($_SESSION, 'SysAdmin') && Get_Value($tbl_type, tbltparsDeleteRow, 0))
		{
			echo "\n\t<td class='kesk'><input type='image' src='" . SetFilePath('images','b_drop.png') . "' alt='Kustuta' style='border: none;' onClick=\"DeleteRow('" . Get_Value($hidden_fix, 'table','') . "', " . $Row['id']. ")\">";
		}
		endif;
            
	endforeach;

    GeneVertTableFooter($KasLisaTyhiRida, $Rida, $view, $hidden_fix, $cols, $nTulp, $add_empty, $LisaRida, Get_Value($tbl_type, tbltparsDeleteRow, 0));

    $Rida++;

    if($SumRida)
    {
        echo "<tfoot>";
		
		foreach($cols as $key=>$value)
        {
            if(is_array(Get_Value($value, 'keskmine')))
            {
                $Jagamine = Get_Value($value, 'keskmine');
                $UL = Get_Value($Sums, $Jagamine['nom']);
                $AL = Get_Value($Sums, $Jagamine['den']);
                if($AL > 0):
					$KO = Get_Value($Jagamine,'kordaja', 1);
					$Koma = Get_Value($value, 'koma', 0);
					$KE = round($UL/$AL*$KO, $Koma);
					$Sums[$key] = $KE;
				endif;
            }
        }
        echo "\n\n<tr class='RidaSum'>";

        GeneTableRow($Sums, $cols, 0, $hidden_fix);

        if(is_array($view)):
                echo "\n\t<td class='kesk'><!--view-->&nbsp;";
        endif;
        
        if(Get_Value($_SESSION, 'SysAdmin') && Get_Value($tbl_type, tbltparsDeleteRow, 0))
        {
            echo "\n\t<td class='kesk'>";
			//<input type='image' src='" . SetFilePath('images','b_drop.png') . "' alt='Kustuta' style='border: none;' onClick=\"DeleteRow('" . Get_Value($hidden_fix, 'table','') . "', " . $Row['id']. ")\">";
        }
        echo "</tfoot>";
    }


    echo "\n</table>";   

}



function GeneVertTableFooter($LisaTyhiRida, &$Rida, $view, $hidden_fix, $cols, $nTulp, $add_empty, $LisaRida, $Kustutus)
{
	echo "\n<!-- Siit algab jalus $LisaTyhiRida-->\n";
    if($LisaTyhiRida):
        echo "\n<tr id='X_sisu_". $Rida . "' class='Rida1'>";
        $Lisa = Get_Value($_SESSION, $LisaRida, 0);

        GeneTableRow(array(0), $cols, $Lisa, $hidden_fix);

        if(is_array($view)):
                echo "\n\t<td class='kesk'><!--view-->&nbsp;";
        endif;

        if(Get_Value($_SESSION, 'SysAdmin') && $Kustutus)
        {
            echo "\n\t<td class='kesk'>";
        }


    endif;

    if(is_array($add_empty) & (Get_Value($_SESSION, 'Write') | Get_Value($_SESSION, 'Admin') | Get_Value($_SESSION, 'SysAdmin'))):
    
        echo "\n<tr>";

        printf("<td class='kesk' colspan='%d'>", $nTulp);
        echo "<input type='image' src='".SetFilePath('images','b_insrow.png')."' style='border: none;' \n onclick=\"SendInsertProcData('" . Get_Value($add_empty, 'call') . "', " . Get_Value($add_empty, 'param') . ",'" . Get_Value($add_empty, 'tabel') . "')\">" . Get_GlobalStr('strAddNew');

    endif;

    
    PrintArray($cols);
    PrintArray($hidden_fix);
}

/**
 * GeneHoriTableForm()
 * 
 * @param string $ab_res
 * @param string $cols
 * @param string $hidden_fix
 * @param string $name
 * @param string $tbl_type
 * @param integer $ncols
 * @return
 */
function GeneHoriTableForm($ab_res='', $cols='', $hidden_fix='', $name='', $tbl_type = '', $ncols = 2)
{

    global $ISConfig;

	$Kirjutamine = Get_Value($tbl_type, tbltparCanWrite, 0);

/*
	$FFUlName = Get_Value($hidden_fix, 'ul_name', '');
    $FFUlValue = Get_Value($hidden_fix, 'ul_value', '');
*/
    $Row = ab_fetch_array_name($ab_res);

    PrintArray($Row);
    
    echo "\n<br>\n<table class='vert-tbl'>";
		GeneTableRow($Row, $cols, $Kirjutamine, $hidden_fix, false, $ncols);
	

    echo "\n</table>";

}

function FormSingCell()
{
    
}
