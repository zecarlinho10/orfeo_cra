<?php

/**
 * Generated 2019-11-17T18:22:48+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\ResourcePath;

/**
 * Represents 
 * a collection of SPLanguage objects.<263>
 */
class LanguageCollection extends BaseEntity
{
    /**
     * @return LanguageCollection
     */
    public function getItems()
    {
        return $this->getProperty("Items",
            new BaseEntityCollection($this->getContext(),
                new ResourcePath("Items",$this->getResourcePath()),Language::class));
    }
    /**
     * @var LanguageCollection
     */
    public function setItems($value)
    {
        $this->setProperty("Items", $value, true);
    }
}