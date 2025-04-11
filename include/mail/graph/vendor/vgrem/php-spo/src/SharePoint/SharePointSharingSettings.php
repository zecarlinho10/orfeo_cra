<?php

/**
 * Generated 2019-11-17T18:22:48+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\ResourcePath;

/**
 * This class 
 * contains the SharePoint UI-specific sharing settings.
 */
class SharePointSharingSettings extends BaseEntity
{
    /**
     * @return string
     */
    public function getAddToGroupModeName()
    {
        return $this->getProperty("AddToGroupModeName");
    }

    /**
     *
     * @return self
     * @var string
     */
    public function setAddToGroupModeName($value)
    {
        $this->setProperty("AddToGroupModeName", $value, true);
        return $this;
    }
    /**
     * @return array
     */
    public function getGroupNameLines()
    {
        return $this->getProperty("GroupNameLines");
    }

    /**
     *
     * @return self
     * @var array
     */
    public function setGroupNameLines($value)
    {
        $this->setProperty("GroupNameLines", $value, true);
        return $this;
    }
    /**
     * @return array
     */
    public function getGroupRoleDefinitionNamesLines()
    {
        return $this->getProperty("GroupRoleDefinitionNamesLines");
    }
    /**
     * @var array
     */
    public function setGroupRoleDefinitionNamesLines($value)
    {
        $this->setProperty("GroupRoleDefinitionNamesLines", $value, true);
    }
    /**
     * @return bool
     */
    public function getIsMobileView()
    {
        return $this->getProperty("IsMobileView");
    }
    /**
     * @var bool
     */
    public function setIsMobileView($value)
    {
        $this->setProperty("IsMobileView", $value, true);
    }
    /**
     * @return bool
     */
    public function getPanelGivePermissionsVisible()
    {
        return $this->getProperty("PanelGivePermissionsVisible");
    }
    /**
     * @var bool
     */
    public function setPanelGivePermissionsVisible($value)
    {
        $this->setProperty("PanelGivePermissionsVisible", $value, true);
    }
    /**
     * @return bool
     */
    public function getPanelShowHideMoreOptionsVisible()
    {
        return $this->getProperty("PanelShowHideMoreOptionsVisible");
    }
    /**
     * @var bool
     */
    public function setPanelShowHideMoreOptionsVisible($value)
    {
        $this->setProperty("PanelShowHideMoreOptionsVisible", $value, true);
    }
    /**
     * @return bool
     */
    public function getPanelSimplifiedRoleSelectorVisible()
    {
        return $this->getProperty("PanelSimplifiedRoleSelectorVisible");
    }
    /**
     * @var bool
     */
    public function setPanelSimplifiedRoleSelectorVisible($value)
    {
        $this->setProperty("PanelSimplifiedRoleSelectorVisible", $value, true);
    }
    /**
     * @return array
     */
    public function getRequiredScriptFileLinks()
    {
        return $this->getProperty("RequiredScriptFileLinks");
    }
    /**
     * @var array
     */
    public function setRequiredScriptFileLinks($value)
    {
        $this->setProperty("RequiredScriptFileLinks", $value, true);
    }
    /**
     * @return array
     */
    public function getRoleDefinitionNameLines()
    {
        return $this->getProperty("RoleDefinitionNameLines");
    }
    /**
     * @var array
     */
    public function setRoleDefinitionNameLines($value)
    {
        $this->setProperty("RoleDefinitionNameLines", $value, true);
    }
    /**
     * @return string
     */
    public function getSelectedGroup()
    {
        return $this->getProperty("SelectedGroup");
    }
    /**
     * @var string
     */
    public function setSelectedGroup($value)
    {
        $this->setProperty("SelectedGroup", $value, true);
    }
    /**
     * @return bool
     */
    public function getSharedWithEnabled()
    {
        return $this->getProperty("SharedWithEnabled");
    }
    /**
     * @var bool
     */
    public function setSharedWithEnabled($value)
    {
        $this->setProperty("SharedWithEnabled", $value, true);
    }
    /**
     * @return string
     */
    public function getSharingCssLink()
    {
        return $this->getProperty("SharingCssLink");
    }

    /**
     *
     * @return self
     * @var string
     */
    public function setSharingCssLink($value)
    {
        $this->setProperty("SharingCssLink", $value, true);
        return $this;
    }
    /**
     * @return bool
     */
    public function getTabbedDialogEnabled()
    {
        return $this->getProperty("TabbedDialogEnabled");
    }
    /**
     * @var bool
     */
    public function setTabbedDialogEnabled($value)
    {
        $this->setProperty("TabbedDialogEnabled", $value, true);
    }
    /**
     * @return integer
     */
    public function getTabToShow()
    {
        if (!$this->isPropertyAvailable("TabToShow")) {
            return null;
        }
        return $this->getProperty("TabToShow");
    }

    /**
     *
     * @return self
     * @var integer
     */
    public function setTabToShow($value)
    {
        $this->setProperty("TabToShow", $value, true);
        return $this;
    }
    /**
     * @return string
     */
    public function gettxtEmailSubjectText()
    {
        return $this->getProperty("txtEmailSubjectText");
    }
    /**
     * @var string
     */
    public function settxtEmailSubjectText($value)
    {
        $this->setProperty("txtEmailSubjectText", $value, true);
    }
    /**
     * @return string
     */
    public function getUserDisplayUrl()
    {
        return $this->getProperty("UserDisplayUrl");
    }

    /**
     *
     * @return self
     * @var string
     */
    public function setUserDisplayUrl($value)
    {
        $this->setProperty("UserDisplayUrl", $value, true);
        return $this;
    }
    /**
     * @return PickerSettings
     */
    public function getPickerProperties()
    {
        return $this->getProperty("PickerProperties",
            new PickerSettings($this->getContext(),new ResourcePath("PickerProperties", $this->getResourcePath())));
    }
}