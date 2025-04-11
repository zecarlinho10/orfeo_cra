<?php

namespace Argo\OAUTH;

use Office365\GraphServiceClient;
use Argo\OAUTH\Provider;
use Office365\Runtime\Http\RequestOptions;
use Office365\Runtime\Http\HttpMethod;
use Office365\Runtime\OData\ODataRequest;
use Office365\Runtime\OData\V4\JsonFormat;
use Office365\Runtime\OData\ODataMetadataLevel;

class GhapClientMS extends GraphServiceClient{
	protected $oauthProvider ;
	protected $pendingRequests;
	public function __construct(Provider $oauthProvider){
		$this->oauthProvider = $oauthProvider;
		$this->getPendingRequests()->beforeExecuteRequest(function (RequestOptions $request) {
            		$this->authenticateRequest($request);
            		$this->prepareRequests($request);
		});
        parent::__construct("time");
	}
	protected function prepareRequests(RequestOptions $request){

		$query = $this->getCurrentQuery();

		//set data modification headers
		if ($query instanceof UpdateEntityQuery) {
			$request->Method = HttpMethod::Patch;
		} else if ($query instanceof DeleteEntityQuery) {
			$request->Method = HttpMethod::Delete;
		}
	}
	public function authenticateRequest(RequestOptions $options){
		$token = $this->oauthProvider->acquireToken();

        $headerVal = $token['token_type'] . ' ' . $token['access_token'];
        $options->ensureHeader('Authorization', $headerVal);
	}

	    /**
	     *      * @return ODataRequest
	     *           */
	public     function getPendingRequests(){
		if(!$this->pendingRequests){
		       	$this->pendingRequests = new ODataRequest(new JsonFormat(ODataMetadataLevel::Verbose));
		}
		return $this->pendingRequests;
	}
}	

?>
