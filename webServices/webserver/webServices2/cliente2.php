<?php # HelloClient.php
# Copyright (c) 2005 by Dr. Herong Yang
#
   $client = new SoapClient(null, array(
      'location' => "http://localhost/orfeocore/webServices2/servidor2.php",
      'uri'      => "urn://localhost/orfeocore/webService2",
      'trace'    => 1 ));

   $return = $client->__soapCall("getRadsExpediente",array("","79802120"));
   var_dump($return);
   echo("\nReturning value of __soapCall() call: ".$return);

  /**  echo("\nDumping request headers:\n" 
      .$client->__getLastRequestHeaders());

   echo("\nDumping request:\n".$client->__getLastRequest());

   echo("\nDumping response headers:\n"
      .$client->__getLastResponseHeaders());

   echo("\nDumping response:\n".$client->__getLastResponse()); **/
?>



