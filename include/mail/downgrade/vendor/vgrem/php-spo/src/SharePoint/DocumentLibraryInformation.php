<?php

/**
 * Generated 2021-04-23T09:48:37+00:00 16.0.21207.12005
 */
namespace Office365\SharePoint;

use Office365\Runtime\ClientValue;
/**
 * Specifies 
 * the information for a document library on 
 * a site 
 * (2).
 */
class DocumentLibraryInformation extends ClientValue
{
    /**
     * Absolute 
     * Url of the document library.
     * @var string
     */
    public $AbsoluteUrl;
    /**
     * @var bool
     */
    public $FromCrossFarm;
    /**
     * Identifies 
     * the modified date of the document library.
     * @var string
     */
    public $Modified;
    /**
     * Identifies 
     * a friendly display for the modified date of the document library.
     * @var string
     */
    public $ModifiedFriendlyDisplay;
    /**
     * Identifies 
     * the server-relative 
     * URL of the document library.
     * @var string
     */
    public $ServerRelativeUrl;
    /**
     * Identifies 
     * the title of the document library.
     * @var string
     */
    public $Title;
    /**
     * @var string
     */
    public $Id;
    /**
     * @var string
     */
    public $DriveId;
    /**
     * @var bool
     */
    public $IsDefaultDocumentLibrary;
}