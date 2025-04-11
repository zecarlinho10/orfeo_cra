<?php

/**
 * Generated 2021-08-22T15:28:03+00:00 16.0.21611.12002
 */
namespace Office365\SharePoint;

use Office365\Runtime\Paths\ServiceOperationPath;

/**
 * Specifies 
 * a base type that represents a user or group that can be 
 * assigned permissions to control security.
 */
class Principal extends Entity
{
    /**
     * @return PrincipalType
     */
    public function getPrincipalType()
    {
        return $this->getProperty("PrincipalType");
    }
    /**
     * Specifies 
     * the member 
     * identifier for the user or group.Its value 
     * MUST be equal to or greater than 1. Its value MUST be equal to or less than 
     * 2147483647. 
     * @return integer
     */
    public function getId()
    {
        return $this->getProperty("Id");
    }
    /**
     * Specifies 
     * whether the principal is hidden 
     * in the user interface (UI).<77>
     * @return bool
     */
    public function getIsHiddenInUI()
    {
        return $this->getProperty("IsHiddenInUI");
    }
    /**
     * Specifies
     * whether the principal is hidden
     * in the user interface (UI).<77>
     *
     * @return self
     * @var bool
     */
    public function setIsHiddenInUI($value)
    {
        return $this->setProperty("IsHiddenInUI", $value, true);
    }
    /**
     * If this object 
     * (1) represents a user, LoginName specifies the login 
     * name of the user. For a group, it specifies 
     * the name of the group.
     * @return string
     */
    public function getLoginName()
    {
        return $this->getProperty("LoginName");
    }
    /**
     * If this object
     * (1) represents a user, LoginName specifies the login
     * name of the user. For a group, it specifies
     * the name of the group.
     *
     * @return self
     * @var string
     */
    public function setLoginName($value)
    {
        return $this->setProperty("LoginName", $value, true);
    }
    /**
     * Specifies 
     * the name of the principal.It MUST 
     * NOT be NULL. Its length MUST be equal to or less than 255. 
     * @return string
     */
    public function getTitle()
    {
        return $this->getProperty("Title");
    }
    /**
     * Specifies
     * the name of the principal.It MUST
     * NOT be NULL. Its length MUST be equal to or less than 255.
     *
     * @return self
     * @var string
     */
    public function setTitle($value)
    {
        return $this->setProperty("Title", $value, true);
    }
    /**
     * Specifies
     * the type of principal
     * represented by this object (1).
     *
     * @return self
     * @var integer
     */
    public function setPrincipalType($value)
    {
        return $this->setProperty("PrincipalType", $value, true);
    }
    /**
     * @param string $name
     * @param mixed $value
     * @param bool $persistChanges
     * @return self
     */
    public function setProperty($name, $value, $persistChanges = true)
    {
        parent::setProperty($name, $value, $persistChanges);
        if (is_null($this->resourcePath)) {
            if ($name === "Id") {
                $this->resourcePath = new ServiceOperationPath("GetById", array($value), $this->parentCollection->resourcePath);
            }
        }
        return $this;
    }
    /**
     * Specifies 
     * the member 
     * identifier for the user or group.Its value 
     * MUST be equal to or greater than 1. Its value MUST be equal to or less than 
     * 2147483647. 
     * @var integer
     */
    public function setId($value)
    {
        return $this->setProperty("Id", $value, true);
    }
}