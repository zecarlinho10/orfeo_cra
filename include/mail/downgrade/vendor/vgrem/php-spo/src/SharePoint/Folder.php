<?php

/**
 * Generated 2020-08-17T19:25:17+00:00 16.0.20405.12007
 */
namespace Office365\SharePoint;

use Office365\Runtime\Actions\InvokePostMethodQuery;
use Office365\Runtime\Actions\UpdateEntityQuery;
use Office365\Runtime\ResourcePath;
/**
 * Represents 
 * a list 
 * folder on a site (2).Various folder properties in the Web class (section 3.2.5.143) 
 * return any from a site or subsite. Use the FolderCollection (section 3.2.5.73) 
 * that represents the collection of folders for a site or folder. Use an indexer 
 * to return a single folder from the collection.The ContentTypeOrder, ServerRelativePath and 
 * UniqueContentTypeOrder properties are not included in the default 
 * scalar property set for this type.
 */
class Folder extends Entity
{

    /**
     * Upload a file under Folder
     * @param string $name
     * @param string $content
     * @return File
     */
    public function uploadFile($name, $content)
    {
        $info = new FileCreationInformation();
        $info->Url = $name;
        $info->Content = $content;
        $info->Overwrite = true;
        return $this->getFiles()->add($info);
    }

    /**
     * Copies the folder along with files to the destination URL.
     * @param string $strNewUrl The absolute URL or server relative URL of the destination file path to copy to.
     * @param bool $bOverWrite true to overwrite a file(s) with the same name in the same location; otherwise false.
     * @return Folder
     */
    public function copyTo($strNewUrl, $bOverWrite)
    {
        $targetFolder =  $this->getContext()->getWeb()->getRootFolder()->getFolders()->add($strNewUrl);
        $this->getContext()->getPendingRequest()->afterExecuteRequest(function () use($strNewUrl,$bOverWrite, $targetFolder) {
            $this->ensureProperty("Files", function ()  use ($strNewUrl,$bOverWrite, $targetFolder){
                /** @var File $file */
                foreach($this->getFiles() as $file){
                    $newFileUrl = join("/", array($strNewUrl,$file->getName())) ;
                    $file->copyTo($newFileUrl, $bOverWrite);
                }
                $this->getContext()->load($targetFolder);
            });
        });
        return $targetFolder;
    }



    /**
     * Moves the file to the specified destination URL.
     * @param string $newUrl The absolute URL or server relative URL of the destination file path to move to.
     * @param int $flags The bitwise SP.MoveOperations value for how to move the file. Overwrite = 1; AllowBrokenThickets (move even if supporting files are separated from the file) = 8.
     * @return Folder
     */
    public function moveTo($newUrl, $flags)
    {
        $targetFolder =  $this->getContext()->getWeb()->getRootFolder()->getFolders()->add($newUrl);
        $this->getContext()->getPendingRequest()->afterExecuteRequest(function () use($newUrl, $flags, $targetFolder) {
            $this->ensureProperty("Files", function () use ($newUrl, $flags, $targetFolder){
                /** @var File $file */
                foreach($this->getFiles() as $file){
                    $newFileUrl = join("/", array($newUrl,$file->getName())) ;
                    $file->moveTo($newFileUrl, $flags);
                }
                $this->getContext()->load($targetFolder);
            });
        });
        return $targetFolder;
    }

