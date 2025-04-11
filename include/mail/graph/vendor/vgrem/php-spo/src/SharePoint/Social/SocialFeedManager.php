<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\Social;


use Office365\SharePoint\BaseEntity;


class SocialFeedManager extends BaseEntity
{
    /**
     * @return SocialActor
     */
    public function getOwner()
    {
        return $this->getProperty("Owner", new SocialActor());
    }
    /**
     * @var SocialActor
     */
    public function setOwner($value)
    {
        $this->setProperty("Owner", $value, true);
    }
    /**
     * @return string
     */
    public function getPersonalSitePortalUri()
    {
        return $this->getProperty("PersonalSitePortalUri");
    }
    /**
     * @var string
     */
    public function setPersonalSitePortalUri($value)
    {
        $this->setProperty("PersonalSitePortalUri", $value, true);
    }
}
