<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\Social;

use Office365\SharePoint\BaseEntity;


class SocialRestFeed extends BaseEntity
{
    /**
     * @return SocialFeed
     */
    public function getSocialFeed()
    {
        return $this->getProperty("SocialFeed", new SocialFeed());
    }

    /**
     *
     * @return SocialRestFeed
     * @var SocialFeed
     */
    public function setSocialFeed($value)
    {
        $this->setProperty("SocialFeed", $value, true);
        return $this;
    }
}
