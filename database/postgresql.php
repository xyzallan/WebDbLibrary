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
    public $Driver = 'postgresql';

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
		
		$conn_string = sprintf("host=%s port=5432 dbname=%s user=%s password=%s", $this->host, $this->data, $this->user, $this->pass);
		$dbconn = pg_connect($conn_string) or die('Could not connect: ' . pg_last_error());		

		$this->Viga = 0;

		$result = pg_query($dbconn, $this->paring);
		
		
		
		//if(pg_last_error($dbconn)):
		if(!$result):
			echo "Error " . pg_last_error($dbconn) . ": päring = " . $this->paring;
			debug_print_backtrace();
		endif;
		
		pg_close($dbconn);
        return $result;

    }

    function executeID($PrintViga = 1)
    {

		$conn_string = sprintf("host=%s port=5432 dbname=%s user=%s password=%s", $this->host, $this->data, $this->user, $this->pass);
		$dbconn = pg_connect($conn_string) or die('Could not connect: ' . pg_last_error());		

		$this->Viga = 0;

		$query = $this->paring;
		//echo $query;
		$result = pg_query($dbconn, $query);
		if(pg_last_error($dbconn)):
			echo "Error " . pg_last_error($dbconn) . ": päring = " . $this->paring;
			debug_print_backtrace();
		endif;
		
		$Tul = pg_fetch_array($result);
		
		
		pg_close($dbconn);
        return $Tul[0];

    }
    
    function PrintError($msobj)
    {
    }


}

function ab_fetch_array($res)
{
    return pg_fetch_array($res);
}

function ab_fetch_array_name($res)
{
    return pg_fetch_array($res, NULL, PGSQL_ASSOC);
}

function ab_fetch_row($res)
{
    return pg_fetch_array($res, NULL, PGSQL_NUM);
}

function ab_fetch_object($res)
{
    return pg_fetch_object($res);
}

function ab_num_rows($res)
{
    return pg_num_rows($res);
}

