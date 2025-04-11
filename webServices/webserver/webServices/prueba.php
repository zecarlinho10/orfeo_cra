<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', "On");

    $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.100.31)(PORT = 1521)))(CONNECT_DATA=(SID=DSSDB)))" ;

    if($c = OCILogon("XROAD", "XROAD", $db))
    {
        echo "Successfully connected to Oracle.\n";
        OCILogoff($c);
    }
    else
    {
        $err = OCIError();
        //echo "Connection failed." . $err[text];
        echo "Connection failed.";
    }

?>