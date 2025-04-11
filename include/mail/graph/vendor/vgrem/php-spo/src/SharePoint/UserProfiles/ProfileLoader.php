<?php

/**
 * Generated 2019-11-16T20:09:17+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\UserProfiles;

use Office365\Runtime\Actions\InvokePostMethodQuery;
use Office365\Runtime\ClientRuntimeContext;
use Office365\Runtime\ResourcePath;
use Office365\Runtime\ServerTypeInfo;
use Office365\SharePoint\BaseEntity;

class ProfileLoader extends BaseEntity
{
    public function __construct(ClientRuntimeContext $context)
    {
        parent::__construct($context, new ResourcePath("SP.UserProfiles.ProfileLoader"));
    }


    /**
     * @param ClientRuntimeContext $context
     * @return ProfileLoader
     */
    public static function getProfileLoader(ClientRuntimeContext $context)
    {
        $loader = new ProfileLoader($context);
        $qry = new InvokePostMethodQuery($loader, "GetProfileLoader");
        $qry->IsStatic = true;
        $context->addQueryAndResultObject($qry, $loader);
        return $loader;
    }

    /**
     * @return ServerTypeInfo
     */
    function getServerTypeInfo()
    {
        return new ServerTypeInfo("SP.UserProfiles", "ProfileLoader");
    }

}
