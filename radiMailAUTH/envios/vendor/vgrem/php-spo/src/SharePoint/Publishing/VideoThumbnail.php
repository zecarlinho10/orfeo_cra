<?php

/**
 * Generated 2019-11-16T20:28:28+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\Publishing;

use Office365\SharePoint\BaseEntity;

class VideoThumbnail extends BaseEntity
{
    /**
     * @return integer
     */
    public function getChoice()
    {
        return $this->getProperty("Choice");
    }
    /**
     * @var integer
     */
    public function setChoice($value)
    {
        $this->setProperty("Choice", $value, true);
    }
    /**
     * @return bool
     */
    public function getIsSelected()
    {
        return $this->getProperty("IsSelected");
    }
    /**
     * @var bool
     */
    public function setIsSelected($value)
    {
        $this->setProperty("IsSelected", $value, true);
    }
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getProperty("Url");
    }
    /**
     * @var string
     */
    public function setUrl($value)
    {
        $this->setProperty("Url", $value, true);
    }
}