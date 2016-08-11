<?php
global $ISConfig;
?>
<script type="text/javascript">

function SendSaveData(sVaartus, sVali, sTabel, rowid, FormID, datamet, sValjatyyp)
{

$.post("<?=$ISConfig->SavePath?>", 	
	{
		"tabelinimi" : sTabel, 
		"valjanimi"  : sVali, 
		"valjatyyp"  : sValjatyyp,
		"vaartus"    : sVaartus,
		"id"         : rowid,
		"meetod"     : datamet
	},
    function(data) {
		//alert("Tehtud");
		$("#" + FormID).css('background-color', data);
	}
) ;


}


function SendSaveDataX(sVaartus, sVali, sTabel, rowid, FormID, datamet, sValjatyyp)
{
var bVaartus = 0;
if($('#' + FormID).is(':checked'))
    {
        bVaartus = 1;
    }


$.get("<?=$ISConfig->SavePath?>", 	
	{
		"tabelinimi" : sTabel, 
		"valjanimi"  : sVali, 
		"valjatyyp"  : sValjatyyp,
		"vaartus"    : bVaartus,
		"id"         : rowid,
		"meetod"     : datamet
	},
    function(data) {
		$("#" + FormID).css('background-color', data);
	}
);
   
}

function DeleteRow(sTabel, rowid)
{
if(confirm("Kas kustutada? " + sTabel + " id=" + rowid))
{

	$.get("<?=$ISConfig->SavePath?>", 	
	{
		"tabelinimi" : sTabel, 
		"id"         : rowid,
		"meetod"     : "delete"
	},
    function(data) {
		location.reload();
	}
) ;

}
}

function CheckToNr(bVaartus)
{
	return bVaartus ? 1 : 0;
}

function SendInsertData(sVaartus, sVali, sTabel, rowid, FormID, datamet, sValjatyyp, ULname, ULvalue)
{

$.get("<?=$ISConfig->SavePath?>", 	
	{
		"tabelinimi": sTabel, 
		"valjanimi"  : sVali, 
		"valjatyyp"  : sValjatyyp,
		"vaartus"    : sVaartus,
		"id"             : rowid,
		"meetod"    : datamet,
		"ulname"     : ULname,
		"ulvalue"     : ULvalue
	},
    function(data) {
        $("#" + FormID).focus();

        var count = $("table#gentbl tbody>tr").length;
        var uus = count +1;

        var kloon2 = $('<tr id = "X_sisu_' + uus + '">' + $('table#gentbl tbody>tr:last').html() + '</' + 'tr>');

        var rida = 0;
        var read = "";

        $("table#gentbl tbody").find('tr').each(
        function(i,el)
        {
            $(el).removeClass('Rida0').removeClass('Rida1').removeClass('Rida5');
            $(el).addClass("Rida"+rida%2);
            rida++;
            $(el).removeAttr('id');
            $(el).attr('id', "X_sisu_" + rida);
            read = read +"; "+ $(el).attr('id');
        });	


	$("#X_sisu_" + count + ' td > input, select, textarea').each(function(index, Element){
		var Elem = $(Element).attr('onchange');
		if(typeof(Elem) === 'undefined'){} else
		{
		var osad = Elem.split(',');
		var uID = $(Element).attr('id') +'_'+ Math.floor(Math.random() * 10000);
		$(Element).attr('id', uID);
		
		if("'" + sTabel + "'" == osad[2]){
				$(Element).attr('onchange', 
				osad[0].replace('SendInsertData','SendSaveData') +  "," + osad[1] + ","+ osad[2]+", "+data+", '"+uID+"', 'update', "+osad[6]+")");
			};
		}
            

		});	

        $('table#gentbl tbody>tr:last').after(kloon2); 
	$("#X_sisu_" + uus + ' td > input, select, textarea').each(function(index, Element){
                $(Element).attr('value', '');
	});	

}
) ;

}

</script>

<script type="text/javascript">

function SendPostData(Tunnus, Vaartus)
{
$.post("<?=$ISConfig->SavePath?>", 	
	{
	"Tunnus" : Tunnus,
	"Vaartus": Vaartus
	},
    function(data) {
	location.reload();
	}
) ;
}
</script>
