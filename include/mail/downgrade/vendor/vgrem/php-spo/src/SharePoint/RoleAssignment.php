<?php

/**
 * Generated 2019-11-16T16:48:41+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\ResourcePath;
/**
 * Specifies 
 * the role 
 * assignments for a user or group on a securable 
 * object. 
 */
class RoleAssignment extends Entity
{

    /**
     * @return Principal
     */
    public function getMember()
    {
        return $this->getProperty("Member",
            new Principal($this->getContext(),new ResourcePath("Member", $this->getResourcePath())));
    }


    /**
     * Gets
     * the identifier of the user or group corresponding
     * to the role assignment.<79>
     * @return integer
     */
    public function getPrincipalId()
    {
        return $this->getProperty("PrincipalId");
    }
    /**
     * Sets
     * the identifier of the user or group corresponding 
     * to the role assignment.<79>
     * @var integer
     */
    public function setPrincipalId($value)
    {
        $this->setProperty("PrincipalId", $value, true);
    }
    /**
     * Specifies 
     * a collection of role definitions for 
     * this role 
     * assignment.It MUST 
     * NOT be NULL. 
     * @return RoleDefinitionCollection
     */
    public function getRoleDefinitionBindings()
    {
        return $this->getProperty("RoleDefinitionBindings",
            new RoleDefinitionCollection($this->getContext(),
                new ResourcePath("RoleDefinitionBindings", $this->getResourcePath())));
    }
}