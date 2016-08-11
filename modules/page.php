<?php
global $ISConfig;
?>

function SetLang(Lang) {

$.post("<?=SetFilePath('modules','setlang.php')?>", 	
	{
		"lang" : Lang
	},
    function(data) {
		location.reload();
	}
) ;

}
