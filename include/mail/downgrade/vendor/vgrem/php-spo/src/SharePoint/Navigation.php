<?php

/**
 * Generated 2019-11-17T16:35:02+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

/**
 * Represents 
 * navigation operations at the site collection 
 * level. 
 */
class Navigation extends BaseEntity
{
    /**
     * @return bool
     */
    public function getUseShared()
    {
        return $this->getProperty("UseShared");
    }
    /**
     * @var bool
     */
    public function setUseShared($value)
    {
        $this->setProperty("UseShared", $value, true);
    }
}