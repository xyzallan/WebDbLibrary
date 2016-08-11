<?php

/*
Juhuks, kui kasutaja/brauser on eemaldanud https-i pĆ¤rast logimist:
*/
/*
if(Get_Value($_SESSION, 'UserID')):
	if(!Get_Value($_SERVER,'HTTPS')):
		header("Location: https://" . Get_Value($_SERVER,"SERVER_NAME") . Get_Value($_SERVER,'SCRIPT_NAME'));
	endif;
endif;
*/
global $ISConfig;
global $db;
global $sisufail;
global $Page_Conf_Array;

$Dokey = Get_Get('do');
$Page_Cont_Row = Get_Value($Page_Conf_Array, $Dokey);


LoadLibrary("Libs.template.header", RPath);

if(Get_Value($_SESSION,"UserID", 0) > 0)
{
	LoadLibrary("Libs.template.logoutform", RPath);
}
else
{
	LoadLibrary("Libs.template.loginform", RPath);
}

$db->set_paring("select char_length(uid)>0 as tuntud, count(*) as arv from online group by 1");
$Ores = $db->execute();

$Tulem = array("t"=>0, "f"=>0);

while($OnLine = ab_fetch_array_name($Ores))
{
    $Tulem[$OnLine["tuntud"]] = $OnLine["arv"];
}

$LOGIT = $Tulem["t"];
$KOIK = $Tulem["t"] + $Tulem["f"];

?>

<div style="width: 100%; text-align: center;">

<table style="width: 100%; border-spacing:0;">
<tr><td colspan=2 style="height: 50px;" >
<tr><td colspan=2 class="header-riba" style="text-align: right; ">Lehel: <?=$LOGIT?>/<?=$KOIK?>
<!-- background: url('<?=SetFilePath('template', 'orange_top_bg.gif')?>'); height: 30px; text-align: right; vertical-align: bottom; -->
<?php if($ISConfig->MultiLang){?>
<img id="flag-et" src="<?=SetFilePath('images','flag-et.png')?>" height=15 alt="Estonian">
<img id="flag-en" src="<?=SetFilePath('images','flag-en.png')?>" height=15 alt="English">
<?php }?>
<tr>
<td id="td-vasak-menu">
<!--<div id="as-vasak-men">-->

<script type="text/javascript">
  $(function() {
    $( "#menu" ).menu();
  });
</script>    
    
    
<!--<as-vasak-men>-->
<ul id="menu">
<?php
//$HorMenuT1 = "";
//$HorMenuT2 = "";
//$HorMenuT3 = "";
$LaadAlgus = microtime(true);

if(count(Get_Value($GLOBALS,'MenuKirjed')) > 0):

	foreach(Get_Value($GLOBALS,'MenuKirjed') as $menu=>$kirj)
	{
		foreach(Get_Value($kirj,'menu') as $key=>$value )
		{
			if (Get_Value($kirj,'position') == 'vert'):
			//class='%s', Get_Value($kirj,'style'),
                printf("<li><a href='?%s=%s'>%s</a>\n", Get_Value($kirj,'str'), $value,$key);
                            
            endif;
		}
	}

endif;

?>
</ul>
<!--</as-vasak-men>-->

<!--</div>-->
<td id="td-hori-menu">
<div id = "HorisMenu">
<?php



$MenuRead = array('Level'=>'','Level1'=>'','Level2'=>'','Level3'=>'','Level4'=>'');

if(count(Get_Value($GLOBALS,'MenuKirjed')) > 0):

	foreach(Get_Value($GLOBALS,'MenuKirjed') as $menu=>$kirj)
	{
		foreach(Get_Value($kirj,'menu') as $key=>$value )
		{
			if (Get_Value($kirj,'position') == 'hori'):

                $MenuRead['Level' . Get_Value($kirj, 'level')] .= sprintf("\n [ <a href='?%s=%s&amp;%s' class='%s'>%s</a> ] ", Get_Value($kirj, 'str'), $value, Get_Value($kirj, 'request'), Get_Value($kirj, 'style'), $key);
                
			
			endif;
		}
	}
endif;

foreach($MenuRead as $Key=>$Value)
{
    if(strlen($Value)>0)
    {
        echo "<B>" . Get_Value($ISConfig->LevelStrings, $Key, '') . "</B>" .  $Value . "<BR>";
    }
    
}    

?>
</div>

<?php

if(Get_Value($_GET,'do') == '')
{
	LoadLibrary("Libs.template.intro", RPath);
}


/******************************************************************/

?>
<?php
$_SESSION['PrintNO'] = Get_Value($_GET, 'do') ? 0 : 1;

$tblt = Get_Value($Page_Cont_Row,"tblt");
if (is_array($tblt)):
	$_SESSION['PrintNO'] = Get_Value($tblt, PCA_tblt::HidePrintPDF, 0);
endif;

LoadLibrary('titleh2', CPath);
LoadLibrary("modul.pre." . Get_Value($_GET,'do'), FPath);

if($ISConfig->PrintPDF & Get_Value($_SESSION, 'PrintNO') == 0):
if(Get_Value($_SESSION,'SysAdmin') || Get_Value($_SESSION,'Tootaja') || Get_Value($_SESSION,'Write')):
    echo "<p><a href=\"printpdf.php?". Get_Value($_SERVER,'QUERY_STRING') ."\" target=\"_blank\"><img src='" . SetFilePath('images','1361381371_pdf.png') . "' ></a>";
    echo "<a href=\"teexlsx.php?". Get_Value($_SERVER,'QUERY_STRING') ."\" target=\"_blank\"><img src='" . SetFilePath('images','xls.png') . "' ></a>";
endif;
endif;


if(defined("_fm_gentbl_"))
{
	LoadLibrary("interface.sisugener", LPath);
}

LoadLibrary("modul.post." . Get_Value($_GET,'do'), FPath);

/*
if($_SESSION['SysAdmin']):
	echo "
		<textarea cols=80 rows=10 id='tblsisu'></textarea>
	<script>
	$('#tblsisu').click(function()
	{
	$('#tblsisu').text($('#gentbl').html());
	}
	)
	</script>


	";


endif;
*/
/******************************************************************/


PrintArray($Page_Conf_Array);
PrintArray($_SESSION);

$Tblt_opt = array();
$Cols_typ = array();
foreach($Page_Conf_Array as $keyX=>$valueX):
    $Moodul = $valueX['tblt'];
    foreach($Moodul as $key=>$value):
        
        if(!isset($Tblt_opt[$key])):
            $Tblt_opt[$key] = 0;
        endif;
        $Tblt_opt[$key] += 1;
    endforeach;

    $Tulbad = $valueX['cols'];
    foreach($Tulbad as $key=>$value):
        $tunn = $value[parsFieldType];
        if(!isset($Cols_typ[$tunn])):
            $Cols_typ[$tunn] = 0;
        endif;
        $Cols_typ[$tunn] += 1;
    endforeach;
    
    
endforeach;
PrintArray($Tblt_opt, '$Tblt_opt');
PrintArray($Cols_typ, '$Cols_typ');

if($ISConfig->LibVersion == 'work'):
    $ISConfig->SysVersion = file_get_contents(RPath . "/Libs/work/version.txt");
endif;

LoadLibrary("Libs.template.footer", RPath); 
echo "<!-- $Dokey : " . print_r($Page_Cont_Row, true) . "-->";
