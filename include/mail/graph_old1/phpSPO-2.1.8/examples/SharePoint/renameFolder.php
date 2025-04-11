<?php


require_once('../bootstrap.php');

use Office365\PHP\Client\Runtime\Auth\AuthenticationContext;
use Office365\PHP\Client\Runtime\Utilities\RequestOptions;
use Office365\PHP\Client\SharePoint\ClientContext;

global $Settings;

try {
	$authCtx = new AuthenticationContext($Settings['Url']);
	$authCtx->acquireTokenForUser($Settings['UserName'],$Settings['Password']);

    $folderUrl = "/sites/news/Documents/Archive";
    $folderNewName = "Archive2014";
    renameFolder($Settings['Url'],$authCtx,$folderUrl,$folderNewName);
    
}
catch (Exception $e) {
	echo 'Error: ',  $e->getMessage(), "\n";
}


function renameFolder($webUrl, $authCtx, $folderUrl,$folderNewName)
{
    $url = $webUrl . "/_api/web/getFolderByServerRelativeUrl('{$folderUrl}')/ListItemAllFields";
    $request = new RequestOptions($url);
    $ctx = new ClientContext($url,$authCtx);
    $data = $ctx->executeQueryDirect($request);

    $itemPayload = array( 
        '__metadata' => array ('type' => $data->d->__metadata->type),
        'Title' => $folderNewName,
        'FileLeafRef' => $folderNewName
        );

    $itemUrl = $data->d->__metadata->uri;
    $request = new RequestOptions($itemUrl);
    $request->addCustomHeader("X-HTTP-Method", "MERGE");
    $request->addCustomHeader("If-Match", "*");
    $request->Data = $itemPayload;
    $data = $ctx->executeQueryDirect($request);
}