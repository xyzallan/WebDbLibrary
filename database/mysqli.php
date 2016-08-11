<?php
/**
 * WebISLib
 * 
 * @copyright  Copyright (c) 2006 - 2014 Allan Sims
 * @category database
 * @author Allan Sims <allan.sims@emu.ee>
 * 
*/
class is_data 
{

    private $host;
    private $user;
    private $pass;
    private $data;
    public  $Viga;

    function __construct()
    {
        global $ISConfig;
        $this->host = $ISConfig->hostname;
        $this->user = $ISConfig->username;
        $this->pass = $ISConfig->password;
        $this->data = $ISConfig->database;
    }


    function set_paring($kask)
    {
        $this->paring = $kask;
    }

    function execute($PrintViga = 1)
    {

        $this->Viga = 0;

        $mysqli = new mysqli($this->host, $this->user, $this->pass, $this->data);
        
        if ($mysqli->connect_errno):
            echo "Andmebaasiga ühendus ebaõnnestus: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            return;
        endif;

        if(($res = $mysqli->query($this->paring))===false):

            if($PrintViga == 1):
                $this->PrintError($mysqli);
            endif;

            $this->Viga = $mysqli->errno;
        
        endif;

        $mysqli->close();

        return $res;

    }

    function executeID($PrintViga = 1)
    {

        $mysqli = new mysqli($this->host, $this->user, $this->pass, $this->data);

        if ($mysqli->connect_errno):
            echo "Andmebaasiga ühendus ebaõnnestus: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            return;
        endif;


        if(($res = $mysqli->query($this->paring))===false):
            if($PrintViga == 1):
                $this->PrintError($mysqli);
            endif;
        endif;

        $U_ID = $mysqli->insert_id;
        $mysqli->close();

        return $U_ID;

    }
    
    function PrintError($msobj)
    {
        global $ISConfig;
        global $Page_Cont_Row;
        
        printf("\nViga: %s; päring: %s\n; andmebaas: %s", $msobj->error, $this->paring, $this->data);
        $VeaTeade = date("Y:m:d, H:i:s") . ': ' . $msobj->error . 
                ' (' . $this->paring .') '. filter_input(INPUT_SERVER,'REMOTE_ADDR') . "\n". 
                ' Leht: ' . filter_input(INPUT_GET, 'do') . 
                ' Kasutaja: ' . Get_Value($_SESSION, 'UserName') .
                ' Page' . serialize($Page_Cont_Row);
        $VeaTeade .= "\nInfosüsteem: " . $ISConfig->Title;

        $nimi = substr($this->data, 0, -2);
        $kuup = date("Y-m");
        error_log($VeaTeade, 3,  RPath . "/tmp/error-${kuup}.log");

        if(Get_Value($_SESSION,"SysAdmin", 0) == 0):
            error_log($VeaTeade, 1, "allan.sims@emu.ee", "Subject: Veateade\nFrom: ${nimi}@${nimi}.emu.ee");
        endif;

        
    }


}

function ab_fetch_array($res)
{
    return $res->fetch_array();
}

function ab_fetch_array_name($res)
{
    return $res->fetch_array(MYSQLI_ASSOC);
}

function ab_fetch_row($res)
{
    return $res->fetch_row();
}

function ab_fetch_object($res)
{
    return $res->fetch_object();
}

function ab_num_rows($res)
{
    return $res->num_rows;
}

function ab_aff_rows($res)
{
    return $res->affected_rows;
}