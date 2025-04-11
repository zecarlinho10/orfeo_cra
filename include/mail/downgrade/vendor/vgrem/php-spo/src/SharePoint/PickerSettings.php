<?php

/**
 * Generated 2020-08-05T10:16:13+00:00 16.0.20315.12009
 */
namespace Office365\SharePoint;

use Office365\SharePoint\UI\ApplicationPages\PeoplePickerQuerySettings;
/**
 * This class 
 * contains configuration settings for the client people picker control hosted by 
 * the SharePoint sharing UI.
 */
class PickerSettings extends BaseEntity
{
    /**
     * @return bool
     */
    public function getAllowEmailAddresses()
    {
        return $this->getProperty("AllowEmailAddresses");
    }
    /**
     * @var bool
     */
    public function setAllowEmailAddresses($value)
    {
        $this->setProperty("AllowEmailAddresses", $value, true);
    }
    /**
     * @return bool
     */
    public function getAllowOnlyEmailAddresses()
    {
        return $this->getProperty("AllowOnlyEmailAddresses");
    }
    /**
     * @var bool
     */
    public function setAllowOnlyEmailAddresses($value)
    {
        $this->setProperty("AllowOnlyEmailAddresses", $value, true);
    }
    /**
     * @return string
     */
    public function getPrincipalAccountType()
    {
        return $this->getProperty("PrincipalAccountType");
    }
    /**
     * @var string
     */
    public function setPrincipalAccountType($value)
    {
        $this->setProperty("PrincipalAccountType", $value, true);
    }
    /**
     * @return integer
     */
    public function getPrincipalSource()
    {
        return $this->getProperty("PrincipalSource");
    }
    /**
     * @var integer
     */
    public function setPrincipalSource($value)
    {
        $this->setProperty("PrincipalSource", $value, true);
    }
    /**
     * @return PeoplePickerQuerySettings
     */
    public function getQuerySettings()
    {
        return $this->getProperty("QuerySettings", new PeoplePickerQuerySettings());
    }
    /**
     * @var PeoplePickerQuerySettings
     */
    public function setQuerySettings($value)
    {
        $this->setProperty("QuerySettings", $value, true);
    }
    /**
     * @return integer
     */
    public function getVisibleSuggestions()
    {
        return $this->getProperty("VisibleSuggestions");
    }
    /**
     * @var integer
     */
    public function setVisibleSuggestions($value)
    {
        $this->setProperty("VisibleSuggestions", $value, true);
    }
    /**
     * @return bool
     */
    public function getUseSubstrateSearch()
    {
        return $this->getProperty("UseSubstrateSearch");
    }
    /**
     * @var bool
     */
    public function setUseSubstrateSearch($value)
    {
        $this->setProperty("UseSubstrateSearch", $value, true);
    }
}