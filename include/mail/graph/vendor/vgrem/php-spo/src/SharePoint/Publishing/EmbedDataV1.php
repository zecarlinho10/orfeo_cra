<?php

/**
 * Generated 2021-08-22T15:28:03+00:00 16.0.21611.12002
 */
namespace Office365\SharePoint\Publishing;

use Office365\SharePoint\BaseEntity;
class EmbedDataV1 extends BaseEntity
{
    /**
     * @return bool
     */
    public function getAllowHttpsEmbed()
    {
        if (!$this->isPropertyAvailable("AllowHttpsEmbed")) {
            return null;
        }
        return $this->getProperty("AllowHttpsEmbed");
    }
    /**
     * @var bool
     */
    public function setAllowHttpsEmbed($value)
    {
        $this->setProperty("AllowHttpsEmbed", $value, true);
    }
    /**
     * @return string
     */
    public function getCreatorName()
    {
        return $this->getProperty("CreatorName");
    }
    /**
     * @var string
     */
    public function setCreatorName($value)
    {
        $this->setProperty("CreatorName", $value, true);
    }
    /**
     * @return string
     */
    public function getDatePublishedAt()
    {
        return $this->getProperty("DatePublishedAt");
    }
    /**
     * @var string
     */
    public function setDatePublishedAt($value)
    {
        $this->setProperty("DatePublishedAt", $value, true);
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
     * @return integer
     */
    public function getEmbedServiceResponseCode()
    {
        return $this->getProperty("EmbedServiceResponseCode");
    }
    /**
     * @var integer
     */
    public function setEmbedServiceResponseCode($value)
    {
        $this->setProperty("EmbedServiceResponseCode", $value, true);
    }
    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->getProperty("ErrorMessage");
    }
    /**
     * @var string
     */
    public function setErrorMessage($value)
    {
        $this->setProperty("ErrorMessage", $value, true);
    }
    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->getProperty("Html");
    }
    /**
     * @var string
     */
    public function setHtml($value)
    {
        $this->setProperty("Html", $value, true);
    }
    /**
     * @return string
     */
    public function getListId()
    {
        return $this->getProperty("ListId");
    }
    /**
     * @var string
     */
    public function setListId($value)
    {
        $this->setProperty("ListId", $value, true);
    }
    /**
     * @return string
     */
    public function getPublisherName()
    {
        return $this->getProperty("PublisherName");
    }
    /**
     * @var string
     */
    public function setPublisherName($value)
    {
        $this->setProperty("PublisherName", $value, true);
    }
    /**
     * @return integer
     */
    public function getResponseCode()
    {
        return $this->getProperty("ResponseCode");
    }
    /**
     * @var integer
     */
    public function setResponseCode($value)
    {
        $this->setProperty("ResponseCode", $value, true);
    }
    /**
     * @return string
     */
    public function getSiteId()
    {
        return $this->getProperty("SiteId");
    }
    /**
     * @var string
     */
    public function setSiteId($value)
    {
        $this->setProperty("SiteId", $value, true);
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
    public function getType()
    {
        return $this->getProperty("Type");
    }
    /**
     * @var string
     */
    public function setType($value)
    {
        $this->setProperty("Type", $value, true);
    }
    /**
     * @return string
     */
    public function getUniqueId()
    {
        return $this->getProperty("UniqueId");
    }
    /**
     * @var string
     */
    public function setUniqueId($value)
    {
        $this->setProperty("UniqueId", $value, true);
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
    public function getVideoId()
    {
        return $this->getProperty("VideoId");
    }
    /**
     * @var string
     */
    public function setVideoId($value)
    {
        $this->setProperty("VideoId", $value, true);
    }
    /**
     * @return string
     */
    public function getWebId()
    {
        return $this->getProperty("WebId");
    }
    /**
     * @var string
     */
    public function setWebId($value)
    {
        $this->setProperty("WebId", $value, true);
    }
    /**
     * @return string
     */
    public function getDecodedUrl()
    {
        return $this->getProperty("DecodedUrl");
    }
    /**
     * @var string
     */
    public function setDecodedUrl($value)
    {
        return $this->setProperty("DecodedUrl", $value, true);
    }
    /**
     * @return integer
     */
    public function getDurationInSeconds()
    {
        return $this->getProperty("DurationInSeconds");
    }
    /**
     * @var integer
     */
    public function setDurationInSeconds($value)
    {
        return $this->setProperty("DurationInSeconds", $value, true);
    }
}