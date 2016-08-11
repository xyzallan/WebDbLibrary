function Enter2Tab(e, form, obj)
{
	var oid = 0;
	var id = 0;
	for(nimi in form)
	{
		oid++;
		if(nimi == obj.name)
		{
			id = oid;
		}
	}

     var key;

     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox

     if(key == 13) form[id].focus();

}


//if (event.keyCode==13) {event.keyCode=9; return event.keyCode }

