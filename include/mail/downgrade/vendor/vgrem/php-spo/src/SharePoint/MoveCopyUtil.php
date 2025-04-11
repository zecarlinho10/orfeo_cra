<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\Actions\InvokePostMethodQuery;
use Office365\Runtime\ClientObject;


/**
 * A 
 * container class for static move/copy methods.
 */
class MoveCopyUtil extends ClientObject
{

    /**
     * @param ClientContext $context
     * @param string $srcUrl
     * @param string $destUrl
     * @param boolean $overwrite
     * @param MoveCopyOptions $options
     * @return ClientObject
     */
    public static function moveFile($context, $srcUrl, $destUrl, $overwrite, $options){
        $util = new MoveCopyUtil($context);
        $qry = new InvokePostMethodQuery($util, "MoveFile", null, null,
            array("srcUrl" => $srcUrl, "destUrl" => $destUrl, "overwrite" => $overwrite, "options" => $options));
        $qry->IsStatic = true;
        $context->addQuery($qry);
        return $util;
    }
}
