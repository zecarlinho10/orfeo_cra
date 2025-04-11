<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\Microfeed;

use Office365\Runtime\ClientObject;

class MicrofeedManager extends ClientObject
{
    /**
     * @return MicroBlogEntity
     */
    public function getCurrentUser()
    {
        return $this->getProperty("CurrentUser", new MicroBlogEntity());
    }
    /**
     * @var MicroBlogEntity
     */
    public function setCurrentUser($value)
    {
        $this->setProperty("CurrentUser", $value, true);
    }
    /**
     * @return bool
     */
    public function getIsFeedActivityPublic()
    {
        return $this->getProperty("IsFeedActivityPublic");
    }
    /**
     * @var bool
     */
    public function setIsFeedActivityPublic($value)
    {
        $this->setProperty("IsFeedActivityPublic", $value, true);
    }
    /**
     * @return string
     */
    public function getStaticThreadLink()
    {
        return $this->getProperty("StaticThreadLink");
    }
    /**
     * @var string
     */
    public function setStaticThreadLink($value)
    {
        $this->setProperty("StaticThreadLink", $value, true);
    }
}
