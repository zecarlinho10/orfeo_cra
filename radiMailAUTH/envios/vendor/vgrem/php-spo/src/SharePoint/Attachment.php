<?php

/**
 * Generated 2020-08-17T19:25:17+00:00 16.0.20405.12007
 */
namespace Office365\SharePoint;

use Office365\Runtime\Actions\DeleteEntityQuery;
use Office365\Runtime\Paths\ServiceOperationPath;

/**
 * Specifies 
 * a list 
 * item attachment.<174>
 */
class Attachment extends BaseEntity
{
    public function deleteObject()
    {
        $qry = new DeleteEntityQuery($this);
        $this->getContext()->addQuery($qry);
        return $this;
    }
    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->getProperty("FileName");
    }
    /**
     * @return string
     */
    public function getServerRelativeUrl()
    {
        return $this->getProperty("ServerRelativeUrl");
    }
    /**
     * Specifies 
     * the file name of the list item attachment.
     * @var string
     */
    public function setFileName($value)
    {
        $this->setProperty("FileName", $value, true);
    }
    /**
     * Specifies 
     * the server-relative 
     * URL of a list item attachment.
     * @var string
     */
    public function setServerRelativeUrl($value)
    {
        $this->setProperty("ServerRelativeUrl", $value, true);
    }
    /**
     * The file 
     * name of the attachment as a SPResourcePath.
     * @return SPResourcePath
     */
    public function getFileNameAsPath()
    {
        return $this->getProperty("FileNameAsPath", new SPResourcePath());
    }
    /**
     * The file 
     * name of the attachment as a SPResourcePath.
     * @var SPResourcePath
     */
    public function setFileNameAsPath($value)
    {
        $this->setProperty("FileNameAsPath", $value, true);
    }
    /**
     * The 
     * server-relative-path of the attachment.
     * @return SPResourcePath
     */
    public function getServerRelativePath()
    {
        return $this->getProperty("ServerRelativePath");
    }

    /**
     * The
     * server-relative-path of the attachment.
     *
     * @return self
     * @var SPResourcePath
     */
    public function setServerRelativePath($value)
    {
        return $this->setProperty("ServerRelativePath", $value, true);
    }

    public function setProperty($name, $value, $persistChanges = true)
    {
        if($name == "ServerRelativeUrl" && is_null($this->getResourcePath())){
            $this->resourcePath = new ServiceOperationPath("getFileByServerRelativeUrl",
                array(rawurlencode($value)), $this->getContext()->getWeb()->getResourcePath());
        }
        return parent::setProperty($name, $value, $persistChanges);
    }
}