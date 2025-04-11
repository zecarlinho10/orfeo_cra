<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;


/**
 * Specifies 
 * a change from an alert.The RelativeTime property is not included in the default 
 * scalar property set for this type.
 */
class ChangeAlert extends Change
{
    /**
     * @return string
     */
    public function getAlertId()
    {
        return $this->getProperty("AlertId");
    }

    /**
     *
     * @return self
     * @var string
     */
    public function setAlertId($value)
    {
        $this->setProperty("AlertId", $value, true);
        return $this;
    }
    /**
     * @return string
     */
    public function getWebId()
    {
        return $this->getProperty("WebId");
    }
    /**
     * @return self
     * @var string
     */
    public function setWebId($value)
    {
        $this->setProperty("WebId", $value, true);
        return $this;
    }
}