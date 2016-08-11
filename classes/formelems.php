<?php

class InPutBase
{
    var $_name;
    var $_rowid;
    var $_tablename;
    var $_colname;
    var $_coltype;
    var $_value;
    var $_longvalue;
    var $_uid;
    var $_meetod;
    var $_readonly;

	

    function __construct($Name='', $Table='', $Column='', $RowID=0, $ColType='', $Value='', $Meetod='update')
    {
        $this->set_uid();
        $this->set_value(stripslashes($Value));
        $this->set_tablename($Table);
        $this->set_colname($Column);
        $this->set_coltype($ColType);
        $this->set_rowid($RowID);
        $this->set_name($Name);
        $this->set_meetod($Meetod);
        $this->set_readonly(0);
    }

	
	/*
	Paneme paika nime
	*/

	function set_name($new_name)
	{
		$this->_name = $new_name;
	}

	function get_name()
	{
		return $this->_name;
	}

	/*
	Paneme paika nime
	*/

	function set_readonly($new_name)
	{
		$this->_readonly = $new_name;
	}

	function get_readonly()
	{
		return $this->_readonly;
	}
    
    
	/*
	Paneme paika nime
	*/

	function set_meetod($new_name)
	{
		$this->_meetod = $new_name;
	}

	function get_meetod()
	{
		return $this->_meetod;
	}

	/*
	Paneme paika rowid
	*/
	function set_rowid($new_rowid)
	{
		$this->_rowid = $new_rowid;
	}

	function get_rowid()
	{
		return $this->_rowid;
	}

	/*
	Paneme paika _tablename
	*/
	function set_tablename($new_tablename)
	{
		$this->_tablename = $new_tablename;
	}

	function get_tablename()
	{
		return $this->_tablename;
	}

	/*
	Paneme paika _colname
	*/
	function set_colname($new_colname)
	{
		$this->_colname = $new_colname;
	}

	function get_colname()
	{
		return $this->_colname;
	}

	/*
	Paneme paika _coltype
	*/
	function set_coltype($new_coltype)
	{
		$this->_coltype = $new_coltype;
	}

	function get_coltype()
	{
		return $this->_coltype;
	}

	/*
	Paneme paika _value
	*/
	function set_value($new_value)
	{
		$this->_value = $new_value;
	}

	function get_value()
	{
		return $this->_value;
	}

	/*
	Paneme paika _uid
	*/
	function set_uid()
	{
		$this->_uid = "X" . substr(md5(rand()),0,20);
	}

	function get_uid()
	{
		return $this->_uid;
	}

	function get_onClick()
	{
		//if($_SESSION["SysAdmin"]):
//            $Tulem = sprintf("onClick=\"NaitaSisu('%s')\"", $this->get_uid());
//            else:
            $Tulem = "";
//        endif;
        return $Tulem;
            //;
	}

	function get_onChange()
	{
		return sprintf("onchange=\"SendSaveData(this.value,'%s','%s', %d, '%s','%s', '%s')\"", 
			$this->get_colname(), 
            $this->get_tablename(), 
            $this->get_rowid(), 
            $this->get_uid(), 
            $this->get_meetod(), 
            substr($this->get_name(), 2, 1));
	}

	function get_onChangeInsert($ULname, $ULvalue)
	{
		return sprintf("onchange=\"SendInsertData(this.value,'%s','%s', %d, '%s','%s', '%s', '%s', '%s')\"", 
			$this->get_colname(), 
            $this->get_tablename(), 
            $this->get_rowid(), 
            $this->get_uid(), 
            'insert', 
            substr($this->get_name(),2,1), 
            $ULname, 
            $ULvalue);
	}

}

class InputTextBox extends InPutBase
{
	/*
	var $_name;
	var $_rowid;
	var $_tablename;
	var $_colname;
	var $_coltype;
	var $_value;
	var $_uid;
	*/

	var $_size;

	/*
	Paneme paika _value
	*/
	function set_size($new_value)
	{
		$this->_size = $new_value;
	}

