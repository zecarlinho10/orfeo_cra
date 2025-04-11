<?php

/**
 * Generated 2019-11-17T16:07:15+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;

/**
 * Represents 
 * an event object happened on a Microsoft.SharePoint.SPFile.
 */
class FileVersionEvent extends BaseEntity
{
    /**
     * @return string
     */
    public function getEditor()
    {
        return $this->getProperty("Editor");
    }
    /**
     * @var string
     */
    public function setEditor($value)
    {
        $this->setProperty("Editor", $value, true);
    }
    /**
     * @return string
     */
    public function getEditorEmail()
    {
        return $this->getProperty("EditorEmail");
    }
    /**
     * @var string
     */
    public function setEditorEmail($value)
    {
        $this->setProperty("EditorEmail", $value, true);
    }
    /**
     * @return SharedWithUser
     */
    public function getSharedByUser()
    {
        return $this->getProperty("SharedByUser", new SharedWithUser());
    }
    /**
     * @var SharedWithUser
     */
    public function setSharedByUser($value)
    {
        $this->setProperty("SharedByUser", $value, true);
    }
    /**
     * @return SharedWithUserCollection
     */
    public function getSharedWithUsers()
    {
        return $this->getProperty("SharedWithUsers", new SharedWithUserCollection());
    }
    /**
     * @var SharedWithUserCollection
     */
    public function setSharedWithUsers($value)
    {
        $this->setProperty("SharedWithUsers", $value, true);
    }
    /**
     * @return string
     */
    public function getTime()
    {
        return $this->getProperty("Time");
    }
    /**
     * @var string
     */
    public function setTime($value)
    {
        $this->setProperty("Time", $value, true);
    }
}