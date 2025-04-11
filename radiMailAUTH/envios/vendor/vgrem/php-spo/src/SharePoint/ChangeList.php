<?php

/**
 * Generated 2019-11-16T19:51:42+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

use Office365\Runtime\ResourcePath;

/**
 * Specifies 
 * a change on a list.The RelativeTime and RootFolderUrl properties are not 
 * included in the default scalar property set 
 * for this type.
 */
class ChangeList extends Change
{

    /**
     * An 
     * SPListTemplateType object that returns the list template type 
     * of the list.
     * @return integer
     */
    public function getBaseTemplate()
    {
        return $this->getProperty("BaseTemplate");
    }
    /**
     * An 
     * SPListTemplateType object that returns the list template type 
     * of the list.
     * @var integer
     */
    public function setBaseTemplate($value)
    {
        $this->setProperty("BaseTemplate", $value, true);
    }
    /**
     * A string 
     * that returns the name of the user (2) who 
     * modified the list.
     * @return string
     */
    public function getEditor()
    {
        return $this->getProperty("Editor");
    }
    /**
     * A string 
     * that returns the name of the user (2) who 
     * modified the list.
     * @var string
     */
    public function setEditor($value)
    {
        $this->setProperty("Editor", $value, true);
    }
    /**
     * Returns a Boolean 
     * value that indicates whether a list is a hidden hidden 
     * list.
     * @return bool
     */
    public function getHidden()
    {
        return $this->getProperty("Hidden");
    }
    /**
     * Returns a Boolean 
     * value that indicates whether a list is a hidden hidden 
     * list.
     * @var bool
     */
    public function setHidden($value)
    {
        $this->setProperty("Hidden", $value, true);
    }
    /**
     * Identifies 
     * the changed list.Exceptions: 
     * Error CodeError Type NameCondition-1System.NotSupportedExceptionThe list identifier 
     *   contained in the change fields (2) item of 
     *   the change collection is NULL.
     * @return string
     */
    public function getListId()
    {
        return $this->getProperty("ListId");
    }
    /**
     * Identifies 
     * the changed list.Exceptions: 
     * Error CodeError Type NameCondition-1System.NotSupportedExceptionThe list identifier 
     *   contained in the change fields (2) item of 
     *   the change collection is NULL.
     * @var string
     */
    public function setListId($value)
    {
        $this->setProperty("ListId", $value, true);
    }
    /**
     * A string 
     * that returns the root folderURL 
     * of the list.
     * @return string
     */
    public function getRootFolderUrl()
    {
        return $this->getProperty("RootFolderUrl");
    }
    /**
     * A string 
     * that returns the root folderURL 
     * of the list.
     * @var string
     */
    public function setRootFolderUrl($value)
    {
        $this->setProperty("RootFolderUrl", $value, true);
    }
    /**
     * A string 
     * that returns the list title.
     * @return string
     */
    public function getTitle()
    {
        return $this->getProperty("Title");
    }
    /**
     * A string 
     * that returns the list title.
     * @var string
     */
    public function setTitle($value)
    {
        $this->setProperty("Title", $value, true);
    }
    /**
     * Identifies 
     * the site 
     * (2) that contains the changed list.Exceptions: 
     * Error CodeError Type NameCondition-1System.NotSupportedExceptionThe site identifier in 
     *   the change fields (2) item of 
     *   the change collection is NULL.
     * @return string
     */
    public function getWebId()
    {
        return $this->getProperty("WebId");
    }

    /**
     * Identifies
     * the site
     * (2) that contains the changed list.Exceptions:
     * Error CodeError Type NameCondition-1System.NotSupportedExceptionThe site identifier in
     *   the change fields (2) item of
     *   the change collection is NULL.
     *
     * @return self
     * @var string
     */
    public function setWebId($value)
    {
        $this->setProperty("WebId", $value, true);
        return $this;
    }
    /**
     * An SPUser object 
     * (1) that represents information about the user (2) who created 
     * the list.
     * @return User
     */
    public function getCreator()
    {
        return $this->getProperty("Creator",
            new User($this->getContext(), new ResourcePath("Creator", $this->getResourcePath())));
    }
}