	function get_size()
	{
		return $this->_size;
	}

	function get_Input()
	{
            // , $this->get_coltype() == 'd' ? "class='datepicker'" : ""
		$Sisu = sprintf("\n<INPUT id=\"%s\" type=\"text\" value=\"%s\" size=\"%d\" %s %s class='%s'>\n", 
                                        $this->get_uid(), 
                                        $this->get_value(), 
                                        $this->get_size(), 
                                        $this->get_onChange(), 
                                        $this->get_onClick(), 
                                        substr($this->get_name(),2,1));

        if($this->get_readonly()) $Sisu = $this->get_value();
        
        return $Sisu;
	}

	function get_InputInsert($ULname, $ULvalue)
	{
            return sprintf("\n<INPUT id='%s' type='text' value='%s' size='%d' %s %s>\n", 
                            $this->get_uid(), 
                            $this->get_value(), 
                            $this->get_size(), 
                            $this->get_onChangeInsert($ULname, $ULvalue), 
                            $this->get_onClick());
	}

}

class TextAreaTextBox extends InPutBase
{
	/*
	var $_name;
	var $_rowid;
	var $_tablename;
	var $_colname;
	var $_coltype;
	var $_value;
	var $_uid;
	*/

	var $_rows;
	var $_cols;

	/*
	Paneme paika _rows
	*/
	function set_rows($new_value)
	{
		$this->_rows = $new_value;
	}

	function get_rows()
	{
		return $this->_rows;
	}

	/*
	Paneme paika _cols
	*/
	function set_cols($new_value)
	{
		$this->_cols = $new_value;
	}

	function get_cols()
	{
		return $this->_cols;
	}

	function get_Input()
	{
            //$this->set_readonly(1);

            $Sisu = sprintf("\n<TEXTAREA id='%s' %s rows='%d' cols='%d'>%s</TEXTAREA>", 
                                    $this->get_uid(), 
                                    $this->get_onChange(), 
                                    $this->get_rows(), 
                                    $this->get_cols(), 
                                    $this->get_value());
            if($this->get_readonly()) $Sisu = $this->get_value();

            return $Sisu;
	}

	function get_InputInsert($ULname, $ULvalue)
	{
		return sprintf("\n<TEXTAREA id='%s' %s rows='%d' cols='%d'>%s</TEXTAREA>", 
                                        $this->get_uid(), 
                                        $this->get_onChangeInsert($ULname, $ULvalue), 
                                        $this->get_rows(), 
                                        $this->get_cols(), 
                                        $this->get_value());
	}

}

class SelectTextBox extends InPutBase
{
	var $_select;
	var $_idcol;
	var $_namecol;
	var $SingleText;

	function set_selnames($idcol, $namecol)
	{
		$this->_idcol = $idcol;
		$this->_namecol = $namecol;
	}

	/*
	Paneme paika _cols
	*/
        
	function set_select($src_table, $order, $filter = NULL)
	{
		global $db;
        if(is_null($filter))
		{
			$paring = sprintf("select *, substring(%s, 1, 40) as nimi40c from %s order by %s", $this->_namecol, $src_table, $order);
		} else
		{
			$paring = sprintf("select *, substring(%s, 1, 40) as nimi40c from %s where %s order by %s", $this->_namecol, $src_table, $filter, $order);
		}
		
		$db->set_paring($paring);
		$Rows = $db->execute();
		
		$this->_select = "<option value='0'>-- Puudub --</option>\n";
		while($Row = ab_fetch_array($Rows))
		{

                $Nimetus = $Row['nimi40c'];
                $sisu = $Row[$this->_idcol] ? sprintf("(%s) %s", $Row[$this->_idcol], $Nimetus) : $Nimetus ;

                $this->_select .= sprintf("<option value='%s' %s>%s</option>\n",
                            $Row[$this->_idcol], 
                            $Row[$this->_idcol]==$this->get_value() ? " selected " : "", 
                            $sisu 
                            );

                            if($Row[$this->_idcol]==$this->get_value()):
                                $this->SingleText = $Nimetus;
                            endif;
                                                    
		}


	}

