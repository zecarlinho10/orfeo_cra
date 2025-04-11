<?php

/**
 * Generated 2019-11-16T20:05:23+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\UserProfiles;

use Office365\SharePoint\BaseEntity;


class PersonProperties extends BaseEntity
{
    /**
     * @return string
     */
    public function getAccountName()
    {
        return $this->getProperty("AccountName");
    }
    /**
     * @var string
     */
    public function setAccountName($value)
    {
        $this->setProperty("AccountName", $value, true);
    }
    /**
     * @return array
     */
    public function getDirectReports()
    {
        return $this->getProperty("DirectReports");
    }
    /**
     * @var array
     */
    public function setDirectReports($value)
    {
        $this->setProperty("DirectReports", $value, true);
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->getProperty("DisplayName");
    }
    /**
     * @var string
     */
    public function setDisplayName($value)
    {
        $this->setProperty("DisplayName", $value, true);
    }
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getProperty("Email");
    }
    /**
     * @var string
     */
    public function setEmail($value)
    {
        $this->setProperty("Email", $value, true);
    }
    /**
     * @return array
     */
    public function getExtendedManagers()
    {
        return $this->getProperty("ExtendedManagers");
    }
    /**
     * @var array
     */
    public function setExtendedManagers($value)
    {
        $this->setProperty("ExtendedManagers", $value, true);
    }
    /**
     * @return array
     */
    public function getExtendedReports()
    {
        return $this->getProperty("ExtendedReports");
    }
    /**
     * @var array
     */
    public function setExtendedReports($value)
    {
        $this->setProperty("ExtendedReports", $value, true);
    }
    /**
     * @return bool
     */
    public function getIsFollowed()
    {
        return $this->getProperty("IsFollowed");
    }
    /**
     * @var bool
     */
    public function setIsFollowed($value)
    {
        $this->setProperty("IsFollowed", $value, true);
    }
    /**
     * @return string
     */
    public function getLatestPost()
    {
        return $this->getProperty("LatestPost");
    }
    /**
     * @var string
     */
    public function setLatestPost($value)
    {
        $this->setProperty("LatestPost", $value, true);
    }
    /**
     * @return array
     */
    public function getPeers()
    {
        return $this->getProperty("Peers");
    }
    /**
     * @var array
     */
    public function setPeers($value)
    {
        $this->setProperty("Peers", $value, true);
    }
    /**
     * @return string
     */
    public function getPersonalSiteHostUrl()
    {
        return $this->getProperty("PersonalSiteHostUrl");
    }
    /**
     * @var string
     */
    public function setPersonalSiteHostUrl($value)
    {
        $this->setProperty("PersonalSiteHostUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getPersonalUrl()
    {
        return $this->getProperty("PersonalUrl");
    }
    /**
     * @var string
     */
    public function setPersonalUrl($value)
    {
        $this->setProperty("PersonalUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->getProperty("PictureUrl");
    }
    /**
     * @var string
     */
    public function setPictureUrl($value)
    {
        $this->setProperty("PictureUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getProperty("Title");
    }
    /**
     * @var string
     */
    public function setTitle($value)
    {
        $this->setProperty("Title", $value, true);
    }
    /**
     * @return array
     */
    public function getUserProfileProperties()
    {
        return $this->getProperty("UserProfileProperties");
    }
    /**
     * @var array
     */
    public function setUserProfileProperties($value)
    {
        $this->setProperty("UserProfileProperties", $value, true);
    }
    /**
     * @return string
     */
    public function getUserUrl()
    {
        return $this->getProperty("UserUrl");
    }
    /**
     * @var string
     */
    public function setUserUrl($value)
    {
        $this->setProperty("UserUrl", $value, true);
    }
}
