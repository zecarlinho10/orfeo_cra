<?php

/**
 * Generated 2019-11-17T18:22:48+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\Actions\InvokePostMethodQuery;

/**
 * Specifies 
 * the tenant properties.
 */
class TenantSettings extends BaseEntity
{
    /**
     * @return string
     */
    public function getCorporateCatalogUrl()
    {
        if (!$this->isPropertyAvailable("CorporateCatalogUrl")) {
            return null;
        }
        return $this->getProperty("CorporateCatalogUrl");
    }
    /**
     * @var string
     */
    public function setCorporateCatalogUrl($value)
    {
        $this->setProperty("CorporateCatalogUrl", $value, true);
    }
    /**
     * @return TenantSettings
     */
    public static function getCurrent(ClientContext $context)
    {
        $returnType = new TenantSettings($context);
        $qry = new InvokePostMethodQuery($returnType, "Current", null, null,
            null);
        $qry->IsStatic = true;
        $context->addQueryAndResultObject($qry, $returnType);
        return $returnType;
    }
}