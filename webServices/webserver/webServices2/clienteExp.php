<?php 
error_reporting(7);
require_once('../webServices/nusoap/lib/nusoap.php');

$wsdl="http://localhost/orfeoCore/webServices2/servidor.php?wsdl"; 


$params  = array("soap_version"=> SOAP_1_2,
                "trace"=>1,
                "exceptions"=>0,
                );
$client=new soapclient2($wsdl, $params); 



echo "Paso 1 <hr>";
$arregloDatos = array();

$arregloDatos[0] = "";
$arregloDatos[1] = "79802120";

$a = $client->call( 'getExpediente', $arregloDatos );
echo "Paso 3 <hr>";
// Display the result
print_r($a);
echo "ddd";
var_dump( $a );


echo "Paso 4 <hr>";


?>


<?php
$cliente = new SoapClient($wsdl);
print "<p>xxxxx : ";
$vem = $cliente->__call('getExpediente',$arregloDatos);
print $vem;
print "</p>";
?>