	function get_select()
	{
		return $this->_select;
	}	
	
	function get_Input()
	{

            //$this->set_readonly(1);
            
            $Tulemus = sprintf("\n<select id='%s' %s>",  
                                $this->get_uid(), 
                                $this->get_onChange());

            $Tulemus .= $this->get_select();
            $Tulemus .= "</select>";
            if($this->get_readonly()) $Tulemus = $this->SingleText;


            return $Tulemus;
	}

	function get_InputInsert($ULname, $ULvalue)
	{
		$Tulemus = sprintf("\n<select id='%s' %s>",  
                                    $this->get_uid(), 
                                    $this->get_onChangeInsert($ULname, $ULvalue));
                
		$Tulemus .= $this->get_select();
		$Tulemus .= "</select>";
		return $Tulemus;
	}
}


class SelectGroupTextBox extends InPutBase
{
	var $_select;

	var $_idcol;
	var $_namecol;
        var $SingleText;

	function set_selnames($idcol, $namecol)
	{
		$this->_idcol = $idcol;
		$this->_namecol = $namecol;
	}

	/*
	Paneme paika _cols
	*/
        
	function set_select($src_table, $order, $group)
	{
		global $db;
                                
		$db->set_paring(sprintf("select %s from %s group by 1 order by 1", $group, $src_table));
                $Group = $db->execute();
		$this->_select = "<option value='0'>-- Puudub --</option>\n";
        
		while($GroupID = ab_fetch_array($Group))
		{
			$par = sprintf("select * from %s where %s = '%s' order by %s", $src_table, $group, $GroupID[0], $order);
			//echo "<!-- $par -->"; 
					$db->set_paring($par);
			$Rows = $db->execute();
			$this->_select .= sprintf("<optgroup label = '%s'>\n", Get_Value($GroupID, "$group"));
			while($Row = ab_fetch_array($Rows))
			{

				$Nimetus = substr($Row[$this->_namecol], 0, 40);
				$this->_select .= sprintf("<option value='%s' %s>(%s) %s</option>\n",
				$Row[$this->_idcol], 
				$Row[$this->_idcol]==$this->get_value() ? " selected " : "", 
				$Row[$this->_idcol],
				$Nimetus 
				);

				if($Row[$this->_idcol]==$this->get_value()):
					$this->SingleText = $Nimetus;
				endif;

			}
			$this->_select .= "</optgroup>\n";
		}


	}

	function get_select()
	{
		return $this->_select;
	}	
	
	function get_Input()
	{

            //$this->set_readonly(1);
            
            $Tulemus = sprintf("\n<select id='%s' %s>",  
                                $this->get_uid(), 
                                $this->get_onChange());

            $Tulemus .= $this->get_select();
            $Tulemus .= "</select>";
            if($this->get_readonly()) $Tulemus = $this->SingleText;


            return $Tulemus;
	}

	function get_InputInsert($ULname, $ULvalue)
	{
		$Tulemus = sprintf("\n<select id='%s' %s>",  
                                    $this->get_uid(), 
                                    $this->get_onChangeInsert($ULname, $ULvalue));
                
		$Tulemus .= $this->get_select();
		$Tulemus .= "</select>";
		return $Tulemus;
	}
}


class SelectFiltTextBox extends InPutBase
{
	var $_select;

	var $_idcol;
	var $_namecol;
	var $SingleText;

	function set_selnames($idcol, $namecol)
	{
		$this->_idcol = $idcol;
		$this->_namecol = $namecol;
	}