    /**
     * Rename a file
     * @param string $name
     * @return Folder
     */
    public function rename($name)
    {
        $item = $this->getListItemAllFields();
        $item->setProperty('Title', $name);
        $item->setProperty('FileLeafRef', $name);
        $item->update();
        return $this;
    }
    /**
     * Moves the list folder to the Recycle Bin and returns the identifier of the new Recycle Bin item.
     * @return Folder
     */
    public function recycle()
    {
        $qry = new InvokePostMethodQuery($this, "recycle");
        $this->getContext()->addQuery($qry);
        return $this;
    }
    /**
     * Gets the collection of all files contained in the list folder.
     * You can add a file to a folder by using the Add method on the folder’s FileCollection resource.
     * @return FileCollection
     */
    public function getFiles()
    {
        return $this->getProperty("Files",
            new FileCollection($this->getContext(), new ResourcePath("Files", $this->getResourcePath())));
    }
    /**
     * Gets the collection of list folders contained in the list folder.
     * @return FolderCollection
     */
    public function getFolders()
    {
        return $this->getProperty("Folders",
            new FolderCollection($this->getContext(), new ResourcePath("folders", $this->getResourcePath())));
    }
    /**
     * Specifies the list item field (2) values for the list item corresponding to the folder.
     * @return ListItem
     */
    public function getListItemAllFields()
    {
        return $this->getProperty("ListItemAllFields",
            new ListItem($this->getContext(), new ResourcePath("ListItemAllFields", $this->getResourcePath())));
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param bool $persistChanges
     * @return Folder
     */
    function setProperty($name, $value, $persistChanges = true)
    {
        if ($name == "UniqueId") {
            $this->resourcePath = $this->getContext()->getWeb()->getFolderById($value)->getResourcePath();
        }
        parent::setProperty($name, $value, $persistChanges);
        return $this;
    }
    /**
     * Gets a 
     * Boolean value that indicates whether the folder exists.
     * @return bool
     */
    public function getExists()
    {
        return $this->getProperty("Exists");
    }
    /**
     * Gets a 
     * Boolean value that indicates whether the folder exists.
     * @var bool
     */
    public function setExists($value)
    {
        $this->setProperty("Exists", $value, true);
    }
    /**
     * Indicates 
     * whether the folder is enabled for WOPI default action.
     * @return bool
     */
    public function getIsWOPIEnabled()
    {
        return $this->getProperty("IsWOPIEnabled");
    }
    /**
     * Indicates 
     * whether the folder is enabled for WOPI default action.
     * @var bool
     */
    public function setIsWOPIEnabled($value)
    {
        $this->setProperty("IsWOPIEnabled", $value, true);
    }
    /**
     * Specifies 
     * the count of items in the list folder.
     * @return integer
     */
    public function getItemCount()
    {
        return $this->getProperty("ItemCount");
    }
    /**
     * Specifies 
     * the count of items in the list folder.
     * @var integer
     */
    public function setItemCount($value)
    {
        $this->setProperty("ItemCount", $value, true);
    }
    /**
     * Specifies 
     * the list 
     * folder name.It MUST 
     * NOT be NULL. Its length MUST be equal to or less than 256. 
     * @return string
     */
    public function getName()
    {
        return $this->getProperty("Name");
    }
    /**
     * Specifies 
     * the list 
     * folder name.It MUST 
     * NOT be NULL. Its length MUST be equal to or less than 256. 
     * @var string
     */
    public function setName($value)
    {
        $this->setProperty("Name", $value, true);
    }
    /**
     * Gets a 
     * string that identifies the application in which the folder was created.
     * @return string
     */
    public function getProgID()
    {
        return $this->getProperty("ProgID");
    }
    /**
     * Gets a 
     * string that identifies the application in which the folder was created.
     * @var string
     */
    public function setProgID($value)
    {
        $this->setProperty("ProgID", $value, true);
    }
    /**
     * Specifies 
     * the server-relative 
     * URL of the list folder.It MUST 
     * NOT be NULL. It MUST be a URL of server-relative form. 
     * @return string
     */
    public function getServerRelativeUrl()
    {
        return $this->getProperty("ServerRelativeUrl");
    }

    /**
     * Specifies
     * the server-relative
     * URL of the list folder.It MUST
     * NOT be NULL. It MUST be a URL of server-relative form.
     * @return self
     * @var string
     */
    public function setServerRelativeUrl($value)
    {
        return $this->setProperty("ServerRelativeUrl", $value, true);
    }
    /**
     * Gets when 
     * the folder was created in UTC.
     * @return string
     */
    public function getTimeCreated()
    {
        return $this->getProperty("TimeCreated");
    }

    /**
     * Gets when
     * the folder was created in UTC.
     * @return self
     * @var string
     */
    public function setTimeCreated($value)
    {
        return $this->setProperty("TimeCreated", $value, true);
    }
    /**
     * Gets the 
     * last time this folder or a direct child was modified in UTC.
     * @return string
     */
    public function getTimeLastModified()
    {
        return $this->getProperty("TimeLastModified");
    }
    /**
     * Gets the 
     * last time this folder or a direct child was modified in UTC.
     * @var string
     */
    public function setTimeLastModified($value)
    {
        $this->setProperty("TimeLastModified", $value, true);
    }
    /**
     * Gets the 
     * unique ID of the folder.
     * @return string
     */
    public function getUniqueId()
    {
        return $this->getProperty("UniqueId");
    }
    /**
     * Gets the 
     * unique ID of the folder.
     * @var string
     */
    public function setUniqueId($value)
    {
        $this->setProperty("UniqueId", $value, true);
    }
    /**
     * Specifies 
     * the server-relative 
     * URL for the list folderWelcome 
     * page.It MUST 
     * NOT be NULL. 
     * @return string
     */
    public function getWelcomePage()
    {
        return $this->getProperty("WelcomePage");
    }

    /**
     * Specifies
     * the server-relative
     * URL for the list folderWelcome
     * page.It MUST
     * NOT be NULL.
     *
     * @return self
     * @var string
     */
    public function setWelcomePage($value)
    {
        return $this->setProperty("WelcomePage", $value, true);
    }
    /**
     * Specifies 
     * the list 
     * folder.
     * @return Folder
     */
    public function getParentFolder()
    {
        return $this->getProperty("ParentFolder",
            new Folder($this->getContext(), new ResourcePath("ParentFolder", $this->getResourcePath())));
    }
    /**
     * @return StorageMetrics
     */
    public function getStorageMetrics()
    {
        return $this->getProperty("StorageMetrics",
            new StorageMetrics($this->getContext(),new ResourcePath("StorageMetrics", $this->getResourcePath())));
    }
    /**
     * Returns 
     * the server-relative path of the folder.
     * @return SPResourcePath
     */
    public function getServerRelativePath()
    {
        return $this->getProperty("ServerRelativePath", new SPResourcePath());
    }

    /**
     * Returns
     * the server-relative path of the folder.
     *
     * @return self
     * @var SPResourcePath
     */
    public function setServerRelativePath($value)
    {
        return $this->setProperty("ServerRelativePath", $value, true);
    }
}