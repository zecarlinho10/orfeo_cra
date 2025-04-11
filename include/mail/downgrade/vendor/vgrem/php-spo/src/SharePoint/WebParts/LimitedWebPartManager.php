<?php

/**
 * Generated 2020-08-19T18:22:34+00:00 16.0.20405.12008
 */
namespace Office365\SharePoint\WebParts;

use Office365\Runtime\ClientValue;
use Office365\Runtime\Actions\InvokePostMethodQuery;
use Office365\Runtime\ResourcePath;
use Office365\SharePoint\BaseEntity;

/**
 * Provides 
 * operations to access and modify the existing Web Parts on a Web Part 
 * Page, and add new ones to the Web Part Page.
 */
class LimitedWebPartManager extends BaseEntity
{
    /**
     * Imports a Web Part from a string in the .dwp format
     * @param string $webPartXml
     * @return WebPartDefinition
     */
    public function importWebPart($webPartXml)
    {
        $payload = new ClientValue();
        $payload->setProperty("webPartXml", $webPartXml);
        $webPartDefinition = new WebPartDefinition($this->getContext());
        $qry = new InvokePostMethodQuery($this, "ImportWebPart", null, null, $payload);
        $this->getContext()->addQueryAndResultObject($qry, $webPartDefinition);
        return $webPartDefinition;
    }
    /**
     * @return WebPartDefinitionCollection
     */
    public function getWebParts()
    {
        return $this->getProperty("WebParts",
            new WebPartDefinitionCollection($this->getContext(),
                new ResourcePath("WebParts", $this->getResourcePath())));
    }
    /**
     * Specifies 
     * whether the Web Part Page 
     * contains one or more personalized Web Parts. 
     * Its value 
     * MUST be "true" if both the personalization scope 
     * of the Web Part Page is "User" and the Web Part Page contains one or 
     * more personalized Web Parts; otherwise, it MUST be "false".
     * @return bool
     */
    public function getHasPersonalizedParts()
    {
        return $this->getProperty("HasPersonalizedParts");
    }
    /**
     * Specifies 
     * whether the Web Part Page 
     * contains one or more personalized Web Parts. 
     * Its value 
     * MUST be "true" if both the personalization scope 
     * of the Web Part Page is "User" and the Web Part Page contains one or 
     * more personalized Web Parts; otherwise, it MUST be "false".
     * @var bool
     */
    public function setHasPersonalizedParts($value)
    {
        $this->setProperty("HasPersonalizedParts", $value, true);
    }
    /**
     * Specifies 
     * the current personalization scope 
     * of the Web Part Page.Its value 
     * MUST be "User" if personalized data is loaded for the Web Parts 
     * on the Web Part Page, or "Shared" if common data for all users is 
     * loaded.
     * @return integer
     */
    public function getScope()
    {
        return $this->getProperty("Scope");
    }
    /**
     * Specifies 
     * the current personalization scope 
     * of the Web Part Page.Its value 
     * MUST be "User" if personalized data is loaded for the Web Parts 
     * on the Web Part Page, or "Shared" if common data for all users is 
     * loaded.
     * @var integer
     */
    public function setScope($value)
    {
        $this->setProperty("Scope", $value, true);
    }
    /**
     * @return bool
     */
    public function getHasWebPartConnections()
    {
        return $this->getProperty("HasWebPartConnections");
    }
    /**
     * @var bool
     */
    public function setHasWebPartConnections($value)
    {
        $this->setProperty("HasWebPartConnections", $value, true);
    }
}