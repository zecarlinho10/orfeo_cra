<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\ResourcePath;
/**
 * Specifies 
 * the request context information for a site collection when 
 * a SharePoint 
 * Add-in accesses that site collection.
 */
class AppContextSite extends BaseEntity
{
    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->getProperty("Site",
            new Site($this->getContext(),new ResourcePath("Site", $this->getResourcePath())));
    }
    /**
     * @return Web
     */
    public function getWeb()
    {
        return $this->getProperty("Web",
            new Web($this->getContext(), new ResourcePath("Web", $this->getResourcePath())));
    }
}
