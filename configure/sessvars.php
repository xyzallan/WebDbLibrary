<?php

if(session_status() != PHP_SESSION_ACTIVE):
    session_start();
endif;

global $db;

/* Online haldus */

$db->set_paring(sprintf("delete from online where sessid='%s'", session_id()));
$db->execute();


$db->set_paring("delete from online where last < now()-interval '10 min'");
$db->execute();

$Oparing = sprintf("insert into online (sessid, last, ipaddr, uid, brauser) values ('%s', now(), '%s', '%s', '%s')", 
		session_id(), 
		Get_Value($_SERVER,"REMOTE_ADDR",""), 
		Get_Value($_SESSION, 'UserName',''), 
		addslashes(substr(Get_Value($_SERVER,"HTTP_USER_AGENT",""), 0, 200))
		);


$db->set_paring($Oparing);
$Res = $db->execute();

/* Online halduse lõpp */


function Reg_Sess($Var, $Defa)
{
    if(!isset($_SESSION[$Var])):

        $_SESSION[$Var] = $Defa;

    endif;
}

function Reg_Sess_Defa()
{
    foreach($_SESSION as $key=>$value):
        unset($_SESSION[$key]);
    endforeach;
    
    global $db;
    $db->set_paring("select * from sys_sessvars");
    $Res = $db->execute();
    while($Var = ab_fetch_object($Res)):
        $_SESSION[$Var->varname] = $Var->defa;	
    endwhile;
}

/* Registreerime süsteemi jaoks vajalikud sessiooni parameetrid */

$db->set_paring("select * from sys_sessvars");
$Res = $db->execute();

while($Var = ab_fetch_object($Res)):
    Reg_Sess($Var->varname, $Var->defa);	
endwhile;
