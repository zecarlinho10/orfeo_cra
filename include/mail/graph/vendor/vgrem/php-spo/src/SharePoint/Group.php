<?php

/**
 * Generated 2019-11-17T14:35:07+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\ResourcePath;

/**
 * Represents 
 * a collection of users. The CanCurrentUserEditMembership, CanCurrentUserManageGroup 
 * and CanCurrentUserViewMembership properties are not included in the default 
 * scalar property set for this type.
 */
class Group extends Principal
{
    /**
     * Gets a collection of user objects that represents all of the users in the group.
     * @return UserCollection
     */
    public function getUsers()
    {
        return $this->getProperty("Users",
            new UserCollection($this->getContext(),new ResourcePath("Users", $this->getResourcePath())));
    }
    /**
     * Specifies 
     * whether a member of the group 
     * can add and remove members from the group.
     * @return bool
     */
    public function getAllowMembersEditMembership()
    {
        return $this->getProperty("AllowMembersEditMembership");
    }
    /**
     * Specifies 
     * whether a member of the group 
     * can add and remove members from the group.
     * @var bool
     */
    public function setAllowMembersEditMembership($value)
    {
        $this->setProperty("AllowMembersEditMembership", $value, true);
    }
    /**
     * Specifies 
     * whether to allow users to request to join or leave in the group. 
     * 
     * @return bool
     */
    public function getAllowRequestToJoinLeave()
    {
        return $this->getProperty("AllowRequestToJoinLeave");
    }
    /**
     * Specifies 
     * whether to allow users to request to join or leave in the group. 
     * 
     * @var bool
     */
    public function setAllowRequestToJoinLeave($value)
    {
        $this->setProperty("AllowRequestToJoinLeave", $value, true);
    }
    /**
     * Specifies 
     * whether requests to join or leave the group are 
     * automatically accepted.
     * @return bool
     */
    public function getAutoAcceptRequestToJoinLeave()
    {
        return $this->getProperty("AutoAcceptRequestToJoinLeave");
    }
    /**
     * Specifies 
     * whether requests to join or leave the group are 
     * automatically accepted.
     * @var bool
     */
    public function setAutoAcceptRequestToJoinLeave($value)
    {
        $this->setProperty("AutoAcceptRequestToJoinLeave", $value, true);
    }
    /**
     * Specifies 
     * whether the current user can add 
     * and remove members from the group.
     * @return bool
     */
    public function getCanCurrentUserEditMembership()
    {
        return $this->getProperty("CanCurrentUserEditMembership");
    }
    /**
     * Specifies 
     * whether the current user can add 
     * and remove members from the group.
     * @var bool
     */
    public function setCanCurrentUserEditMembership($value)
    {
        $this->setProperty("CanCurrentUserEditMembership", $value, true);
    }
    /**
     * Specifies 
     * whether the current user can 
     * change settings on the group.
     * @return bool
     */
    public function getCanCurrentUserManageGroup()
    {
        return $this->getProperty("CanCurrentUserManageGroup");
    }
    /**
     * Specifies 
     * whether the current user can 
     * change settings on the group.
     * @var bool
     */
    public function setCanCurrentUserManageGroup($value)
    {
        $this->setProperty("CanCurrentUserManageGroup", $value, true);
    }
    /**
     * Specifies 
     * whether the current user can 
     * view the membership of the group.
     * @return bool
     */
    public function getCanCurrentUserViewMembership()
    {
        return $this->getProperty("CanCurrentUserViewMembership");
    }
    /**
     * Specifies 
     * whether the current user can 
     * view the membership of the group.
     * @var bool
     */
    public function setCanCurrentUserViewMembership($value)
    {
        $this->setProperty("CanCurrentUserViewMembership", $value, true);
    }
    /**
     * Specifies 
     * the description for the group.
     * @return string
     */
    public function getDescription()
    {
        return $this->getProperty("Description");
    }
    /**
     * Specifies 
     * the description for the group.
     * @var string
     */
    public function setDescription($value)
    {
        $this->setProperty("Description", $value, true);
    }
    /**
     * Specifies 
     * whether viewing the membership of the group 
     * is restricted to members of the 
     * group.
     * @return bool
     */
    public function getOnlyAllowMembersViewMembership()
    {
        return $this->getProperty("OnlyAllowMembersViewMembership");
    }
    /**
     * Specifies 
     * whether viewing the membership of the group 
     * is restricted to members of the 
     * group.
     * @var bool
     */
    public function setOnlyAllowMembersViewMembership($value)
    {
        $this->setProperty("OnlyAllowMembersViewMembership", $value, true);
    }
    /**
     * Specifies 
     * the name of the owner of the group.
     * @return string
     */
    public function getOwnerTitle()
    {
        return $this->getProperty("OwnerTitle");
    }
    /**
     * Specifies 
     * the name of the owner of the group.
     * @var string
     */
    public function setOwnerTitle($value)
    {
        $this->setProperty("OwnerTitle", $value, true);
    }
    /**
     * Specifies 
     * the e-mail 
     * address to which requests to join or leave the group 
     * are sent.
     * @return string
     */
    public function getRequestToJoinLeaveEmailSetting()
    {
        return $this->getProperty("RequestToJoinLeaveEmailSetting");
    }
    /**
     * Specifies 
     * the e-mail 
     * address to which requests to join or leave the group 
     * are sent.
     * @var string
     */
    public function setRequestToJoinLeaveEmailSetting($value)
    {
        $this->setProperty("RequestToJoinLeaveEmailSetting", $value, true);
    }
    /**
     * Specifies 
     * the name of the owner of the group.
     * @return Principal
     */
    public function getOwner()
    {
        return $this->getProperty("Owner",
            new Principal($this->getContext(),new ResourcePath("Owner", $this->getResourcePath())));
    }
}