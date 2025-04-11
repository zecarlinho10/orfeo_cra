<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

/**
 * Specifies 
 * a change on a group.The RelativeTime property is not included in the default 
 * scalar property set for this type.
 */
class ChangeGroup extends Change
{
    /**
     * @return integer
     */
    public function getGroupId()
    {
        return $this->getProperty("GroupId");
    }

    /**
     *
     * @return self
     * @var integer
     */
    public function setGroupId($value)
    {
        return $this->setProperty("GroupId", $value, true);
    }
}