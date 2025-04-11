<?php

/**
 * Generated 2019-11-17T16:35:02+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

/**
 * Represents 
 * a list definition or a list template, which defines the fields and views for a 
 * list. List definitions are contained in files within \\Program Files\Common 
 * Files\Microsoft Shared\Web Server Extensions\12\TEMPLATE\FEATURES, but list 
 * templates are created through the user interface or through the object model 
 * when a list is saved as a template.Use the Web.ListTemplates property (section 3.2.5.143.1.2.13) 
 * to return a ListTemplateCollection (section 3.2.5.92) for a 
 * site collection. Use an indexer to return a single list definition or list 
 * template from the collection.
 */
class ListTemplate extends BaseEntity
{
    /**
     * @return bool
     */
    public function getAllowsFolderCreation()
    {
        return $this->getProperty("AllowsFolderCreation");
    }

    /**
     * @return ListTemplate
     * @var bool
     */
    public function setAllowsFolderCreation($value)
    {
        $this->setProperty("AllowsFolderCreation", $value, true);
        return $this;
    }
    /**
     * @return integer
     */
    public function getBaseType()
    {
        return $this->getProperty("BaseType");
    }

    /**
     * @return ListTemplate
     * @var integer
     */
    public function setBaseType($value)
    {
        $this->setProperty("BaseType", $value, true);
        return $this;
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getProperty("Description");
    }

    /**
     *
     * @return self
     * @var string
     */
    public function setDescription($value)
    {
        $this->setProperty("Description", $value, true);
        return $this;
    }
    /**
     * @return string
     */
    public function getFeatureId()
    {
        return $this->getProperty("FeatureId");
    }

    /**
     *
     * @return self
     * @var string
     */
    public function setFeatureId($value)
    {
        $this->setProperty("FeatureId", $value, true);
        return $this;
    }
    /**
     * @return bool
     */
    public function getHidden()
    {
        return $this->getProperty("Hidden");
    }
    /**
     * @var bool
     */
    public function setHidden($value)
    {
        $this->setProperty("Hidden", $value, true);
    }
    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->getProperty("ImageUrl");
    }
    /**
     * @var string
     */
    public function setImageUrl($value)
    {
        $this->setProperty("ImageUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getInternalName()
    {
        return $this->getProperty("InternalName");
    }
    /**
     * @var string
     */
    public function setInternalName($value)
    {
        $this->setProperty("InternalName", $value, true);
    }
    /**
     * @return bool
     */
    public function getIsCustomTemplate()
    {
        return $this->getProperty("IsCustomTemplate");
    }

    /**
     *
     * @return ListTemplate
     * @var bool
     */
    public function setIsCustomTemplate($value)
    {
        $this->setProperty("IsCustomTemplate", $value, true);
        return $this;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->getProperty("Name");
    }

    /**
     *
     * @return ListTemplate
     * @var string
     */
    public function setName($value)
    {
        $this->setProperty("Name", $value, true);
        return $this;
    }
    /**
     * @return bool
     */
    public function getOnQuickLaunch()
    {
        return $this->getProperty("OnQuickLaunch");
    }

    /**
     *
     * @return ListTemplate
     * @var bool
     */
    public function setOnQuickLaunch($value)
    {
        $this->setProperty("OnQuickLaunch", $value, true);
        return $this;
    }
    /**
     * @return integer
     */
    public function getListTemplateTypeKind()
    {
        return $this->getProperty("ListTemplateTypeKind");
    }

    /**
     *
     * @return ListTemplate
     * @var integer
     */
    public function setListTemplateTypeKind($value)
    {
        $this->setProperty("ListTemplateTypeKind", $value, true);
        return $this;
    }
    /**
     * @return bool
     */
    public function getUnique()
    {
        return $this->getProperty("Unique");
    }

    /**
     * @return ListTemplate
     * @var bool
     */
    public function setUnique($value)
    {
        $this->setProperty("Unique", $value, true);
        return $this;
    }
}