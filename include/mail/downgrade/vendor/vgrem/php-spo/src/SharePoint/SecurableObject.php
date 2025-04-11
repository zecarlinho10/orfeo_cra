<?php

/**
 * Generated 2019-11-16T17:57:50+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\Actions\InvokePostMethodQuery;
use Office365\Runtime\ResourcePath;
/**
 * An object 
 * (1) that can be assigned security permissions.The HasUniqueRoleAssignments property is not included in the 
 * default 
 * scalar property set for this type.
 */
class SecurableObject extends Entity
{
    /**
     * Creates unique role assignments for the securable object.
     * @param bool $copyRoleAssignments
     * @return SecurableObject
     */
    public function breakRoleInheritance($copyRoleAssignments)
    {
        $qry = new InvokePostMethodQuery($this, "breakroleinheritance", array($copyRoleAssignments));
        $this->getContext()->addQuery($qry);
        return $this;
    }
    /**
     * @return RoleAssignmentCollection
     */
    public function getRoleAssignments()
    {
        return $this->getProperty('RoleAssignments',
            new RoleAssignmentCollection($this->getContext(),
                new ResourcePath("roleAssignments", $this->getResourcePath())));
    }
    /**
     * Gets a Boolean value indicating whether the object has unique security or
     * inherits its role assignments from a parent object.
     * @return bool
     */
    public function getHasUniqueRoleAssignments()
    {
        return $this->getProperty('HasUniqueRoleAssignments');
    }
    /**
     * Specifies 
     * the object where role assignments for 
     * this object are defined.<85>
     * @return SecurableObject
     */
    public function getFirstUniqueAncestorSecurableObject()
    {
        return $this->getProperty("FirstUniqueAncestorSecurableObject",
            new SecurableObject($this->getContext(),
                new ResourcePath("FirstUniqueAncestorSecurableObject", $this->getResourcePath())));
    }

    /**
     * Specifies
     * whether the role assignments are
     * uniquely defined for this securable object or
     * inherited from a parent securable object. If the value is "false", role
     * assignments are inherited from a parent securable object.
     * @return self
     * @var bool
     */
    public function setHasUniqueRoleAssignments($value)
    {
        return $this->setProperty("HasUniqueRoleAssignments", $value, true);
    }
}