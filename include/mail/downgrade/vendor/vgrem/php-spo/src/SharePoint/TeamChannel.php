<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

/**
 * This class 
 * is a placeholder for all TeamChannel related methods.
 */
class TeamChannel extends BaseEntity
{
    /**
     * @return string
     */
    public function getfolderId()
    {
        if (!$this->isPropertyAvailable("folderId")) {
            return null;
        }
        return $this->getProperty("folderId");
    }
    /**
     * @var string
     */
    public function setfolderId($value)
    {
        $this->setProperty("folderId", $value, true);
    }
    /**
     * @return integer
     */
    public function getgroupId()
    {
        if (!$this->isPropertyAvailable("groupId")) {
            return null;
        }
        return $this->getProperty("groupId");
    }
    /**
     * @var integer
     */
    public function setgroupId($value)
    {
        $this->setProperty("groupId", $value, true);
    }
}