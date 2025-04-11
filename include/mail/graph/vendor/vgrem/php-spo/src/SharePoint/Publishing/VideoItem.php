<?php

/**
 * Generated 2019-11-16T20:26:56+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint\Publishing;

use Office365\Runtime\Paths\EntityPath;
use Office365\Runtime\Http\HttpMethod;
use Office365\Runtime\Http\RequestOptions;
use Office365\Runtime\ServerTypeInfo;
use Office365\SharePoint\ClientContext;
use Office365\SharePoint\Entity;

class VideoItem extends Entity
{

    /**
     * @param $content
     * @throws \Exception
     */
    public function saveBinaryStream($content)
    {
        $ctx = $this->getContext();
        $methodName = "GetFile()/SaveBinaryStream";
        $requestUrl = $this->getResourceUrl() . "/" . $methodName;
        $request = new RequestOptions($requestUrl);
        $request->Method = HttpMethod::Post;
        $request->Data = $content;
        if ($ctx instanceof ClientContext) {
            $ctx->ensureFormDigest($request);
        }
        //$request->TransferEncodingChunkedAllowed = true;
        $ctx->executeQueryDirect($request);
    }
    function setProperty($name, $value, $persistChanges = true)
    {
        if ($name == "ID") {
            if (is_null($this->getResourcePath())) {
                $this->resourcePath = new EntityPath($value, $this->getParentCollection()->getResourcePath());
            }
        }
        parent::setProperty($name, $value, $persistChanges);
    }

    /**
     * @return ServerTypeInfo
     */
    public function getServerTypeInfo()
    {
        return new ServerTypeInfo("SP.Publishing", "VideoItem");
    }
    /**
     * @return string
     */
    public function getChannelID()
    {
        return $this->getProperty("ChannelID");
    }
    /**
     * @var string
     */
    public function setChannelID($value)
    {
        $this->setProperty("ChannelID", $value, true);
    }
    /**
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->getProperty("CreatedDate");
    }
    /**
     * @var string
     */
    public function setCreatedDate($value)
    {
        $this->setProperty("CreatedDate", $value, true);
    }
    /**
     * @return string
     */
    public function getDefaultEmbedCode()
    {
        return $this->getProperty("DefaultEmbedCode");
    }
    /**
     * @var string
     */
    public function setDefaultEmbedCode($value)
    {
        $this->setProperty("DefaultEmbedCode", $value, true);
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->getProperty("Description");
    }
    /**
     * @var string
     */
    public function setDescription($value)
    {
        $this->setProperty("Description", $value, true);
    }
    /**
     * @return string
     */
    public function getDisplayFormUrl()
    {
        return $this->getProperty("DisplayFormUrl");
    }
    /**
     * @var string
     */
    public function setDisplayFormUrl($value)
    {
        $this->setProperty("DisplayFormUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->getProperty("FileName");
    }
    /**
     * @var string
     */
    public function setFileName($value)
    {
        $this->setProperty("FileName", $value, true);
    }
    /**
     * @return string
     */
    public function getOwnerName()
    {
        return $this->getProperty("OwnerName");
    }
    /**
     * @var string
     */
    public function setOwnerName($value)
    {
        $this->setProperty("OwnerName", $value, true);
    }
    /**
     * @return string
     */
    public function getPlayerPageUrl()
    {
        return $this->getProperty("PlayerPageUrl");
    }
    /**
     * @var string
     */
    public function setPlayerPageUrl($value)
    {
        $this->setProperty("PlayerPageUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getServerRelativeUrl()
    {
        return $this->getProperty("ServerRelativeUrl");
    }
    /**
     * @var string
     */
    public function setServerRelativeUrl($value)
    {
        $this->setProperty("ServerRelativeUrl", $value, true);
    }
    /**
     * @return integer
     */
    public function getThumbnailSelection()
    {
        return $this->getProperty("ThumbnailSelection");
    }
    /**
     * @var integer
     */
    public function setThumbnailSelection($value)
    {
        $this->setProperty("ThumbnailSelection", $value, true);
    }
    /**
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->getProperty("ThumbnailUrl");
    }
    /**
     * @var string
     */
    public function setThumbnailUrl($value)
    {
        $this->setProperty("ThumbnailUrl", $value, true);
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getProperty("Title");
    }
    /**
     * @var string
     */
    public function setTitle($value)
    {
        $this->setProperty("Title", $value, true);
    }
    /**
     * @return string
     */
    public function getID()
    {
        return $this->getProperty("ID");
    }
    /**
     * @var string
     */
    public function setID($value)
    {
        $this->setProperty("ID", $value, true);
    }
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getProperty("Url");
    }
    /**
     * @var string
     */
    public function setUrl($value)
    {
        $this->setProperty("Url", $value, true);
    }
    /**
     * @return string
     */
    public function getVideoDownloadUrl()
    {
        return $this->getProperty("VideoDownloadUrl");
    }
    /**
     * @var string
     */
    public function setVideoDownloadUrl($value)
    {
        $this->setProperty("VideoDownloadUrl", $value, true);
    }
    /**
     * @return integer
     */
    public function getVideoDurationInSeconds()
    {
        return $this->getProperty("VideoDurationInSeconds");
    }
    /**
     * @var integer
     */
    public function setVideoDurationInSeconds($value)
    {
        $this->setProperty("VideoDurationInSeconds", $value, true);
    }
    /**
     * @return integer
     */
    public function getVideoProcessingStatus()
    {
        return $this->getProperty("VideoProcessingStatus");
    }
    /**
     * @var integer
     */
    public function setVideoProcessingStatus($value)
    {
        $this->setProperty("VideoProcessingStatus", $value, true);
    }
    /**
     * @return integer
     */
    public function getViewCount()
    {
        return $this->getProperty("ViewCount");
    }
    /**
     * @var integer
     */
    public function setViewCount($value)
    {
        $this->setProperty("ViewCount", $value, true);
    }
    /**
     * @return string
     */
    public function getYammerObjectUrl()
    {
        return $this->getProperty("YammerObjectUrl");
    }
    /**
     * @var string
     */
    public function setYammerObjectUrl($value)
    {
        $this->setProperty("YammerObjectUrl", $value, true);
    }
}