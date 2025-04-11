<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\Social;

use Office365\SharePoint\BaseEntity;

class SocialFollowingManager extends BaseEntity
{
    /**
     * @return string
     */
    public function getFollowedDocumentsUri()
    {
        if (!$this->isPropertyAvailable("FollowedDocumentsUri")) {
            return null;
        }
        return $this->getProperty("FollowedDocumentsUri");
    }
    /**
     * @var string
     */
    public function setFollowedDocumentsUri($value)
    {
        $this->setProperty("FollowedDocumentsUri", $value, true);
    }
    /**
     * @return string
     */
    public function getFollowedSitesUri()
    {
        if (!$this->isPropertyAvailable("FollowedSitesUri")) {
            return null;
        }
        return $this->getProperty("FollowedSitesUri");
    }
    /**
     * @var string
     */
    public function setFollowedSitesUri($value)
    {
        $this->setProperty("FollowedSitesUri", $value, true);
    }
}
