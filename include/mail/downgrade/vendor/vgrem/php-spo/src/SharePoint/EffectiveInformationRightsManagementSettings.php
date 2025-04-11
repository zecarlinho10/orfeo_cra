<?php

/**
 * Generated 2019-11-17T16:07:15+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

/**
 * A 
 * collection of effective IRM settings on the 
 * file.
 */
class EffectiveInformationRightsManagementSettings extends BaseEntity
{
    /**
     * @return bool
     */
    public function getAllowPrint()
    {
        return $this->getProperty("AllowPrint");
    }
    /**
     * @var bool
     */
    public function setAllowPrint($value)
    {
        $this->setProperty("AllowPrint", $value, true);
    }
    /**
     * @return bool
     */
    public function getAllowScript()
    {
        return $this->getProperty("AllowScript");
    }
    /**
     * @var bool
     */
    public function setAllowScript($value)
    {
        $this->setProperty("AllowScript", $value, true);
    }
    /**
     * @return bool
     */
    public function getAllowWriteCopy()
    {
        return $this->getProperty("AllowWriteCopy");
    }
    /**
     * @var bool
     */
    public function setAllowWriteCopy($value)
    {
        $this->setProperty("AllowWriteCopy", $value, true);
    }
    /**
     * @return bool
     */
    public function getDisableDocumentBrowserView()
    {
        return $this->getProperty("DisableDocumentBrowserView");
    }
    /**
     * @var bool
     */
    public function setDisableDocumentBrowserView($value)
    {
        $this->setProperty("DisableDocumentBrowserView", $value, true);
    }
    /**
     * @return integer
     */
    public function getDocumentAccessExpireDays()
    {
        return $this->getProperty("DocumentAccessExpireDays");
    }
    /**
     * @var integer
     */
    public function setDocumentAccessExpireDays($value)
    {
        $this->setProperty("DocumentAccessExpireDays", $value, true);
    }
    /**
     * @return string
     */
    public function getDocumentLibraryProtectionExpireDate()
    {
        return $this->getProperty("DocumentLibraryProtectionExpireDate");
    }
    /**
     * @var string
     */
    public function setDocumentLibraryProtectionExpireDate($value)
    {
        $this->setProperty("DocumentLibraryProtectionExpireDate", $value, true);
    }
    /**
     * @return bool
     */
    public function getEnableDocumentAccessExpire()
    {
        return $this->getProperty("EnableDocumentAccessExpire");
    }
    /**
     * @var bool
     */
    public function setEnableDocumentAccessExpire($value)
    {
        $this->setProperty("EnableDocumentAccessExpire", $value, true);
    }
    /**
     * @return bool
     */
    public function getEnableDocumentBrowserPublishingView()
    {
        return $this->getProperty("EnableDocumentBrowserPublishingView");
    }
    /**
     * @var bool
     */
    public function setEnableDocumentBrowserPublishingView($value)
    {
        $this->setProperty("EnableDocumentBrowserPublishingView", $value, true);
    }
    /**
     * @return bool
     */
    public function getEnableGroupProtection()
    {
        return $this->getProperty("EnableGroupProtection");
    }
    /**
     * @var bool
     */
    public function setEnableGroupProtection($value)
    {
        $this->setProperty("EnableGroupProtection", $value, true);
    }
    /**
     * @return bool
     */
    public function getEnableLicenseCacheExpire()
    {
        return $this->getProperty("EnableLicenseCacheExpire");
    }
    /**
     * @var bool
     */
    public function setEnableLicenseCacheExpire($value)
    {
        $this->setProperty("EnableLicenseCacheExpire", $value, true);
    }
    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->getProperty("GroupName");
    }
    /**
     * @var string
     */
    public function setGroupName($value)
    {
        $this->setProperty("GroupName", $value, true);
    }
    /**
     * @return bool
     */
    public function getIrmEnabled()
    {
        return $this->getProperty("IrmEnabled");
    }
    /**
     * @var bool
     */
    public function setIrmEnabled($value)
    {
        $this->setProperty("IrmEnabled", $value, true);
    }
    /**
     * @return integer
     */
    public function getLicenseCacheExpireDays()
    {
        return $this->getProperty("LicenseCacheExpireDays");
    }
    /**
     * @var integer
     */
    public function setLicenseCacheExpireDays($value)
    {
        $this->setProperty("LicenseCacheExpireDays", $value, true);
    }
    /**
     * @return string
     */
    public function getPolicyDescription()
    {
        return $this->getProperty("PolicyDescription");
    }
    /**
     * @var string
     */
    public function setPolicyDescription($value)
    {
        $this->setProperty("PolicyDescription", $value, true);
    }
    /**
     * @return string
     */
    public function getPolicyTitle()
    {
        if (!$this->isPropertyAvailable("PolicyTitle")) {
            return null;
        }
        return $this->getProperty("PolicyTitle");
    }
    /**
     * @var string
     */
    public function setPolicyTitle($value)
    {
        $this->setProperty("PolicyTitle", $value, true);
    }
    /**
     * @return integer
     */
    public function getSettingSource()
    {
        return $this->getProperty("SettingSource");
    }
    /**
     * @var integer
     */
    public function setSettingSource($value)
    {
        $this->setProperty("SettingSource", $value, true);
    }
    /**
     * @return string
     */
    public function getTemplateId()
    {
        return $this->getProperty("TemplateId");
    }
    /**
     * @var string
     */
    public function setTemplateId($value)
    {
        $this->setProperty("TemplateId", $value, true);
    }
}