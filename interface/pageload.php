<?php

global $fm_GenTabClass;
global $Page_Conf_Array;
global $Page_Cont_Row;
global $Page_Array;

global $AdminOigus, $SysAdmin, $KirjuOigus, $KasutOigus;

if(is_array(Get_Value($Page_Array, 'tblt')))
{
	$key = Get_Get('do');
	$Page_Conf_Array[$key] = $Page_Array;
}

if(array_key_exists(Get_Get('do'), $Page_Conf_Array) )
{
	$key = Get_Get('do');
	$value = $Page_Conf_Array[$key];

	$PreFunc = Get_Value($value,'pred',''); 
	if(is_array($PreFunc))
	{
		$FuncName = Get_Value($PreFunc,'func');
		$ParsValu = Get_Value($PreFunc,'pars');
		if(function_exists($FuncName))
		{
			if(is_array($ParsValu))
			{
				call_user_func_array($FuncName, $ParsValu);
			}
			else
			{
				call_user_func($FuncName, $ParsValu);
			}
			CheckRights();
			$value = $Page_Conf_Array[$key];
		}
	}

	$Tbl_Info = Get_Value($value,'tblt');
	$KirjuOigus = Get_Value($Tbl_Info,'KirjuOigus',0);
    
	$fm_GenTabClass->SetGenTab_SQLP(Get_Value($value,'csql'));
	$fm_GenTabClass->SetGenTab_COLS(Get_Value($value,'cols'));
	$fm_GenTabClass->SetGenTab_HFIX(Get_Value($value,'hfix'));
	$fm_GenTabClass->SetGenTab_VIEW(Get_Value($value,'view'));
	$fm_GenTabClass->SetGenTab_NAME(Get_Value($value,'name'));
	$fm_GenTabClass->SetGenTab_THUL(Get_Value($value,'thul'));
	$fm_GenTabClass->SetGenTab_HEAD(Get_Value($value,'head'));
	$fm_GenTabClass->SetGenTab_READ(Get_Value($value,'tblt'));
	$fm_GenTabClass->SetGenTab_EMPY(Get_Value($value,'empy'));
	$fm_GenTabClass->SetGenTab_BY(Get_Value($Tbl_Info,'by'));
	
	$Page_Cont_Row = $value;
	define("_fm_gentbl_", 1);

}