	/*
	Paneme paika _cols
	*/
	function set_select($src_table, $order, $filter)
	{
            global $db;

            $db->set_paring(sprintf("select * from %s where %s order by %s", $src_table, $filter, $order));
            $Rows = $db->execute();

            $this->_select = "<option value='0'>-- Puudub --</option>\n";
            while($Row = ab_fetch_array($Rows))
            {
				
				$Nimetus = substr($Row[$this->_namecol], 0, 40);
				$this->_select .= sprintf("<option value='%s' %s>(%s) %s</option>\n",
				$Row[$this->_idcol], 
				$Row[$this->_idcol]==$this->get_value() ? " selected " : "", 
				$Row[$this->_idcol],
				$Nimetus 
				);

				if($Row[$this->_idcol]==$this->get_value()):
					$this->SingleText = $Nimetus;
				endif;

				/*
				$Nimetus = substr($Row[$this->_namecol], 0, 40);
                $this->_select .= sprintf("<option value='%s' %s>%s (%s)</option>\n",
                $Row[$this->_idcol], 
                $Row[$this->_idcol]==$this->get_value() ? " selected " : "", 
                $Nimetus, 
                $Row[$this->_idcol]);

                if($Row[$this->_idcol]==$this->get_value()):
                    $this->SingleText = $Nimetus;
                endif;
				 * 
				 */

            }


	}

	function get_select()
	{
		return $this->_select;
	}	
	
	function get_Input()
	{
            //$this->set_readonly(1);
            $Tulemus = sprintf("\n<select id='%s' %s>",  
                                    $this->get_uid(), 
                                    $this->get_onChange());
            $Tulemus .= $this->get_select();
            $Tulemus .= "</select>";
            if($this->get_readonly()) $Tulemus = $this->SingleText;
            return $Tulemus;
	}

	function get_InputInsert($ULname, $ULvalue)
	{
            $Tulemus = sprintf("\n<select id='%s' %s>",  
                                    $this->get_uid(), 
                                    $this->get_onChangeInsert($ULname, $ULvalue));

            $Tulemus .= $this->get_select();
            $Tulemus .= "</select>";
            return $Tulemus;
	}
}

class CheckBox extends InPutBase
{
	/*
	var $_name;
	var $_rowid;
	var $_tablename;
	var $_colname;
	var $_coltype;
	var $_value;
	var $_uid;
	*/

	var $_size;

	/*
	Paneme paika _value
	*/
	function set_size($new_value)
	{
		$this->_size = $new_value;
	}

	function get_size()
	{
		return $this->_size;
	}

	function get_onChange()
	{
		return sprintf("onchange=\"SendSaveData(CheckToNr(this.checked),'%s','%s', %d, '%s','%s', '%s')\"", 
			$this->get_colname(), 
            $this->get_tablename(), 
            $this->get_rowid(), 
            $this->get_uid(), 
            $this->get_meetod(), 
            substr($this->get_name(), 2, 1));
	}

	function get_onChangeInsert($ULname, $ULvalue)
	{
		return sprintf("onchange=\"SendInsertData(CheckToNr(this.checked),'%s','%s', %d, '%s','%s', '%s', '%s', '%s')\"", 
			$this->get_colname(), 
            $this->get_tablename(), 
            $this->get_rowid(), 
            $this->get_uid(), 
            'insert', 
            substr($this->get_name(),2,1), 
            $ULname, 
            $ULvalue);
	}	
	
       
	function get_Input()
	{
            //$this->set_readonly(1);
                    // TODO: muuta sisend
            $Tulemus =  sprintf("\n<INPUT id='%s' type='checkbox' %s size='%d' %s %s >\n", 
                    $this->get_uid(), 
                    $this->get_value() ? "checked" : "", 
                    $this->get_size(), 
                    $this->get_onChange(), 
                    $this->get_onClick());

            if($this->get_readonly()) $Tulemus = $this->get_value() ? Get_GlobalStr('strYes') : Get_GlobalStr('strNo');

            return $Tulemus;
            
        }

	function get_InputInsert($ULname, $ULvalue)
	{
            return sprintf("\n<INPUT id='%s' type='checkbox' value='%s' size='%d' %s %s>\n", 
                $this->get_uid(), 
                $this->get_value(), 
                $this->get_size(), 
                $this->get_onChangeInsert($ULname, $ULvalue), 
                $this->get_onClick());
	}

}