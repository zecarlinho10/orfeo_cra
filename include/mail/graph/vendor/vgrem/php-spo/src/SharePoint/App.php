<?php

/**
 * Generated 2019-11-17T17:00:44+00:00 16.0.19506.12022
 */
namespace Office365\SharePoint;


/**
 * Provides 
 * methods for retrieving installed app instances.<186> All methods in SP.AppCatalog 
 * are static.
 */
class App extends BaseEntity
{
    /**
     * @return string
     */
    public function getAssetId()
    {
        if (!$this->isPropertyAvailable("AssetId")) {
            return null;
        }
        return $this->getProperty("AssetId");
    }
    /**
     * @var string
     */
    public function setAssetId($value)
    {
        $this->setProperty("AssetId", $value, true);
    }
    /**
     * @return string
     */
    public function getContentMarket()
    {
        if (!$this->isPropertyAvailable("ContentMarket")) {
            return null;
        }
        return $this->getProperty("ContentMarket");
    }
    /**
     * @var string
     */
    public function setContentMarket($value)
    {
        $this->setProperty("ContentMarket", $value, true);
    }
    /**
     * @return string
     */
    public function getVersionString()
    {
        if (!$this->isPropertyAvailable("VersionString")) {
            return null;
        }
        return $this->getProperty("VersionString");
    }
    /**
     * @var string
     */
    public function setVersionString($value)
    {
        $this->setProperty("VersionString", $value, true);
    }
}
