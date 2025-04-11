<?php


namespace Argo\OAUTH;

require_once   (realpath(dirname(__FILE__) . "/")."/Provider.php"); 

use Argo\OAUTH\Provider;
use Office365\Runtime\Auth\AADTokenProvider;
use Office365\Runtime\Auth\UserCredentials;

	 
class AuthMSCredentials implements Provider{

   protected $clientId;
   protected $tenatId;
   protected $userAD;
   protected $resource = "https://graph.microsoft.com";

   public function __construct(string $clientId,string $tenatId, UserCredentials $userAD){
	   $this->clientId = $clientId;
	   $this->tenatId = $tenatId;
	   $this->userAD = $userAD; 
   }
   public function setResource(string $resource){
   	$this->resource = $resource;
   }
   public function getResource(){
   	return $this->resource;
   }
   public function acquireToken(){
	   $provider = new AADTokenProvider($this->tenatId);
        return $provider->acquireTokenForPassword($this->resource,$this->clientId,$this->userAD);
   }	
	   
}

?>
